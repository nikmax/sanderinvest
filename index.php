<?php

session_start();

// vars
require "assets/sql/config.php";
$con = new mysqli($ds,$du,$dp,$db);
if ($con -> connect_error) {
      $log = date("F j, Y, g:i a") . ' - db connect error : ' . 
      $con -> connect_error ."\n";
      file_put_contents('log_'.date("j.n.Y").'.log', $log, FILE_APPEND);
      require "structs/head.php";
      echo '<body><main><div class="container"><h1>db connect error</h1>'.
    		'<p>'.$con -> connect_error.'</p></div></main>';
      require 'structs/footer.php';
      exit();
    }
// Sonderbehandlung //
if(isset($_GET['contact'])) require "forms/contact.php";
if(isset($_GET['create'])) require "users/register.php";
if(isset($_GET['logout'])) require "pages/logout.php";
if(isset($_GET['login'])) require "forms/login.php"; 
if(isset($_GET['forgot'])) require "users/forgot.php"; 
if(!$_SESSION['user_id'])  require "forms/login.php";

$json = file_get_contents("assets/json/sidebar.json");
$nav = json_decode($json,true);
$get = explode('/', key($_GET));
$get2 = $get;
$cmd1 = array_shift($get) or $cmd1 ='';
$cmd2 = array_shift($get) or $cmd2 ='';
$page = 'pages';
$file = 'home';
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
  }//foreach nav


require "structs/head.php";
require "structs/header.php";
require "structs/sidebar.php";
echo '<main id="main" class="main">';
//require "structs/pagetitle.php";
require $page . '/' . $file .'.php';
echo '</main><!-- End #main -->';
require "structs/footer.php";



?>
