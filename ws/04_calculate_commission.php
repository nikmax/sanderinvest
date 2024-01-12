<?php

$sql = "select 
    @u := user_id
   ,@i := date_format(date_add( now() , interval (0) month), '%Y-%m-01 00:00:00' )
   ,@d := (select  max(date) from $t3 where code_id = 2 and user_id=@u and other=$acc)
   ,@d := if( @d is null, '$startdate 00:00:00', @d )
   ,@a := (select sum(if(code_id = 1, amount,0))
           from  $t3 where date > @d and date < @i and user_id = @u and other=$acc)
   ,@x := if(@a is not null and @a > 0,@a/10*3,0)
  from $t3  group by $t3.user_id";

$comms = sql_query($sql);

if($comms->num_rows != 0){
  while ($c = $comms->fetch_array()) {
    
    if ($c[5] > 0) {
        $sql = "
          insert into $t3 
          (user_id,code_id,date,reason,amount,other)  
          values (${c[0]},'2','${c[1]}','${c[4]}',-${c[5]},$acc)";
        $res = sql_query($sql);
        $amount = $c[5]/6;  // referral bonus
        $select = "select '3','${c[1]}',concat( 'from user ','${c[0]}')";
        $concat = "concat(
            @u1 := (select ref_id from $t4 where id = ${c[0]})
            ,@u2 := (select ref_id from $t4 where id = @u1) 
            ,@u3 := (select ref_id from $t4 where id = @u2) 
            ,@u4 := (select ref_id from $t4 where id = @u3) 
            ,@u5 := (select ref_id from $t4 where id = @u4))   ";
        $sql = "
            insert into $t3 (code_id,date,reason,text,user_id,amount,other)
                $select, $concat, @u1, $amount, $acc
            union all $select, null, @u2, $amount, $acc
            union all $select, null, @u4, $amount, $acc
            union all $select, null, @u5, $amount, $acc";
        $res = sql_query($sql);
        }//if $c[5]
    }//while
  }//if $comms
//exit("ok: ".$sql2);

?>
