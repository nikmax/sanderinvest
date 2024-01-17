<?php if(!isset($con)) exit("falsch verbunden");

$foc1=' autofocus';

if (!(empty($_POST['user']) || empty($_POST['psw'])) ){
	$sql="
		select 
		    if(password=md5('".addslashes($_POST['psw'])."'),'OK','NO'),
		    id, username, first_name,
		    is_active, DATEDIFF(now(),date_joined)
		from $t4 where email = '".addslashes($_POST['user'])."'";
    $res = $con -> query($sql);
    $con-> error && $message = 'Sorry! Our Database is corrupt.' && require 'error-404.php';
    if($res->num_rows > 0){ 
			$row = $res -> fetch_array();
			if($row[0] == 'OK'){
				empty($_POST['remember']) || setcookie(session_name(),$_COOKIE[session_name()],time()+3600*24*31*12,'/');
				$_SESSION['user_id'] = $row[1];
				$_SESSION['user'] = $row[2];
				$_SESSION['user_name'] = htmlspecialchars($row[3]);
				$con -> query("update $t4 set last_login = now(), recover = null where id=".$row[1]);
				header("Location: /");exit();}
			if($row[4] == 0 && $row[5] < 2) header('Location: /?confirm=activate');}
	$msg='Email-Address or Password incorrect!  <a href="?forgot">Forgot password?</a>';
	$show=' show'; $foc1=''; $foc2=' autofocus';}
	require 'head.php';
?>
<main>
    <div class="container">

      <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
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
                    <h5 class="card-title text-center pb-0 fs-4">Login to Your Account</h5>
                    <p class="text-center small">Enter your Email & password to login</p>
                  </div>

                  <form method="POST" action="?login" class="row g-3 needs-validation" novalidate>

                    <div class="col-12">
                      <label for="user" class="form-label">Email</label>
                      <div class="input-group has-validation">
                        <span class="input-group-text" id="inputGroupPrepend">@</span>
                        <input type="text" name="user" class="form-control" id="user" value="<?=htmlentities($_POST['user']);?>" required<?=$foc1;?>>
                        <div class="invalid-feedback">Please enter your email.</div>
                      </div>
                    </div>

                    <div class="col-12">
                      <label for="psw" class="form-label">Password</label>
                      <input type="password" name="psw" class="form-control" id="psw" required<?=$foc2;?>>
                      <div class="invalid-feedback">Please enter your password!</div>
                    </div>

                    <div class="col-12">
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" value="true" id="remember"<?php if(isset($_POST['remember'])) echo ' checked = "checked"';?>>
                        <label class="form-check-label" for="remember">Remember me</label>
                      </div>
                    </div>
                    <div class="col-12">
                      <button class="btn btn-primary w-100" type="submit">Login</button>
                    </div>
                    <div class="col-12">
                      <p class="small mb-0">Don't have account? <a href="?register">Create an account</a></p>
                    </div>
                    <div class="alert alert-danger bg-danger text-light border-0 
                        alert-dismissible fade<?=$show;?>" role="alert"><?=$msg;?>
                    <button type="button" class="btn-close btn-close-white" 
                        data-bs-dismiss="alert" aria-label="Close"></button>
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
