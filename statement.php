<?php
		include "webconfig.php";
		if(isset($_GET['a']) && isset($_GET['id']) && $_GET['a'] != "" && $_GET['id'] != ""){
			$echo = "";
			$sql = "SELECT concat_ws(';',isDemo,Balance + Credit,Equity,Profit,MarketOpen,MarketTime,Currency,Updated,Coins) FROM Account where Number = ? AND ID = ?";
			if (!$stmt = $db->prepare($sql)) die("prepare failed(acc) ".$db->error);
			if(!$stmt->bind_param('ii', $_GET['a'], $_GET['id'])) die("binding failed(acc) ".$stmt->error);
			if(!$stmt->execute()) die("execute failed(acc) ".$stmt->error);
			$result = $stmt->store_result();
			
			if($stmt->num_rows != 0){
				if(!$stmt->bind_result($str)) die("bind result failed(acc) ".$stmt->error);
				$stmt->fetch();
				$echo = $str;
				$stmt->free_result();
				$stmt->close();
				// SELECT concat_ws("/",WEEK(current_time(),1), year(current_time()));
				$sql = "SELECT DATE_FORMAT(Datum,\"%Y-%m-%d\") datum, Stand, DATE_FORMAT(Now(),\"%Y-%m-%d\") heute, DATE_FORMAT(DATE_SUB(Now(),INTERVAL (DAYOFWEEK(Now())-2) DAY),\"%Y-%m-%d\") dow FROM Balance WHERE Account = ? ORDER BY ID DESC";
				if (!$stmt = $db->prepare($sql)) die("prepare failed(balance) ".$db->error);
				if(!$stmt->bind_param('i', $_GET['id'])) die("binding failed(balance) ".$stmt->error);
				if(!$stmt->execute()) die("execute failed(balance) ".$stmt->error);
				$result = $stmt->store_result();
				$echo2 = "";
				$profit = 0;
				$alt_stand = 0;
				$cur_datum = "";
				$passt = true;
				$passt2 = true;
				$sum_week = 0;
				if($stmt->num_rows != 0){
					if(!$stmt->bind_result($datum,$stand,$heute,$dow)) die("bind result failed(balance) ".$stmt->error);
					while($stmt->fetch()){
						if ($passt){
							if($heute != $datum) $passt = false;
							else{
								if($stand != 0){
									if($alt_stand !=0 and $alt_stand > $stand) $profit += ($alt_stand - $stand);
								}else $passt = false;
								
								//$passt = ($cur_datum == "" || $cur_datum == $datum);
								//$cur_datum = $datum;
								$alt_stand = $stand;
								$sum_week = $profit;
							}
						}elseif ($passt2){
							if($datum >= $dow){
								if($stand != 0){
									if($alt_stand != 0 and $alt_stand > $stand) $sum_week += ($alt_stand - $stand);
									$alt_stand = $stand;
								}else $passt2 = false;
							}else $passt2 = false;
						}
					}
				}
				$stmt->free_result();
				$stmt->close();
				$echo = number_format($profit,2) ."\n".number_format($sum_week,2)."\n".$echo;//$echo2."\n".$echo;
				$sql = "SELECT
							concat_ws(';',e.ID,e.Active,CONCAT(s.Name,''),e.Type,e.Plan,e.Stealth,e.MoneyM,e.StartLot,e.MaxOrders, e.OpenOrders, e.TakeProfit, FORMAT(e.NextLot,2), FORMAT(e.TPp,1), FORMAT(e.NLp,1), e.Profit)
						FROM EA e
						LEFT JOIN Symbol  s ON s.ID = e.Symbol
						LEFT JOIN Account a ON a.ID = e.Account
						where e.Account = ? order by e.ID asc";
				if (!$stmt = $db->prepare($sql)) die("prepare failed(ea) ".$db->error);
				if(!$stmt->bind_param('i', $_GET['id'])) die("binding failed(ea) ".$stmt->error);
				if(!$stmt->execute()) die("execute failed(ea) ".$stmt->error);
				$result = $stmt->store_result();
				$len = $stmt->num_rows;
				if($len != 0){
					//if(!$stmt->bind_result($_id,$_active,$_sym,$_type,$_plan,$_stealth,$_mm,$_startlot,$_maxorders,$_nOrders, $_AvgTp, $_nextlot, $_TPp, $_NLp, $_profit)) die("bind result failed(ea) ".$stmt->error);
					if(!$stmt->bind_result($str)) die("bind result failed(ea) ".$stmt->error);
					while($stmt->fetch()){
						//$echo .= "\n".$_active.";".$_sym." ".$_type." ".$_plan." ".$_stealth." ".$_mm." ".$_startlot." ".$_maxorders;
						//$echo .= " ".$_nOrders." ".$_AvgTp." ".$_nextlot." ".$_TPp." ".$_NLp." ".$_profit;
						$echo .= "\n".$str;
					}
				}
				$stmt->free_result();
				$stmt->close();

				exit($echo);
			}else exit("missing account");
		}
		if(isset($_GET['c']) && $_GET['c'] == "accounts"){
			$echo = "";
			$sql = "SELECT concat_ws(';',ID,Number) FROM Account";
			if (!$stmt = $db->prepare($sql)) die("prepare failed(adm) ".$db->error);
			//if(!$stmt->bind_param('ii', $_GET['a'], $_GET['id'])) die("binding failed(acc) ".$stmt->error);
			if(!$stmt->execute()) die("execute failed(adm) ".$stmt->error);
			$result = $stmt->store_result();
			$len = $stmt->num_rows;
				if($len != 0){
					//if(!$stmt->bind_result($_id,$_active,$_sym,$_type,$_plan,$_stealth,$_mm,$_startlot,$_maxorders,$_nOrders, $_AvgTp, $_nextlot, $_TPp, $_NLp, $_profit)) die("bind result failed(ea) ".$stmt->error);
					if(!$stmt->bind_result($str)) die("bind result failed(ea) ".$stmt->error);
					while($stmt->fetch()){
						//$echo .= "\n".$_active.";".$_sym." ".$_type." ".$_plan." ".$_stealth." ".$_mm." ".$_startlot." ".$_maxorders;
						//$echo .= " ".$_nOrders." ".$_AvgTp." ".$_nextlot." ".$_TPp." ".$_NLp." ".$_profit;
						$echo .= $str."\n";
					}
				}
				$stmt->free_result();
				$stmt->close();
				exit($echo);
		}
		exit("missing command");

?>
