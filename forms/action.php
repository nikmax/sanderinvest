<?php
if(!isset($con)) exit("falsch verbunden");

/* Create new account */
if(isset($_POST['create'])){
    if ($_POST['email'] != $_POST['coemail']) exit ('Emails are different.');
    if (empty($_POST['first_name'])) exit ('First name ist empty.');
    if (empty($_POST['last_name'])) exit ('Last name ist empty.');
    if (empty($_POST['email'])) exit ('Email is empty');
    $email=addslashes($_POST['email']);
    $first = addslashes($_POST['first_name']);
    $last = addslashes($_POST['last_name']);
    $sql="select * from $t4 where email = '$email'";
    $res = $con -> query($sql);
    if($res->num_rows > 0) exit ('Email allready exists.');
    $user=strtolower(substr($first,0,2).substr($last,0,2));
    //set @u:=lower(concat(substring('anton',1,2),substring('Sander',1,2)));
    $sql="select concat('$user',lpad(ifnull(max(REGEXP_replace(username,'[a-z]','')),0)+1,3,'0'))".
    "from $t4 where username like concat('$user','%')";
    $res = $con -> query($sql);
    $err='Unknown error! Please contact our <a href="?contact&subject=create%20account%20error1&mail='.$email.'">service team</a>.';
    if($res->num_rows > 0) {$row = $res -> fetch_array();$user=$row[0];}
    else exit($err);
    $sql="select * from $t4 where username = '$user'";
    $res = $con -> query($sql);
    $err='Unknown error! Please contact our <a href="?contact&subject=create%20account%20error2&mail='.$email.'">service team</a>.';
    if($res->num_rows > 0) exit ($err);
    $sql="select id from $t4 where username = '".addslashes($_POST['ref'])."'";
    $res = $con -> query($sql);
    if($res->num_rows > 0) {$row = $res -> fetch_array();$ref=$row[0];}
    else $ref=1;
    $psw = md5(random_int(1, 2560));

    $to = $email;
    $subject = "Confirm your new account";
    $headers = "From: SanderInvest <$haupt_email>\n";
    $headers .= "Reply-To: ";
    $message = "
Welcome to SanderInvest!

Click the following link to confirm and activate your new account:
https://vsan:5300?activate-account/$psw

If the above link is not clickable, try copying and pasting it into the address bar of your web browser.

Withouth activate process your credentials will delete after 24 hours.

Your SanderInvest-Team
";
    $err='Unknown error! Please contact our <a href="?contact&subject=create%20account%20error3&mail='.$email.'">service team</a>.';
    if (mail($to,$subject,$message,$headers)) $err = "OK";

    $sql="insert into $t4 (password,username,first_name,last_name,email,date_joined,ref_id) ";
    $sql.="values ('$psw','$user','$first','$last','$email',now(),$ref)";
    $res = $con -> query($sql);
    $err='Unknown error! Please contact our <a href="?contact&subject=create%20account%20error4&mail='.$email.'">service team</a>.';
    if($con -> error) exit ($err);
    exit('OK');}


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