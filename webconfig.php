<?php
	include 'conf.php';
	$db = new mysqli($HOST, $USER1, $PSW1,$DB1);
	if ($db->connect_errno) {
    	die("Verbindung fehlgeschlagen: " . $db->connect_error);
	}

	
	$comm = "SETKA";
	define("OP_BUY",  0); define("OP_SELL", 1);
	// 
      	define("Number",0);
    	define("MarketTime",1); 
    	define("Balance",2); 
    	define("Equity",3); 
    	define("MaxLot",4); 
    	define("MinLot",5);
    	define("Leverage",6);
    	define("Currency",7);
    	define("Name",8);
    	define("Broker",9);
    	define("LotSize",10);
    	define("cSymbol", 11);
    	define("Email",12);
    	define("isDemo",13);
    	define("AccProfit",14);
      	define("AccCredit",15);
      	define("AccFreeMargin",16);
	// ticket;opentime;digits;point;magicnumber;symbol;type;lot;openprice;sl;tp;profit;bid;ask;commission;swap
		define("TicketNumber", 0); define("OpenTime",     1);
		define("Digits",       2); define("Point",        3);
		define("MagicNumber",  4); define("Symbol",       5);
		define("Type",         6); define("Lots",         7);
		define("OpenPrice",    8); define("StopLoss",     9);
		define("TakeProfit",   10);define("Profit",       11);
		define("Bid",          12); define("Ask",         13);
		define("Commission",   14); define("Swap",        15);

  //+-----------------------------------------------------------------------+----------+----------+
  //      SYM    | B/S  |  #/n  | TP(pp)        | P/L         | nextLot/pp  | CloseAll | OrderNow |
  //-------------+------+-------+---------------+-------------+-------------+----------+----------+
  // GBPUSDmicro | BUY  | 1/15  | 1.24434(32.3) | -0.93 EUR   | 0.02/11.8   | button   | button   |
  //-------------+------+-------+---------------+-------------+-------------+----------+----------+
  // EURUSDmicro | SELL | 10/15 | 1.23334(25.3) | -333.93 EUR | 1.38/20.8   |
  //-------------+------+-------+---------------+-------------+-------------+
  //                                                                 ^^ wenn RED -> not enough Money !


    $km = 1.6;  $ks = 15; $ktp = 10; $kst = 20; $ksti = 0;
    $kmagic = 1000000;
?>
