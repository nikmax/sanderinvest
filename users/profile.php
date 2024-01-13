<?php
if(!isset($con)) exit("falsch verbunden");

$sql="select * from $t4 where id = ". $_SESSION['user_id'];
  $res = $con -> query($sql);
  if($res->num_rows == 0) require "users/error-404.php";
  else{
    $row = $res -> fetch_assoc();
?>

    <div class="pagetitle">
      <h1>Profile</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="?">Home</a></li>
          <li class="breadcrumb-item">Users</li>
          <li class="breadcrumb-item active">Profile</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->



    <section class="section profile">
      <div class="row">
        <div class="col-xl-2"><!-- Profilimage-->
          <div class="card">
            <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
              <img src="assets/user_icons/<?=htmlentities($row['username']);?>.jpg" alt="Profile" class="rounded-circle">
              <h2><?=htmlentities($row['first_name']).' '.htmlentities($row['last_name']);?></h2>
              <h3></h3>
              <div class="social-links mt-2">
                <a href="#" class="twitter"><i class="bi bi-twitter"></i></a>
                <a href="#" class="facebook"><i class="bi bi-facebook"></i></a>
                <a href="#" class="instagram"><i class="bi bi-instagram"></i></a>
                <a href="#" class="linkedin"><i class="bi bi-linkedin"></i></a>
              </div>
            </div>
          </div>
        </div><!-- Profilimage-->

        <div class="col-xl-8">
          <div class="card">
            <div class="card-body pt-3"><!-- Bordered Tabs -->
              <ul class="nav nav-tabs nav-tabs-bordered">
                <li class="nav-item">
                  <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-overview">Overview</button>
                </li>

                <li class="nav-item">
                  <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-edit">Edit Profile</button>
                </li>

                <li class="nav-item">
                  <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-settings">Settings</button>
                </li>

                <li class="nav-item">
                  <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-change-password">Change Password</button>
                </li>
              </ul>

              <div class="tab-content pt-2">

                <div class="tab-pane fade show active profile" id="profile-overview">
                  <h5 class="card-title"> </h5>
                  <!--p class="small fst-italic">Sunt est soluta temporibus accusantium neque nam maiores cumque temporibus. Tempora libero non est unde veniam est qui dolor. Ut sunt iure rerum quae quisquam autem eveniet perspiciatis odit. Fuga sequi sed ea saepe at unde.</p>

                  <h5 class="card-title">Profile Details</h5-->

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label ">Full Name</div>
                    <div class="col-lg-9 col-md-8"><?=htmlentities($row['first_name']).' '.
                    htmlentities($row['last_name']);?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Username</div>
                    <div class="col-lg-9 col-md-8"><?=htmlentities($row['username']);?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Date joined</div>
                    <div class="col-lg-9 col-md-8"><?=$row['date_joined'];?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Last login</div>
                    <div class="col-lg-9 col-md-8"><?=$row['last_login'];?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Email</div>
                    <div class="col-lg-9 col-md-8"><?=htmlentities($row['email']);?></div>
                  </div>
                </div><!--Overview-->

                <div class="contact tab-pane fade profile pt-3" id="profile-edit">
                  <form  action="?action" class="php-email-form">
                    <div class="row mb-3">
                      <label for="profileImage" class="col-md-4 col-lg-3 col-form-label">Profile Image</label>
                      <div class="col-md-8 col-lg-9">
                        <img src="assets/user_icons/<?=$_SESSION['user'];?>.jpg" alt="Profile">
                        <div class="pt-2">
                          <a href="#" class="btn btn-primary btn-sm" title="Upload new profile image"><i class="bi bi-upload"></i></a>
                          <a href="#" class="btn btn-danger btn-sm" title="Remove my profile image"><i class="bi bi-trash"></i></a>
                        </div>
                      </div>
                    </div>

                    <!--div class="row mb-3">
                      <label for="about" class="col-md-4 col-lg-3 col-form-label">About</label>
                      <div class="col-md-8 col-lg-9">
                        <textarea name="about" class="form-control" id="about" style="height: 100px">Sunt est soluta temporibus accusantium neque nam maiores cumque temporibus. Tempora libero non est unde veniam est qui dolor. Ut sunt iure rerum quae quisquam autem eveniet perspiciatis odit. Fuga sequi sed ea saepe at unde.</textarea>
                      </div>
                    </div-->

                    <div class="row mb-3">
                      <label for="first_name" class="col-md-4 col-lg-3 col-form-label">Firstname</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="first_name" type="text" class="form-control" id="first_name" 
                              value="<?=htmlentities($row['first_name']);?>">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="last_name" class="col-md-4 col-lg-3 col-form-label">Name</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="last_name" type="text" class="form-control" id="last_name" 
                              value="<?=htmlentities($row['last_name']);?>">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="email" class="col-md-4 col-lg-3 col-form-label">Email</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="email" type="email" class="form-control" id="email" 
                            value="<?=htmlentities($row['email']);?>">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="Twitter" class="col-md-4 col-lg-3 col-form-label">X Profile</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="twitter" type="text" class="form-control" id="X" value="https://twitter.com/#" disabled>
                      </div>
                    </div>
                    <div class="row mb-3">
                      <label for="Facebook" class="col-md-4 col-lg-3 col-form-label">Meta Profile</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="facebook" type="text" class="form-control" id="Meta" value="https://facebook.com/#" disabled>
                      </div>
                    </div>
                    <div class="row mb-3">
                      <label for="Instagram" class="col-md-4 col-lg-3 col-form-label">Instagram Profile</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="instagram" type="text" class="form-control" id="Instagram" value="https://instagram.com/#" disabled>
                      </div>
                    </div>
                    <div class="row mb-3">
                      <label for="Linkedin" class="col-md-4 col-lg-3 col-form-label">Linkedin Profile</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="linkedin" type="text" class="form-control" id="Linkedin" value="https://linkedin.com/#" disabled>
                      </div>
                    </div>

                    <div class="text-center">
                      <div class="loading">Loading</div>
                      <div class="error-message btn btn-danger"></div>
                      <div class="sent-message btn btn-success">Your profile has been changed!</div> 
                      <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>

                  </form>
                </div><!-- End Profile Edit Form -->

                <div class="contact tab-pane fade pt-3" id="profile-settings">
                  <!-- Settings Form -->
                  <form action="?action" class="php-email-form">
                    <input type="hidden" name="settings">
                    <div class="row mb-3">
                      <label for="fullName" class="col-md-4 col-lg-3 col-form-label">Email Notifications</label>
                      <div class="col-md-8 col-lg-9">
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" id="changesMade" name="changesMade" checked>
                          <label class="form-check-label" for="changesMade">
                            Changes made to your account
                          </label>
                        </div>
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" id="newProducts" name="newProducts" checked>
                          <label class="form-check-label" for="newProducts">
                            Information on new products and services
                          </label>
                        </div>
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" id="proOffers" name="proOffers">
                          <label class="form-check-label" for="proOffers">
                            Marketing and promo offers
                          </label>
                        </div>
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" id="securityNotify" name="securityNotify" checked disabled>
                          <label class="form-check-label" for="securityNotify">
                            Security alerts
                          </label>
                        </div>
                      </div>
                    </div>

                    <div class="text-center">                      
                      <div class="loading">Loading</div>
                      <div class="error-message btn btn-danger"></div>
                      <div class="sent-message btn btn-success">Your settings are changed!</div> 
                      <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>

                    
                  </form>
                </div><!-- End settings Form -->

                <div class="contact tab-pane fade pt-3 profile" id="profile-change-password">
                  <!-- Change Password Form -->
                  <form action="?action" class="php-email-form">

                    <div class="row mb-3">
                      <label for="password" class="col-md-4 col-lg-3 col-form-label">Current Password</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="password" type="password" class="form-control" id="password">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="newpassword" class="col-md-4 col-lg-3 col-form-label">New Password</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="newpassword" type="password" class="form-control" id="newpassword">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="renewpassword" class="col-md-4 col-lg-3 col-form-label">New Password</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="renewpassword" type="password" class="form-control" id="renewpassword">
                      </div>
                    </div>

                    <div class="text-center">
                      <div class="loading">Loading</div>
                      <div class="error-message btn btn-danger"></div>
                      <div class="sent-message btn btn-success">Your password has been changed!</div> 
                      <button type="submit" class="btn btn-primary">Change Password</button>
                    </div>

                  </form>
                </div><!-- End Change Password Form -->
              </div>

            </div><!-- End Bordered Tabs -->
          </div>

        </div>
      </div>
    </section>

<?php 
}
?>