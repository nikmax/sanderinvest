<?php
  if ($_POST['first_name']){
  exit("no data");
}
require "structs/head.php";
?>
<body>
  <main>
    <div class="container"><!-- class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4 -->


<section class="section register">
    <div class="row gy-4">

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


    </div>
  </main><!-- End #main -->
<?php
require "structs/footer.php";
exit();
?>