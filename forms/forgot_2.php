<?php
  
  require "structs/head.php";
  if ($_POST['user'] != "" ){
    /*
    $sql="select * from $t4
          where (username = '".addslashes($_POST['user'])."' or 
          email = '".addslashes($_POST['user'])."')
          and password <> '' and is_active = '1'
          and password = MD5('".addslashes($_POST['psw'])."')";  
    $res = $con -> query($sql);
    if($res->num_rows > 0){
      $row = $res -> fetch_assoc();
      $_SESSION['user_id'] = $row["id"];
      $_SESSION['user_name'] = htmlspecialchars($row["first_name"]);
      $con->query("update $t4 set last_login = now() where id=".$row["user_id"]);
    }else $error = ' show';
  }//else if ($_POST['user'] != "" || $_POST['psw'] !="") $error = ' show';
    */
?>
<body>
  <main>
    <div class="container">
    <?php
      if ($_POST['user'] != "" ){

        ?>
      <section class="section error-404 min-vh-100 d-flex flex-column align-items-center justify-content-center">
        <!--h1>Confirmation</h1-->
        <h2>Your password will be reset.
          The confirmation code has been sent to your email.
          Please enter the code from your email below.</h2>
        <a class="btn" href="?">Log in</a>

        <?php
      }else{
    ?>
 <section class="section forgot min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
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
                    <h5 class="card-title text-center pb-0 fs-4">Password reset</h5>
                    <p class="text-center small">Enter your username</p>
                  </div>
                  <form class="row g-3 needs-validation" action="?forgot" method="post" novalidate>
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
                      <button class="btn btn-primary w-100" type="submit">Request</button>
                    </div>
                    <div class="col-12">
                      <p class="small mb-0">Question? <a href="?contact">Contact us</a></p>
                    </div>
                  </form>
              </div><!-- End Card -->
            </div>
          </div>
          </div><!-- End Row -->
        </div>
     
    <?php
    }
    ?>
     </section>
    </div>
  </main><!-- End #main -->
<?php
require "structs/footer.php";
exit();

?>