<?php if(!isset($con)) exit("falsch verbunden");

  $header='Attention!';
  $message='The confirm Link has expiries!';

if($_GET['confirm']=='activate'){
	$header = 'Wait for activate your account';
	$message = 'Your account ist not yet activated.<br>We recently sent an activation mail.<br>Please check your mailbox.';}
if($_GET['confirm']=='forgot'){
	$header='Password recovery';
	$message='We sent a mail with recovery instructions. Please check your mailbox.';}
if($_GET['confirm']=='register'){
	$header='Confirm your new account';
	$message='We sent a mail with account activate instructions. Please check your mailbox.';}

require 'head.php';
?>
  <main style="">
    <div class="container">
    <section class="section error-404 min-vh-100 d-flex flex-column align-items-center justify-content-center">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">
              <h5 class="card-title"><?=$header;?></h5>
              <p><?=$message;?></p>
              <a class="btn" href="?">Back to home</a>
            </div>
          </div>

        </div>


      </div>
    </section>

</div>
</main>
<?php
require 'foot.php';
exit();
?>
