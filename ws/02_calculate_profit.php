<?php
// update equity for closed position if null
$sql = "
            update $t1 h set equity = (select abs(sum( if(code_id = 9, amount,0)))
            from $t3 where other = '$acc' and date <= h.open_time ) 
            where ticket='$ticket' and brokeraccount=$acc and equity is null";
//if(empty($equity)){ 
  $sql = "
        select abs(sum( ifnull(if(code_id = 9, amount,0),0))) 
        from $t3 
        where other =$acc and  date <= '$open_time'";
  $r = sql_query($sql);
  list($equity) = $r->fetch_array();
//  } // equity
// calculate return of investment
  if($equity == 0){$roi=0;$equity=0;}
  else $roi  = ($profit + $swap) / $equity; // *100 = %
  $sql  = "update $t1 set
           profit='$profit',swap='$swap',
           equity='$equity',text='$roi',
           -- close_time= if(date_format('$close_time','%m-%d %H:%i:%s') = '01-01 00:00:00',
           -- date_add('$close_time',interval (1) second),'$close_time'),
           close_time='$close_time',close_price='$close_price'
           where id=$id";
  $res = sql_query($sql);
?>
