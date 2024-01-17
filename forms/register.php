<?php if(!isset($con)) exit("falsch verbunden");

	$foc1=' autofocus';
	$show_s = '';
	$show_r='display: none;';
	$subject='';
	$message='';
	$headers='';

if (!(empty($_POST['email']) || empty($_POST['email2'])) ){
	$msg = register();
	if(empty($msg)) $show_s=' d-block';
	else $show_r='';}

function register (){

	global $con,$t4, $haupt_email,$email,$subject,$message,$headers;
	
    if ($_POST['email'] != $_POST['email2']) return ('Emails are different.');
    if (empty($_POST['first_name'])) return ('First name ist empty.');
    if (empty($_POST['last_name'])) return ('Last name ist empty.');
    if (empty($_POST['email'])) return ('Email is empty');

    $email=addslashes($_POST['email']);
    $first = addslashes($_POST['first_name']);
    $last = addslashes($_POST['last_name']);
    $sql="select * from $t4 where email = '$email'";
    $res = $con -> query($sql);
    if($res->num_rows > 0) return ('Email allready exists.');

    $user=strtolower(substr($first,0,2).substr($last,0,2));
    //set @u:=lower(concat(substring('anton',1,2),substring('Sander',1,2)));
    $sql="select concat('$user',lpad(ifnull(max(REGEXP_replace(username,'[a-z]','')),0)+1,3,'0'))".
    "from $t4 where username like concat('$user','%')";
    $res = $con -> query($sql);

    if($res->num_rows > 0) {$row = $res -> fetch_array();$user=$row[0];}
    else return('Unknown error! Please contact our <a href="?contact&subject=create%20account%20error1&mail='.$email.'">service team</a>.');
    $sql="select * from $t4 where username = '$user'";
    $res = $con -> query($sql);
    if($res->num_rows > 0) return ('Unknown error! Please contact our <a href="?contact&subject=create%20account%20error2&mail='.$email.'">service team</a>.');
    $sql="select id from $t4 where username = '".addslashes($_POST['ref'])."'";
    $res = $con -> query($sql);
    if($res->num_rows > 0) {$row = $res -> fetch_array();$ref=$row[0];}
    else $ref=1;

    $psw = md5(random_int(1, 2560)).md5(random_int(1, 2560));

    $sql="insert into $t4 (password,username,first_name,last_name,email,date_joined,ref_id) ";
    $sql.="values ('$psw','$user','$first','$last','$email',now(),$ref)";
    $res = $con -> query($sql);
    if($con -> error) return ('Unknown error! Please contact our <a href="?contact&subject=create%20account%20error4&mail='.$email.'">service team</a>.');


    $to = $email;
    $subject = "Confirm your new account";
    $headers = "From: SanderInvest <$haupt_email>\n";
    $message = "Welcome to SanderInvest!
		Click the following link to confirm and activate your new account:
		https://vsan:5300?activate=r&id=$psw

		If the above link is not clickable, try copying and pasting it into the address bar of your web browser.

		Withouth activate process your credentials will delete after 24 hours.

		Your SanderInvest-Team";
    
    return ('');}


if(!empty($show_s)) {
	mail($email,$subject,$message,$headers);
	//return ('Unknown error! Please contact our <a href="?contact&subject=create%20account%20error3&mail='.$email.'">service team</a>.');
	header('Location: /?confirm=register');
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
                    <h5 class="card-title text-center pb-0 fs-4">Create an Account</h5>
                    <p class="text-center small">Enter your personal details to create account</p>
                  </div>

                  <form method="POST" action="?register" class="row g-3 needs-validation" id="form-register" novalidate>
                    <div class="col-12">
                      <label for="first_name" class="form-label">Your first name</label>
                      <input type="text" name="first_name" class="form-control" id="first_name" value="<?=htmlentities($_POST['first_name']);?>" required<?=$foc1;?>>
                      <div class="invalid-feedback">Please, enter your first name!</div>
                    </div>

                    <div class="col-12">
                      <label for="last_name" class="form-label">Your last name</label>
                      <input type="text" name="last_name" class="form-control" id="last_name" value="<?=htmlentities($_POST['last_name']);?>" required>
                      <div class="invalid-feedback">Please, enter your last name!</div>
                    </div>

                    <div class="col-12">
                      <label for="email" class="form-label">Your email</label>
                      <div class="input-group has-validation">
                        <span class="input-group-text" id="inputGroupPrepend">@</span>
                        <input type="email" name="email" class="form-control" id="email" value="<?=htmlentities($_POST['email']);?>" required>
                        <div class="invalid-feedback">Please enter a valid Email adddress!</div>
                      </div>
                    </div>

                    <div class="col-12">
                      <label for="email2" class="form-label">Repeate your email</label>
                      <div class="input-group has-validation">
                        <span class="input-group-text" id="inputGroupPrepend">@</span>
                        <input type="email" name="email2" class="form-control" id="email2" required>
                        <div class="invalid-feedback">Please enter a valid Email adddress!</div>
                      </div>
                    </div>
					<div class="col-12">
                      <label for="ref" class="form-label">Referral</label>
                      <input type="ref" name="ref" class="form-control" id="ref" value="<?=htmlentities($_POST['ref']);?>" required>
                      <div class="invalid-feedback">Please enter your sponsor nickname or type "unknown"</div>
                    </div>
                    <div class="col-12">
                    <div class="col-12">
                      <div class="form-check">
                        <input class="form-check-input" name="terms" type="checkbox" value="" id="acceptTerms" required<?php if(isset($_POST['terms'])) echo ' checked="checked"';?>>
                        <label class="form-check-label" for="acceptTerms">I agree and accept the <a href="#">terms and conditions</a></label>
                        <div class="invalid-feedback">You must agree before submitting.</div>
                      </div>
                    </div>
                    <div class="col-12">
                      <button class="btn btn-primary w-100" type="submit">Create Account</button>
                    </div>
                    <div class="col-12">
                      <p class="small mb-0">Already have an account? <a href="?">Log in</a></p>
                    </div>
					<div style="<?=$show_r;?>" class="alert alert-danger bg-danger text-light border-0 
                        alert-dismissible fade show" role="alert"><?=$msg;?>
						<button type="button" class="btn-close btn-close-white" 
                        data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
					<div class="contact"><div class="loading<?=$show_s;?>">Loading</div></div>

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
