<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
/**
 * This example shows sending a message using a local sendmail binary.
 */
 
include('PHPMailerAutoload.php');


function sendMail($sendTo,$Subject,$Body){

//Create a new PHPMailer instance
$mail = new PHPMailer;
// Set PHPMailer to use the sendmail transport
$mail->isSendmail();
//Set who the message is to be sent from
$mail->setFrom('no-reply@creditplus.ug', 'customer service');
//Set an alternative reply-to address
$mail->addReplyTo('no-reply@creditplus.ug', 'hhsjsj team');
//Set who the message is to be sent to
// $mail->addAddress("kalxcharles@gmail.com", "charles");
$mail->addAddress($sendTo);
//Set the subject line
$mail->Subject = ($subject);
//Read an HTML message body from an external file, convert referenced images to embedded,
//convert HTML into a basic plain-text alternative body
//$mail->msgHTML(file_get_contents('contents.html'), dirname(__FILE__));
//Replace the plain text body with one created manually
$mail->Body     = $Body;
$mail->AltBody = ("hahahah");
//Attach an image file
//$mail->addAttachment('images/phpmailer_mini.png');

//send the message, check for errors
if (!$mail->send()) {
    echo "Mailer Error: " . $mail->ErrorInfo;
} else {
    echo "Message sent!";
}


}

// function sendMail($sendTo,$Subject,$Body){

//     // require_once 'PHPMailer/PHPMailerAutoload.php';

//     $mail = new PHPMailer;
//     $mail->isSMTP();                                      
//     $mail->Host = 'smtp.example.com;smtp.example.com';  
//     $mail->SMTPAuth = true;                               
//     $mail->Username = 'newsletter@example.com';           
//     $mail->Password = 'password';                         
//     $mail->SMTPSecure = 'ssl';                            
//     $mail->Port = 465;                                    
//     $mail->From = 'newsletter@example.com';
//     $mail->FromName = 'xyz';
//     $mail->WordWrap = 50;                                 
//     $mail->isHTML(true);                                  

//     $mail->addAddress($sendTo);               
//     $mail->Subject = $Subject;
//     $mail->Body = ( stripslashes( $Body ) );
//     $mail->AltBody = 'Please Use a Html email Client To view This Message!!';

//     if(!$mail->send()) {
//         $return = 'Message could not be sent.';
//         // echo 'Mailer Error: ' . $mail->ErrorInfo;
//     } else {
//         $return = 'Message has been sent!';
//     }
//     return $return;
// }

// // foreach ($emails as $email) {
 $subject = "sample subject";
 $body = "sample body";
 $email = "kalxcharles@gmail";
 sendMail($email, $subject, $body);
// // }



?>

