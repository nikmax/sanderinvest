<?php
if(!isset($_SESSION['user_id'])){
    require "structs/head.php";
    echo '<body><main id="main" class="main">';
    $email = '<div class="col-md-12"> <input type="text" class="form-control" name="email" placeholder="Email" required=""></div><div class="col-md-12"> <input type="text" class="form-control" name="name" placeholder="Name" required=""></div>';}
else{
?>
<div class="pagetitle">
    <h1>Contact</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="?">Home</a></li>
        <li class="breadcrumb-item">Forms</li>
        <li class="breadcrumb-item active">Contact</li>
      </ol>
    </nav>
</div>
<?php } ?>

<section class="section contact">
    <div class="row">
       <div class="col-xl-2"></div>
      <div class="col-xl-8">
        <div class="card p-4">
          <form method="post" action="?action" class="php-email-form">
            <div class="row gy-4">
              <div class="col-md-6">Your Message:</div>
              <?=$email;?>
              <div class="col-md-12"> <input type="text" class="form-control" name="subject" placeholder="Subject" required=""></div>
              <div class="col-md-12"><textarea class="form-control" name="message" rows="6" placeholder="Message" required=""></textarea></div>
              <div class="col-md-12 text-center">
                <div class="loading">Loading</div>
                <div class="error-message btn btn-danger"></div>
                <div class="sent-message btn btn-success">Your message has been sent. Thank you!</div> 
                <button type="submit" class="btn btn-primary">Send Message</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
</section>
<?php
if(!isset($_SESSION['user_id'])){
    echo '</main><!-- End #main -->';
    require "structs/footer.php";
    exit;}

?>
