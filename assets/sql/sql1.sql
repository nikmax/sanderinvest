select t3.`user_id` AS `user`

,sum(if(((t3.`date` > '2021-12-31') and (t3.`date` < '2022-02-01') and (t3.`code_id` = 1)),t3.`amount`,0)) AS `Jan 22` 
,sum(if(((t3.`date` > '2022-01-31') and (t3.`date` < '2022-03-01') and (t3.`code_id` = 1)),t3.`amount`,0)) AS `Feb 22` 
,sum(if(((t3.`date` > '2022-02-31') and (t3.`date` < '2022-04-01') and (t3.`code_id` = 1)),t3.`amount`,0)) AS `Mrz 22` 
,sum(if(((t3.`date` > '2022-03-31') and (t3.`date` < '2022-05-01') and (t3.`code_id` = 1)),t3.`amount`,0)) AS `Apr 22`
,sum(if(((t3.`date` > '2022-04-31') and (t3.`date` < '2022-06-01') and (t3.`code_id` = 1)),t3.`amount`,0)) AS `Mai 22`
,sum(if(((t3.`date` > '2022-05-31') and (t3.`date` < '2022-07-01') and (t3.`code_id` = 1)),t3.`amount`,0)) AS `Jun 22`
,sum(if(((t3.`date` > '2022-06-31') and (t3.`date` < '2022-08-01') and (t3.`code_id` = 1)),t3.`amount`,0)) AS `Jul 22`
,sum(if(((t3.`date` > '2022-07-31') and (t3.`date` < '2022-09-01') and (t3.`code_id` = 1)),t3.`amount`,0)) AS `Aug 22`
,sum(if(((t3.`date` > '2022-08-31') and (t3.`date` < '2022-10-01') and (t3.`code_id` = 1)),t3.`amount`,0)) AS `Sep 22`
,sum(if(((t3.`date` > '2022-09-31') and (t3.`date` < '2022-11-01') and (t3.`code_id` = 1)),t3.`amount`,0)) AS `Okt 22`
,sum(if(((t3.`date` > '2022-10-31') and (t3.`date` < '2022-12-01') and (t3.`code_id` = 1)),t3.`amount`,0)) AS `Nov 22`
,sum(if(((t3.`date` > '2022-11-31') and (t3.`date` < '2023-01-01') and (t3.`code_id` = 1)),t3.`amount`,0)) AS `Dez 22`
,sum(if(((t3.`date` > '2022-12-31') and (t3.`date` < '2023-02-01') and (t3.`code_id` = 1)),t3.`amount`,0)) AS `Jan 23`
,sum(if(((t3.`date` > '2023-01-31') and (t3.`date` < '2023-03-01') and (t3.`code_id` = 1)),t3.`amount`,0)) AS `Feb 23` 
,sum(if(((t3.`date` > '2023-02-31') and (t3.`date` < '2023-04-01') and (t3.`code_id` = 1)),t3.`amount`,0)) AS `Mrz 23` 

from `acc_user` as t3 group by t3.`user_id`


;


set @u := 2;
SELECT a.code_id, c.Text, sum(a.amount) , count(a.code_id)
FROM `acc_user` a 
left join adm_entrycode c on a.code_id = c.id
where a.user_id = @u group by code_id
;


select id,@t := open_time, @p :=(profit+ swap)/equity, 
(select @s :=sum(amount) from acc_user where code_id = 9 and date <= @t and user_id = @u) s, 
@h := abs(@s)*@p, @x := @x+@h
from acc_history where close_time is null
