<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'vendor/autoload.php';
require_once __DIR__ . '/config.php';

//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);

try {
	//Server settings
	$mail->SMTPDebug = 2;
	$mail->isSMTP();
	$mail->Host       = MAIL_HOST;
	$mail->SMTPAuth   = true;
	$mail->Username   = MAIL_USERNAME;
	$mail->Password   = MAIL_PASSWORD;
	$mail->SMTPSecure = MAIL_SECURITY;
	$mail->Port       = MAIL_PORT;
	//$mail->SMTPAutoTLS = false;

	//Recipients
	$mail->setFrom(MAIL_FROM, MAIL_FROMNAME);
	$mail->addAddress(MAIL_TO);     //Add a recipient
	$mail->addReplyTo(MAIL_REPLYTO, MAIL_FROMNAME);

	//Attachments
	//$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
	//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

	//Content
	$mail->isHTML(true);                                  //Set email format to HTML
	$mail->Subject = 'Testmail - Here is the subject';
	$mail->Body    = 'This is the HTML message body <b>in bold!</b>';
	$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

	$mail->send();
	echo 'Message has been sent';
} catch (Exception $e) {
	echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
