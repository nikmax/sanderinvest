<?php
//if(!isset($con)) 
require 'head.php';
empty($message) && $message = 'The page you are looking for does not exist.';
?>

<section class="section error-404 min-vh-100 d-flex flex-column align-items-center justify-content-center">
    <h1>404</h1>
    <h2><?=$message;?></h2>
    <a class="btn" href="?">Back to home</a>
    <img src="assets/img/not-found.svg" class="img-fluid py-5" alt="Page Not Found">    
</section>

<?php
//if(!isset($con)) 
require 'foot.php';
exit();
?>
