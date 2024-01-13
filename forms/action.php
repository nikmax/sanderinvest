<?php
if(!isset($con)) exit("falsch verbunden");

/* Create new account */
if(isset($_POST['create'])){
    //
    exit("OK");}

/* from contact form */
if(isset($_POST['message'])){
    $subject = addslashes($_POST['subject']);
    $message = addslashes($_POST['message']);
    if(isset($_SESSION['user_id'])){
        $user = $_SESSION['user'];
        $name = $_SESSION['user_name'];
        $sql="select email from $t4 where id = ".$_SESSION['user_id'];  
        $res = $con -> query($sql);
        //if($res->num_rows == 0) exit ("Please log in first.");
        $row = $res -> fetch_assoc();
        $email = $row['email'];}
    else {
        $email = addslashes($_POST['email']);
        $name = addslashes($_POST['name']);
        $user = "neuer Interessent";}
    // Create the email and send the message
    $to = $haupt_email; // Add your email address inbetween the '' replacing yourname@yourdomain.com - This is where the form will send a message to.
    $subject = "Anfrage von $name ($user) :  $subject";
    $headers = "From: $name <$email>\n"; // This is the email address the generated message will be from. We recommend using something like noreply@yourdomain.com.
    $headers .= "Reply-To: "; 
    $result= "PHP mail Fehler";
    if (mail($to,$subject,$message,$headers)) $result = "OK,OK";
    exit($result);}

/* Change Password Form */
if(isset($_POST['password'])){
    if ($_POST['password'] == "") exit("password is empty!");
    if($_POST['newpassword'] == "") exit('new password is empty!');
    if($_POST['newpassword'] != $_POST['renewpassword'])
        exit('wrong new password!');
    $sql="select id from $t4 where id = '". $_SESSION['user_id'] . "' and 
    password = MD5('".addslashes($_POST['password'])."')"; 
    $res = $con -> query($sql);
    if($res->num_rows == 0) exit('wrong password!');
    $row = $res -> fetch_array();
    $con->query("update $t4 set password = MD5('".addslashes($_POST['newpassword'])."') where id=".$row[0]);
    exit("OK,OK");}

/* Change Settings Form */
if(isset($_POST['settings'])){
    //
    exit("OK");}

/* Change Profile Form */
if(isset($_POST['first_name'])){
    if ($_POST['first_name'] == "") exit("first name is empty!");
    if ($_POST['last_name'] == "") exit("last name is empty!");
    if ($_POST['email'] == "") exit("email is empty!");
    $res = $con->query("update $t4 set first_name = '".addslashes($_POST['first_name'])."',
        last_name = '".addslashes($_POST['last_name'])."',
        email = '".addslashes($_POST['email'])."' 
        where id=".$_SESSION['user_id']);
    if($con->error) exit($con->error);
    else exit("OK");}

?>