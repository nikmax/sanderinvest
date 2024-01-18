<?php if(!isset($con)) exit("falsch verbunden");

if (isset($_POST['psw']) && isset($_POST['psw2']) ){
	if ($_POST['psw'] == '' || $_POST['psw2'] == '' ) exit('empty password not allowed.');
	if ($_POST['psw'] != $_POST['psw2'] ) exit('Passwords are different.');
	$pattern = "/^(?=.*[!§$%&=?*#_])(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z!§$%&=?*#_]).{8,}$/";
	if (!preg_match($pattern,$_POST['psw']))  exit('Password is simple.');
}
else if(empty($_POST['id'])) exit('The confirm Link has expiries!');

if($_POST['act'] == 'r')
		$sql= "UPDATE $t4 set is_active = 1, password = md5('".addslashes($_POST['psw'])."')
    where is_active = 0  and password = '".addslashes($_POST['id'])."'";
if($_POST['act'] == 'f')
		$sql="UPDATE $t4 set recover=NULL,password=md5('".addslashes($_POST['psw'])."')
		where recover = 1 and concat(password,md5(password)) = '".addslashes($_POST['id'])."'";
$res = $con -> query($sql);
$con-> error && exit('Sorry! Our Database is corrupt.');

if($con->affected_rows > 0) exit();
else exit ('Sorry, es ist was schief gelaufen, try it again.');
?>