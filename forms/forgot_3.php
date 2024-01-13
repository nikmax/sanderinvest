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
                             autofocus  autocomplete="off">
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
     </section>
    </div>
  </main><!-- End #main -->
