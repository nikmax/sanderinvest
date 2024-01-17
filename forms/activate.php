<?php if(!isset($con)) exit("falsch verbunden");

if (isset($_POST['psw']) && isset($_POST['psw2']) ){
	$show=' show';
	if ($_POST['psw'] == '' || $_POST['psw2'] == '' ) $msg= 'empty password not allowed.';
	else if ($_POST['psw'] != $_POST['psw2'] ) $msg= 'Passwords are different.';
	else $show='';
}
else if(empty($_GET['id'])) require 'error-404.php';

if($_GET['activate'] == 'r'){
		$header='Account activate';}
if($_GET['activate'] == 'f'){
		$header='Passwort reset';}
require 'head.php';
?>
  <main>
    <div class="container">

      <section class="section min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

              <div class="d-flex justify-content-center py-4">
                <a href="/" class="logo d-flex align-items-center w-auto">
                  <img src="assets/img/logo.jpg" alt="">
                  <span class="d-none d-lg-block"><?=$company;?></span>
                </a>
              </div><!-- End Logo -->

              <div class="card mb-3">

                <div class="card-body">

                  <div class="pt-4 pb-2">
                    <h5 class="card-title text-center pb-0 fs-4"><?=$header;?></h5>
                    <p class="text-center small">Enter your new password</p>
                  </div>

                  <form method="POST" action="?activate=<?php htmlentities($_GET['activate']);?>&id=<?php htmlentities($_GET['id']);?>" class="row g-3 needs-validation" id="form-register" novalidate>
                     <div class="col-12">
                      <label for="psw" class="form-label">Password</label>
                      <input type="password" name="psw" class="form-control" id="psw" required autofocus>
                      <div class="invalid-feedback">Please enter your password!</div>
                    </div>
                     <div class="col-12">
                      <label for="psw2" class="form-label">Password repeat</label>
                      <input type="password" name="psw2" class="form-control" id="psw2" required>
                      <div class="invalid-feedback">Please convirm your password!</div>
                    </div>
                    <div class="col-12">
                      <button class="btn btn-primary w-100" type="submit">Submit</button>
                    </div>
                    <div class="col-12">
                    <div class="alert alert-danger bg-danger text-light border-0 
                    alert-dismissible fade<?=$show;?>" role="alert"><?=$msg;?>
						<button type="button" class="btn-close btn-close-white" 
                        data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    </div>
                  </form>

                </div>
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
