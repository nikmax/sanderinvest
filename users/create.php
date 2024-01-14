<?php
require "structs/head.php";
?>
<body>
  <main>
    <div class="container">
      <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
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

                <div class="card-body contact">

                  <div class="pt-4 pb-2">
                    <h5 class="card-title text-center pb-0 fs-4">Create an Account</h5>
                    <p class="text-center small">Enter your personal details to create account</p>
                  </div>

                  <form method="post" action="?action" class="row g-3 needs-validation" id="php-register-form" novalidate>
                    <div class="col-12">
                      <label for="first_name" class="form-label">Your first Name</label>
                      <input type="text" name="first_name" class="form-control" id="first_name" required>
                      <div class="invalid-feedback">Please, enter your first name!</div>
                    </div>
                    <div class="col-12">
                      <label for="last_name" class="form-label">Your last Name</label>
                      <input type="text" name="last_name" class="form-control" id="last_name" required>
                      <div class="invalid-feedback">Please, enter your last name!</div>
                    </div>

                    <div class="col-12">
                      <label for="email" class="form-label">Your Email</label>
                      <input type="email" name="email" class="form-control" id="email" required>
                      <div class="invalid-feedback">Please enter a valid Email adddress!</div>
                    </div>
                    <div class="col-12">
                      <label for="coemail" class="form-label">Confirm Email</label>
                      <input type="email" name="coemail" class="form-control" id="coemail" required>
                      <div class="invalid-feedback">Please confirm your Email adddress!</div>
                    </div>
                    <div class="col-12">
                      <label for="ref" class="form-label">Referral</label>
                      <input type="ref" name="ref" class="form-control" id="ref" required>
                      <div class="invalid-feedback">Please enter your sponsor nickname or type "unknown"</div>
                    </div>
                    <div class="col-12">
                      <div class="form-check">
                        <input class="form-check-input" name="terms" type="checkbox" value="" id="terms" required>
                        <label class="form-check-label" for="terms">I agree and accept the <a href="#">terms and conditions</a></label>
                        <div class="invalid-feedback">You must agree before submitting.</div>
                      </div>
                    </div>
                    <div class="col-12">
                      <button class="btn btn-primary w-100" type="submit">Create Account</button>
                    </div>
                    <div class="col-12">
                      <p class="small mb-0">Already have an account? <a href="?">Log in</a></p>
                    </div>
                    <div class="col-md-12 text-center">
                      <div class="loading">Loading</div>
                      <div class="error-message btn btn-danger"></div>
                      <div class="sent-message btn btn-success">Your account is created! Please check your mailbox.</div>
                      </div>
                  </form>
                  

                </div><!-- End Card -->
              </div>

            </div>
          </div>
        </div>
      </section>
    </div>
  </main><!-- End #main -->
<?php
require "structs/footer.php";
exit();
?>