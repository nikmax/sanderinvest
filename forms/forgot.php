<?php
  require "structs/head.php";
  echo '<body><main><div class="container">';
  if ($_POST['user'] != "" ){
    require "forms/forgot_1.php";
  }else if ($_POST['code'] != ""){
    require "forms/forgot_2.php";
  } else {
    require "forms/forgot_3.php";
  }
  require "structs/footer.php";
  exit();
?>
