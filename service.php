<?php
if(!isset($_SESSION['user_id'])) exit("falsch verbunden"); 


if(isset($_GET['acc_user'])){




	$bl = $con -> query("set @a=0");
	$bl = $con -> query("set @c=0");
	$sql = "select 
	    concat('[',
	       '\"',@c:= @c+1,'\",'
	      ,'\"',date_format(u.date,'%Y-%m-%d %H:%i'),'\",' -- &#8209;
	      ,'\"',c.username,'\",'
	      ,'\"',e.Text,'\",'
	      ,'\"',format(u.amount,2),'\",'
	      ,'\"',format(@a:=@a+u.amount,2),'\",'
	      ,'\"',b.Kurzname,'\"'
	      ,']') as s
	    from $t3 u 
	    left join $t4 c on u.user_id = c.id
	    left join $t5 e on u.code_id = e.id
	    left join $t6 a on u.other = a.id
	    left join $t7 b on a.Broker_id = b.id
	    order by u.date asc, u.id asc";
	$bl = $con -> query($sql) or die("ERROR1 : ".$con->error);
	echo '{"headings":["#","Date","User","Type","Amount","Sum","From"],"data":[';
	$r=$bl->fetch_array();echo $r[0];
	while($r=$bl->fetch_array()) echo ','.$r[0];
	echo ']}';
} // acc_user
if(isset($_GET['acc_history'])){
$sql="select 
    h.brokeraccount, h.open_time, h.profit,
    h.swap, h.equity
    ,concat(
    	'\"',b.Kurzname,'-',h.brokeraccount,'\",'
    	'\"',h.ticket,'\",'
    	'\"',if(h.type=0,'BUY','SELL'),'\",'
    	'\"',REPLACE(h.symbol,'micro',''),'\",'
    	'\"',h.lots,'\",'
    	'\"',date_format(h.open_time,'%Y-%m-%d %H:%i'),'\",'
    	'\"',ifnull(date_format(h.close_time,'%Y-%m-%d %H:%i'),'in work'),'\"') as line from $t1 h
          left join adm_brokeraccount a on h.brokeraccount = a.id
          LEFT JOIN adm_broker b ON a.Broker_id = b.id";
$tr = $con -> query($sql) or die("ERROR2 : ".$con->error);
$x=true;
echo '{"headings":["Broker","Ticket","Type","Symbol","Volume",
"Open Time","Close Time","Profit/Invest"],"data":[';
while($r = $tr->fetch_array()){
    $sql = "select ifnull(abs(sum( if(code_id = 9, amount,0))),0) from $t3 
        where other = '${r[0]}' and  date <= '${r[1]}'";
    $res = $con -> query($sql) or die("ERROR3 : ".$con->error);
    list($equity) = $res->fetch_array();
    if($equity == 0) $e = number_format(($r[2]+$r[3]),2);
    else $e = number_format(($r[2]+$r[3])/$r[4]*$equity,2).' / '.number_format($equity,2);
    if($x) $x=false;
    else echo ',';
    echo '['.$r[5].',"'.$e.'"]';
}
echo ']}';
} // acc_history
exit();
?>