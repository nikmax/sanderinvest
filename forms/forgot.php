<?php if(!isset($con)) exit("falsch verbunden");
$foc1=' autofocus';
if (!(empty($_POST['email']) || empty($_POST['email2'])) ){
	$show=' show'; $foc2=$foc1; $foc1='';
	if ($_POST['email'] != $_POST['email2']) $msg='Emails are different.';
	else{
		$sql="update $t4 set last_login=now(),recover=1 where email = '".addslashes($_POST['email'])."'";
		$res = $con -> query($sql);
		$con-> error && $message = 'Sorry! Our Database is corrupt.' && require 'error-404.php';
		if($con->affected_rows > 0){
			$sql="select concat(password,md5(password)), email from $t4 where email = '".addslashes($_POST['email'])."'";
			$res = $con -> query($sql);
			$con-> error && $message = 'Sorry! Our Database is corrupt.' && require 'error-404.php';
			$row = $res -> fetch_array();
			$psw = $row[0]; $email=$row[1];
			$subject = "Password recovery";
			$headers = "From: SanderInvest <$haupt_email>\n";
			$message = "
		Welcome to SanderInvest!
		
		Click the following link to recover your password:
		https://vsan:5300?activate=f&id=$psw

		If the above link is not clickable, try copying and pasting it into the address bar of your web browser.

		The link will be expiried after 2 hours.

		Your SanderInvest-Team";}
	}
	if(!empty($email)) mail($email,$subject,$message,$headers);
	//return ('Unknown error! Please contact our <a href="?contact&subject=create%20account%20error3&mail='.$email.'">service team</a>.');
	if(empty($msg)) header('Location: /?confirm=forgot');
	}

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
                    <h5 class="card-title text-center pb-0 fs-4">Recovery password</h5>
                    <p class="text-center small">Enter your email to recover password</p>
                  </div>

                  <form method="POST" action="?forgot" class="row g-3 needs-validation" id="form-register" novalidate>
                    <div class="col-12">
                      <label for="email" class="form-label">Your email</label>
                      <div class="input-group has-validation">
                        <span class="input-group-text" id="inputGroupPrepend">@</span>
                        <input type="email" name="email" class="form-control" id="email" value="<?=htmlentities($_POST['email']);?>" required<?=$foc1;?>>
                        <div class="invalid-feedback">Please enter a valid Email adddress!</div>
                      </div>
                    </div>

                    <div class="col-12">
                      <label for="email2" class="form-label">Repeate your email</label>
                      <div class="input-group has-validation">
                        <span class="input-group-text" id="inputGroupPrepend">@</span>
                        <input type="email" name="email2" class="form-control" id="email2" required<?=$foc2;?>>
                        <div class="invalid-feedback">Please enter a valid Email adddress!</div>
                      </div>
                    </div>

                    <div class="col-12">
                      <button class="btn btn-primary w-100" type="submit">Recover password</button>
                    </div>
                    <div class="col-12">
                      <p class="small mb-0">Remembered again? <a href="?">Log in</a></p>
                    </div>
					<div style="" class="alert alert-danger bg-danger text-light border-0 
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
