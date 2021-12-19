<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
/**
 * This example shows sending a message using a local sendmail binary.
 */
 
include('PHPMailerAutoload.php');


/**
* 
*/


	//Create a new PHPMailer instance
$mail = new PHPMailer;
// Set PHPMailer to use the sendmail transport
$mail->isSendmail();
//Set who the message is to be sent from
$mail->setFrom('no-reply@creditplus.ug', 'customer service');
//Set an alternative reply-to address
$mail->addReplyTo('no-reply@creditplus.ug', 'hhsjsj team');
//Set who the message is to be sent to
$mail->addAddress("kalxcharles@gmail.com", "charles");
//Set the subject line
$mail->Subject = ("hhshshsh");
//Read an HTML message body from an external file, convert referenced images to embedded,
//convert HTML into a basic plain-text alternative body
//$mail->msgHTML(file_get_contents('contents.html'), dirname(__FILE__));
//Replace the plain text body with one created manually
$mail->Body     = 'Hi! This is my first e-mail sent through PHPMailer.';
$mail->AltBody = ("hahahah");
//Attach an image file
//$mail->addAttachment('images/phpmailer_mini.png');

//send the message, check for errors
if (!$mail->send()) {	
    echo "Mailer Error: " . $mail->ErrorInfo;
} else {
    echo "Message sent!";
}


?>

