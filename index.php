<?php
session_start();
require 'assets/sql/config.php';

/* actions */
if(isset($_GET['logout'])) require 'forms/logout.php';
if(isset($_GET['activate'])) require 'forms/activate.php';
if(isset($_GET['confirm'])) require 'forms/confirm.php';
if(isset($_GET['login'])) require 'forms/login.php';
if(isset($_GET['forgot'])) require 'forms/forgot.php';
if(isset($_GET['register'])) require 'forms/register.php';

/* start here */
if(!isset($_SESSION['user_id'])) require 'forms/login.php';
$page = 'pages';$file = 'home';

/*/ Sonderbehandlung //
	if(isset($_GET['action'])) require "forms/action.php";
	if(isset($_GET['create'])) require "users/create.php";
	if(isset($_GET['logout'])) require "pages/logout.php";
	//if(isset($_GET['login'])) require "forms/login.php"; 
	if(isset($_GET['forgot'])) require "forms/forgot.php"; 
	if(isset($_GET['contact'])) require "forms/contact.php";*/


/*$json = file_get_contents("assets/json/sidebar.json");
	$nav = json_decode($json,true);
	$get = explode('/', key($_GET));
	$get2 = $get;
	$cmd1 = array_shift($get) or $cmd1 ='';
	$cmd2 = array_shift($get) or $cmd2 ='';

	foreach ($nav['items'] as $ar1) 
	  if (isset($ar1['path'])  and strlen($cmd1) > 0 and strpos($ar1['path'], $cmd1)  ){
		$page = $cmd1;
		$file = 'error-404'; 
		if(isset($ar1['items'])){ 
		  foreach ($ar1['items'] as $ar2) 
			if (isset($ar2['file']) and strlen($cmd2) > 0 and $ar2['file'] == $cmd2) {
			  $file = $ar2['file'];break;
		  } 
		}else if(isset($ar1['file']) and $ar1['file'] == $cmd2) {$file = $ar1['file'];break;} 
	  }//foreach nav*/


require 'head.php';
require 'navis/header.php';
//require 'structs/sidebar.php';
echo '<main id="main" class="main">';
//require 'structs/pagetitle.php';
require $page . '/' . $file .'.php';
echo '</main>';
require 'foot.php';



?>
