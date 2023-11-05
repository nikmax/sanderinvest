<?php
	include "webconfig.php";

	$data   = array();
    $account= array();
    $cmd	= "";
    
	$ID = 0;
	$mt = 0;
	$updated;
	$maxlot      = 0;
	$minlot 	 = 0;

    if(!GetPostData($db)) exit("Welcome! Your Access will be configured...");

	$profit = number_format($account[Equity] - $account[Balance],2,".","")." ".$account[Currency]; 
	
	$kopf  = "";
	$kopf .= $profit;
	$kopf .= "\n" . $updated;
	$kopf .= "\nBalance/Equity:, ". $account[Balance] . "," . $account[Equity];
	$kopf .= "\nLeverage/MaxLot/MinLot/LotSize:, " . $account[Leverage].",". $account[MaxLot] ."," . $account[MinLot] .",".$account[LotSize].",Market ".($mt==0?"closed":"is open") ." ** " . ($account[isDemo]==0?"REAL":"DEMO");

	$info  = "";
    $info     = "\n+------+-------+------+---------+-----------------------+---------------+---------------+---------------+";
	$info    .= "\n|  *   |  SYM  | B/S  |  #/n    | TP(pp)                | P/L           | nextLot/pp    |    Stealth    |";
    $info    = "\n+------+-------+------+---------+-----------------------+---------------+---------------+---------------+";


	// ,EA.LotK,EA.StepP,EA.StepI,EA.TP
    // ,$km,$kst,$ksti,$ktp

	$sql = "SELECT
			EA.ID,EA.ID+".$kmagic.",EA.Active,CONCAT(s.Name,a.Suffix),EA.Type,
			EA.Plan,EA.Stealth,EA.MoneyM,EA.StartLot,EA.MaxOrders,EA.LotK,EA.StepP,EA.StepI,EA.TP
			FROM EA 
			LEFT JOIN Symbol  s ON s.ID = EA.Symbol
			LEFT JOIN Account a ON a.ID = EA.Account
			where EA.Account = ?
	";
	if (!$stmt = $db->prepare($sql)) die("prepare failed(ea) ".$db->error);
	if(!$stmt->bind_param('i', $ID)) die("binding failed(ea) ".$stmt->error);
	if(!$stmt->execute()) die("execute failed(ea) ".$stmt->error);
	$result = $stmt->store_result();
	$len = $stmt->num_rows;
	if($len != 0){
		if(!$stmt->bind_result($_id,$_magic,$_active,$_sym,$_type,$_plan,$_stealth,$_mm,$_startlot,$_maxorders,$km,$kst,$ksti,$ktp)) 
			die("bind result failed(ea) ".$stmt->error);
		while($stmt->fetch()){
			$maxlot      = $account[MaxLot];
			$minlot 	 = $account[MinLot];

			$PL       = 0; $nextLot  = 0;
			$AvgTP    = 0; $nOrders  = 0;
			$Ask      = 0; $Bid    = 0;
			
			$curPrice = 0; $curLot = 0; $curProfit = 0; $curTP = 0;
			$diff_s   = 0; $diff_t = 0;
			$point    = 0; $digits = 4; $PDiv = 1;

			if($_mm > 0) {
				$_startlot   = number_format(($account[Balance]+$account[AccCredit]) / $_mm / $account[LotSize],2,".","");
				$min_balance = number_format($minlot * $_mm * $account[LotSize],2,".","");
				$limit_lot   = number_format($maxlot/($km^$_maxorders),2);

				if($_startlot > $limit_lot){
					$_startlot = $limit_lot;
					echo "Warning ! Limit reached! LotSize: " .$start_lot ;
				}

				if ($_startlot < $minlot || $limit_lot < $minlot){
					exit(" \n*** Needed Balance at least : ". $min_balance . " " .$account[Currency]);
					continue;
				}
			}


			GetOrdersInfo();
			//if ($_plan == 2) 
			$mykst = ($nOrders == 0) ? ($kst):($kst + (($nOrders-1)*$ksti)); 
			//else $mykst = $kst;
				$diff_s  = number_format($mykst - (($_type == OP_BUY) ?($curPrice-$Ask):($Bid-$curPrice))/$PDiv*pow(10,$digits),1,".","");
				$diff_t  = number_format((($_type == OP_BUY) ?($AvgTP-$Bid):($Ask-$AvgTP))/$PDiv*pow(10,$digits),1,".","");
			
			$info .= "\n| ". ($_active?" ON  ":" OFF ") . "|" . $_sym . (($_type == OP_BUY) ? " | BUY  " : " | SELL ");
			$info .= "| " . $nOrders . "/". $_maxorders;$info .= " | "  . $AvgTP;
			$info .= "(". $diff_t .")";
			$info .= "  | "  . $PL;$info .= " | " . $nextLot . "(". $diff_s .") |";
			$info .= ($_stealth ? " ON":" OFF")." | ";
			
			if($mt == 0) continue;
			UpdateEAsInfo($db,$_id,$_startlot,$_maxorders,$nOrders,$AvgTP,$nextLot,$diff_t,$diff_s,$PL);
			if($nOrders == 0){
				if($_active){
					$cmd .= ";ordersend=".$_sym.",".$_type.",".$nextLot.",".$comm."-".$nOrders.",".$_magic;
		  		}
		  		continue;
			}
			// ab hier : $nOrders != 0
			
			if($_close) {
				$cmd .= ";closeallorders=".$_magic;
				continue;
			}
			// not close
			if($diff_s <= 0) {
				if($nOrders < $_maxorders){
					$cmd .= ";ordersend=".$_sym.",".$_type.",".$nextLot.",".$comm."-".$nOrders.",".$_magic;
					continue;  //// neu hinzugefügt
				}else if($_plan != 1){
					$cmd .= ";closeallorders=".$_magic;
					continue;
				}
	  		}
	  		if($_stealth){
	  			if($curTP != 0) $cmd .= ";takeprofit=".$_magic.",0";
	  			elseif(($_type == OP_BUY  && $AvgTP <= $Bid) || ($_type == OP_SELL && $Ask <= $AvgTP)){
					$cmd .= ";closeallorders=".$_magic;
	  			}
	  		}else if($AvgTP != $curTP){
				$cmd .= ";takeprofit=".$_magic.",".$AvgTP;
	  		}
		} // while
	}
	$stmt->free_result();
	$stmt->close();

    $info     .= "\n+------+-------+------+---------+-----------------------+---------------+---------------+---------------+";

    exit($kopf.$info."\n");
	
	function GetOrdersInfo(){
		global $account, $_sym;
		// INPUT
		global $data, $_magic, $_type, $_startlot;
		global $PL, $maxlot,$minlot, $km, $ktp;
		
		// OUTPUT
		global $nextLot, $AvgTP, $nOrders, $Ask, $Bid;
		global $PDiv, $digits, $point;
		// INTERN
		global $curPrice, $curLot, $curProfit, $curTP;

		$AvgPrice=0; $AvgLot=0;$curTime=0;
		$nOrders = 0;
		$order = Array();
		$len = count($data);
		if($len !=0){
		for ($i = 0; $i < $len; $i++){
			$order = explode(";",$data[$i]);
			if($order[MagicNumber] == $_magic){
				if ($order[Digits] == 2 || $order[Digits] == 4) $PDiv = 1;
				else $PDiv = 10;
				$digits = $order[Digits];
				$point  = $order[Point];
				$Ask    = $order[Ask];
				$Bid    = $order[Bid];

		    	$nOrders++;
			    $curProfit += $order[Profit] + $order[Commission]+$order[Swap];
				$curTP  = $order[TakeProfit];
	          	$AvgPrice += $order[OpenPrice]*$order[Lots];
	          	$AvgLot += $order[Lots];
				$PL += $order[Profit];
			    if($curTime < $order[OpenTime]){
			        	$curPrice = $order[OpenPrice];
			        	$curLot = $order[Lots];
			    }
			}
		}
		}
		$PL = number_format($PL,2,".","");
		if($nOrders == 0) $nextLot = $_startlot;
		else{
			$nextLot = round($curLot*$km,2);
			if($_type == OP_BUY) $AvgTP = round($AvgPrice / $AvgLot + NDP($ktp), $digits);// round($AvgPrice / $AvgLot + NDP($ktp-$nOrders), $digits);
			else                 $AvgTP = round($AvgPrice / $AvgLot - NDP($ktp), $digits); // round($AvgPrice / $AvgLot - NDP($ktp-$nOrders), $digits);
		}
		if($nextLot > $maxlot) $nextLot = $maxlot;
		if($nextLot < $minlot) $nextLot = $minlot;
	}
	function UpdateEAsInfo($_db,$ID,$StartLot,$MaxOrders,$OpenOrders,$TakeProfit,$NextLot,$TPp,$NLp,$Profit){
			$sql  = "UPDATE EA ";
			$sql .= " SET StartLot=?,MaxOrders = ?,OpenOrders = ?,TakeProfit = ?,NextLot = ?,TPp = ?,NLp = ?, Profit = ?";
			$sql .= " WHERE ID = ?";
			if (!$stmt = $_db->prepare($sql)) die("prepare failed(UpEA) ".$_db->error."#".$sql);
			if(!$stmt->bind_param('diidddddi',$StartLot,$MaxOrders,$OpenOrders,$TakeProfit,$NextLot,$TPp,$NLp,$Profit,$ID)) die("binding failed(UpEA) ".$stmt->error);
			if(!$stmt->execute()) die("execute failed(UpEA) ".$stmt->error);
	}
	function NDP($price){
		global $point, $digits, $PDiv;
 		return round($price*$point*$PDiv, $digits);
	}
	function GetPostData($db){
		global $data, $account,$ID,$mt,$updated;
		$post = file_get_contents("php://input");
    	$data = explode("\n", $post);
    	array_shift($data);  // entferne Spaltennamen
    	$account = explode(";", array_shift($data));

    	if($account[Number] != "" && $account[Name] != "" && $account[Broker] != ""){
	   		$_br = getBroker($db,$account[Broker]);
			$sql = "SELECT ID,MarketTime,Updated FROM Account where Number = ? AND Broker = ?";
			if (!$stmt = $db->prepare($sql)) die("prepare failed(1) ".$db->error);
			if(!$stmt->bind_param('ii', $account[Number], $_br)) die("binding failed(1) ".$stmt->error);
			if(!$stmt->execute()) die("execute failed(1) ".$stmt->error);
			$result = $stmt->store_result();
			$ID = $stmt->num_rows;
			if($ID != 0){
				$stmt->bind_result($ID,$mt,$updated);
				$stmt->fetch();
				$stmt->free_result();
				$stmt->close();
				//$mt .= ":".$account[MarketTime];
				if($mt == $account[MarketTime]) $mt = 0;
				else $mt = 1;
				$sql  = "UPDATE Account ";
				$sql .= " SET Balance = ?,Equity = ?,Profit = ?,MarketTime = ?,MarketOpen = ?,Email = ?, Credit = ?, FreeMargin = ?, Updated = now()";
				$sql .= " WHERE ID = ?";
				if (!$stmt = $db->prepare($sql)) die("prepare failed(2) ".$db->error."#".$sql);
				if(!$stmt->bind_param('dddsisddi',$account[Balance], $account[Equity], $account[AccProfit], $account[MarketTime], $mt,$account[Email],$account[AccCredit],$account[AccFreeMargin], $ID)) die("binding failed(2) ".$stmt->error);
				if(!$stmt->execute()) die("execute failed(2) ".$stmt->error);			
			}else{
				$stmt->free_result();
				$stmt->close();
				$pf = "";
				$mt = 1;
				if(strlen($account[cSymbol]) > 6) $pf = substr($account[cSymbol], 6);
				$sql  = "INSERT INTO Account ";
				$sql .= "(Number,Name,Broker,isDemo,Balance,Equity,Profit,Leverage,MaxLot,MinLot,LotSize,MarketOpen,MarketTime,Currency,Suffix,Email)";
				$sql .= " VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
				if (!$stmt = $db->prepare($sql)) die("prepare failed(3) ".$db->error."#".$sql);
				if(!$stmt->bind_param('isiidddiidiissss',$account[Number], $account[Name],$_br, $account[isDemo], $account[Balance], $account[Equity], $account[AccProfit], $account[Leverage], $account[MaxLot], $account[MinLot], $account[LotSize], $mt, $account[MarketTime], $account[Currency],$pf,$account[Email])) die("binding failed(3) ".$stmt->error);
				if(!$stmt->execute()) die("execute failed(3) ".$stmt->error);
				$ID = $stmt->insert_id;
				$stmt->free_result();
				$stmt->close();
				/*
					$sql  = "UPDATE pref SET NextMagic = LAST_INSERT_ID(NextMagic) + 100";
					$sql .= " WHERE ID = 1";
					if (!$stmt = $_db->prepare($sql)) die("prepare failed(4) ".$_db->error."#".$sql);
					if(!$stmt->execute()) die("execute failed(4) ".$stmt->error);
					$magic = $stmt->insert_id;
					$stmt->free_result();
					$stmt->close();
				*/
				$sql  = "INSERT INTO EA (Account,Symbol,Type) VALUES (?,?,?)";
				if (!$stmt = $db->prepare($sql)) die("prepare failed(16) ".$db->error."#".$sql);
				if(!$stmt->bind_param('iii',$ID,$sym,$type)) die("binding failed(16) ".$stmt->error);
				$sym = 0;$type = 0;
				if(!$stmt->execute()) die("execute failed(1.6) ".$stmt->error);
				$sym = 0;$type = 1;
				if(!$stmt->execute()) die("execute failed(2.6) ".$stmt->error);

				SendEmail("Neues Account (".$account[Number]."):".$account[Name]);
			}
			$stmt->free_result();
			$stmt->close();
			$sql = "SELECT Stand FROM Balance where ID = (SELECT MAX(ID) FROM Balance WHERE Account = ?)";
			if (!$stmt = $db->prepare($sql)) die("prepare failed(5) ".$db->error);
			if(!$stmt->bind_param('i', $ID)) die("binding failed(5) ".$stmt->error);
			if(!$stmt->execute()) die("execute failed(5) ".$stmt->error);
			//$result = $stmt->store_result();
			//$NUM = $stmt->num_rows;
			$stmt->bind_result($MAX);
			$stmt->fetch();
			if($MAX != $account[Balance]){
				$stmt->free_result();
				$stmt->close();
				$sql  = "INSERT INTO Balance (Account,Datum,Stand) VALUES (?,?,?)";
				if (!$stmt = $db->prepare($sql)) die("prepare failed(6) ".$db->error."#".$sql);
				if(!$stmt->bind_param('isd',$ID, $account[MarketTime], $account[Balance])) die("binding failed(6) ".$stmt->error);
				if(!$stmt->execute()) die("execute failed(6) ".$stmt->error);
			}
			$stmt->free_result();
			$stmt->close();
			return true;
		}
		return false;
	}
	function SendEmail($emailbody){
		$to = 'anton@sander.uk';
		$email_subject = "Neues Erreignis bei sanderinvest";
		$email_body = $emailbody;
		$headers = "From: sanderinvest_ltd<info@sanderinvest.com>\n"; 
		$headers .= "Reply-To: ";	
		mail($to,$email_subject,$email_body,$headers);
	}
	function getBroker($db,$name){
		$ID = 0;
		$sql = "SELECT ID FROM Broker where Name = ? LIMIT 1";
		if(!$stmt = $db->prepare($sql)) die ("prepare failed (gb)");
		$stmt->bind_param('s', $name);
		if(!$stmt->execute()) die("execute failed (gb)");
		$result = $stmt->store_result();
		if($stmt->num_rows != 0){
			if(!$stmt->bind_result($ID) ) die("bind_result failed (gb)");
			$stmt->fetch();
			$stmt->close();
			return $ID;
		}else{
			die("not OK");
			$stmt->close();
			$sql = "INSERT INTO Broker (Name) VALUES (?)";
			if(!$stmt = $db->prepare($sql)) die ("prepare failed (insert gb)");
			$stmt->bind_param('s', $name);
			if(!$stmt->execute()) die("execute failed (gb)");
			$ID = $stmt->insert_id;
			$stmt->close();
			return $ID;
		}
	}
?>
