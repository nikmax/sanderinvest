<?php

if(!isset($con)) exit("falsch verbunden");
  $error = "";

  if(isset($_POST['remember'])) {
    $remember = 'checked = "checked"';
    setcookie(session_name(),$_COOKIE[session_name()],time()+3600*24*31*12,'/');}
  else $remember = '';
  if ($_POST['user'] != "" && $_POST['psw'] !=""){
      $sql="select * from $t4
          where (username = '".addslashes($_POST['user'])."' or 
          email = '".addslashes($_POST['user'])."')
          and password <> '' and is_active = '1'
          and password = MD5('".addslashes($_POST['psw'])."')";  
      $res = $con -> query($sql);
      if($res->num_rows > 0){
      $row = $res -> fetch_assoc();
      $_SESSION['user_id'] = $row["id"];
      $_SESSION['user'] = $row["username"];
      $_SESSION['user_name'] = htmlspecialchars($row["first_name"]);
      $con->query("update $t4 set last_login = now() where id=".$row["id"]);
      }else $error = ' show';}

  if(!isset($_SESSION['user_id'])){
  require "structs/head.php";
?>
<body>

  <main>
    <div class="container">
      <section class="section login min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

              <div class="d-flex justify-content-center py-4">
                <a href="?" class="logo d-flex align-items-center w-auto">
                  <img src="assets/img/favicon.ico" alt="">
                  <span class="d-none d-lg-block"><?=$company;?></span>
                </a>
              </div><!-- End Logo -->

              <div class="card mb-3">
                <div class="card-body">

                  <div class="pt-4 pb-2">
                    <h5 class="card-title text-center pb-0 fs-4">Login to Your Account</h5>
                    <p class="text-center small">Enter your username & password to login</p>
                  </div>
                  <form class="row g-3 needs-validation" action="" method="post" novalidate>
                    <div class="col-12">
                      <label for="user" class="form-label">Username</label>
                      <div class="input-group has-validation">
                        <input type="text" name="user" class="form-control" id="user" required
                            <?php if (isset($_POST["user"])) 
                                     echo ' value="'.addslashes($_POST["user"]).'" ';
                                   else echo " autofocus "; 
                            ?> autocomplete="off">
                        <div class="invalid-feedback">Please enter your username.</div>
                      </div>
                    </div>
                    <div class="col-12">
                      <label for="psw" class="form-label">Password</label>
                      <input type="password" name="psw" class="form-control" id="psw" required
                          <?php if (isset($_POST["user"])) echo ' autofocus '; 
                          ?> autocomplete="off" onkeyup="document.getElementById('err').hidden='true';">
                      <div class="invalid-feedback">Please enter your password!</div>
                    </div>
                    <div class="col-12">
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" <?=$remember;?> id="remember">
                        <label class="form-check-label" for="remember">Remember me</label>
                      </div>
                    </div>
                    <div class="col-12">
                      <button class="btn btn-primary w-100" type="submit">Login</button>
                    </div>
                    <div class="col-12">
                      <p class="small mb-0">Don't have account? <a href="?create">Create an account</a></p>
                    </div>
                  </form>
                  <div id="err" class="alert alert-danger bg-danger text-light border-0 
                        alert-dismissible fade<?=$error;?>" role="alert">Username or Password incorrect!  <a href="?forgot">Forgot password?</a>
                    <!--button type="button" class="btn-close btn-close-white" 
                        data-bs-dismiss="alert" aria-label="Close"></button-->
                  </div>
              </div><!-- End Card -->
            </div>
          </div>
          </div><!-- End Row -->
        </div>
      </section>
    </div>
  </main><!-- End #main -->
<?php
require "structs/footer.php";
exit();
}
?>