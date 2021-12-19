<?php
require 'PHPMailerAutoload.php';

$mail = new PHPMailer;

$to = "kalxcharles@gmail.com";
$name = "creditors";
$subject = "Creditplus Account Activation";
$body = "8828282882822";


$mail = new PHPMailer();
$mail->SMTPDebug = 1;
$mail->IsSMTP();
$mail->Host = "mailhost05.i3c.co.ug";
$mail->Port = "587";
//usually the port for TLS is 587, for SSL is 465 and non-secure is 25
$mail->SMTPSecure = "tls";
//TLS, SSL or  delete the line
$mail->SMTPAuth = true;
$mail->Username = 'no-reply@creditplus.ug';
$mail->Password = 'Cred123@';
$mail->From = 'no-reply@creditplus.ug';
$mail->FromName = 'Creditplus';
$mail->AddAddress($to, $name);
$mail->Subject = $subject;

$row = $row['id'];

// $mail->Body = $body;

$msg = "<html>
<head>
      <title>Invitation Email</title>
      <style>
            body {
                  background-color: #999;
            }
            
            #content {
                  width: 800px;
                  height: 600px;
                  background: url('http://www.traypml.com/nahb/images/invite_bg.jpg');
                  border: 1px solid black;
                  margin: 0 auto;
            }
            #dateTime {
                  width: 800px;
                  text-align: center;
                  font: 35px Impact;
                  position: relative;
                  top: 130px;
                  line-height: 50px;
                  text-transform: uppercase;
            }
            #bullets {
                  position: relative;
                  left: 320px;
                  top: 180px;
                  width: 400px;
                  height: 90px;
                  text-align: center;
                  font: 18px Helvetica, Arial, sans-serif;
                  letter-spacing: 1px;
            }
            #iPhone {
                  color: white;
                  text-align: center;
                  position: relative;
                  left: 100px;
                  top: 85px;
                  width: 200px;
                  font: 20px Helvetica, Arial, sans-serif;
                  line-height: 35px;
            }
            #service {
                  font: 15px Helvetica, Arial, sans-serif;
                  font-style: italic;
                  position: relative;
                  left: 100px;
                  top: 115px;
            }
            #button {
                  font: bold 14px Helvetica, Arial, sans-serif;
                  position: relative;
                  left: 500px;
                  text-align: center;
                  width: 150px;
                  top: 30px;
            }
            #button a {
                  color: black;
                  text-decoration: none;
            }
            #button a:hover {
                  color: red;
            }
      </style>
</head>
<body style='background-color: #999;'>
      <div id='content'>
            &nbsp;
            <div id='dateTime'>
                  Tuesday March 18, 2008<br/>
                  10:00AM - 12:00PM<br/>
                  ABC Conference Room<br/>
            </div>
            <div id='bullets'>
                  &bull; Meet your Traypml team &nbsp; &bull; Food & Drinks<br/>
                  &bull; Prizes &nbsp; &bull; Learn about how we can help<br/>
                  you with your printing & mailing needs
            </div>
            <div id='iPhone'>
                  RSVP<br/>To Attend &<br/>Register to<br/>WIN A<br/>16GB iPhone*!!
            </div>
            <div id='service'>
                  * Service Plan Not Included<br/>You must be present to claim your prize
            </div>
            <div id='button'>
                  <a href='http://www.traypml.com/nahb/index.php?id=1' target='_blank'>Click Here to<br/>RSVP & Register</a>
            </div>
      </div>
</body>
</html>";

$mail->Body = $msg;
$mail->IsHTML(true);



if(!$mail->Send()) {
  echo 'Mailer error: '.$mail->ErrorInfo;
} else {
        echo("<p>Message successfully sent!</p>");
        echo "E-mail: ", $to, "<br />";
        echo "Name: ", $name, "<br />";
        echo "Subject: ", $subject, "<br />";
        echo "Body: ", $body, "<br />";
 
    }



  ?>