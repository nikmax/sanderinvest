<?php if(!isset($con)) exit("falsch verbunden");
if($_GET['activate']=='r'){
    $suc='Your account is activated!';
    $header='Account activate';
    $sql= "SELECT id FROM $t4 
    where is_active = 0 and datediff(now(),date_joined) < 1 
    and password = '".addslashes($_GET['id'])."'";}
if($_GET['activate']=='f') {
    $suc='Your password is changed!';
    $header='Password recovery';
    $sql="SELECT id FROM $t4 
    where recover = 1 and timediff(now(),last_login) < '24:00:01' 
    and concat(password,md5(password)) = '".addslashes($_GET['id'])."'";}
$res = $con -> query($sql);
if($res->num_rows == 0) require 'forms/confirm.php';
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

                  <form class="row g-3 needs-validation" id="form-activate" novalidate>
                     <input type="hidden" name="id" value="<?= htmlentities($_GET['id']); ?>">
                     <input type="hidden" name="act" value="<?= htmlentities($_GET['activate']); ?>">
                     <div class="col-12">
                      <label for="psw" class="form-label">Password</label>
                      <input type="password" name="psw" class="form-control" id="psw" required autofocus>
                      <div class="invalid-feedback">Please enter your password!</div>
                    </div>
                     <div class="col-12">
                      <label for="psw2" class="form-label">Password repeat</label>
                      <input type="password" name="psw2" class="form-control" id="psw2" required>
                      <div class="invalid-feedback">Please confirm your password!</div>
                    </div>
                    <div class="col-12">
                      <button class="btn btn-primary w-100" type="submit">Submit</button>
                    </div>
                    <div class="col-md-12 text-center">
                      <div class="loading">Loading</div>
                      <div class="error-message btn btn-danger"></div>
                      <div class="sent-message btn btn-success"><?=$suc;?><br> <a href="/">Log in</a> now</div>
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
