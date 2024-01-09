<?php
require "structs/head.php";
?>
<body>
  <main>
      <section class="section error-404 min-vh-100 d-flex flex-column align-items-center justify-content-center">
        <!--h1>Confirmation</h1-->
        <h2>Registration email sent. Open the email to finish create account.
If you don’t see this email in your inbox within 15 minutes, look for it in your junk mail folder. If you find it there, please mark the email as “Not Junk”.</h2>
        <a class="btn" href="?">Log in</a>
        <!--img src="assets/img/not-found.svg" class="img-fluid py-5" alt="Page Not Found"-->
        
      </section>
  </main><!-- End #main -->
<?php
require "structs/footer.php";
exit();
?>