<?php
//if(!isset($page)) exit();
if(isset($_POST['message'])){
  $subject = addslashes($_POST['subject']);
  $message = addslashes($_POST['message']);
  $sql="select * from $t4 where id = ".$_SESSION['user_id'];  
  $res = $con -> query($sql);
  if($res->num_rows > 0){
    $row = $res -> fetch_assoc();
    // Create the email and send the message
    $to = $haupt_email; // Add your email address inbetween the '' replacing yourname@yourdomain.com - This is where the form will send a message to.
    $subject = "Contact vom ${row['username']} :  $subject";
    $headers = "From: <$to>\n"; // This is the email address the generated message will be from. We recommend using something like noreply@yourdomain.com.
    $headers .= "Reply-To: "; 
    $result= "PHP mail Fehler";
    if (mail($to,$subject,$message,$headers)) $result = "OK";
    exit($result);
  }else exit ("Please log in first.");
}
if(!isset($page)) exit();
?>
<div class="pagetitle">
    <h1>Contact</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="?test">Home</a></li>
        <li class="breadcrumb-item">Pages</li>
        <li class="breadcrumb-item active">Contact</li>
      </ol>
    </nav>
</div>

<section class="section contact">
    <div class="row gy-4">
      <div class="col-xl-6">
        <div class="row">
          <div class="col-lg-6">
            <div class="info-box card"> <i class="bi bi-geo-alt"></i><h3>Address</h3><p>Schillerstr. 4<br>66903 Altenkirchen</p>
            </div>
          </div>
          <!--div class="col-lg-6">
            <div class="info-box card"> <i class="bi bi-telephone"></i><h3>Call Us</h3><p>+49 176 38107533</p>
            </div>
          </div-->
          <div class="col-lg-6"><div class="info-box card"> <i class="bi bi-envelope"></i><h3>Email Us</h3><p>info@sanderinvest.com<br>+49 176 38107533</p></div></div>
          <!--div class="col-lg-6"><div class="info-box card"> <i class="bi bi-clock"></i><h3>Open Hours</h3><p>Monday - Friday<br>9:00AM - 05:00PM</p></div></div-->
        </div>
      </div>
      <div class="col-xl-6">
        <div class="card p-4">
          <form action="?contact" method="post" class="php-email-form">
            <div class="row gy-4">
              <div class="col-md-6">Your Message:</div>
              <div class="col-md-12"> <input type="text" class="form-control" name="subject" placeholder="Subject" required=""></div>
              <div class="col-md-12"><textarea class="form-control" name="message" rows="6" placeholder="Message" required=""></textarea></div>
              <div class="col-md-12 text-center">
                <div class="loading">Loading</div>
                <div class="error-message"></div>
                <div class="sent-message">Your message has been sent. Thank you!</div> 
                <button type="submit">Send Message</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
</section>