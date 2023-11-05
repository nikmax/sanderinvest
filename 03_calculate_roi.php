<?php
$sql = "
  insert into $t3 (amount,user_id,date,reason,code_id,text,other)
  select 
     abs(sum(if(u.code_id = 9, u.amount,0))) * (h.profit + h.swap) / h.equity
                    as amount
     , u.user_id    as user_id
     , h.close_time as date
     , h.ticket     as reason
     , '1'          as code_id
     , concat(format(100 * (h.profit + h.swap) / h.equity,2),'%') as text
     , $acc as other
  from  $t3 u, $t1 h
  where u.date <= h.open_time and h.id = $id
  group by u.user_id";  // roi
$r = sql_query($sql); // Return of Investment
?>
