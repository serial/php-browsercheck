<?php
header("Content-Type: application/json");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'vendor/autoload.php';
require __DIR__ . '/config.php';

//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);


$array = $_POST['content'];

/*
 * Clean the array from line breaks and replace them with a colon
 */
$data = array();
foreach ($array as $key => $value) {
	//echo $key . '. => ' . $value;
	$value = str_replace("\n",": ", $value);
	$data[$key] = $value;
}


$message = '<b>Browser Check</b> <br><br>';

/*
 * Iterate over the array and append the values to the message
 */
foreach ($data as $key => $value) {
	$message .= ($key+1).' --- '.$value . "<br>";
}

$message .= "<br>";
$message .= "Timestamp: " . date('d.m.Y H:i:s', $_SERVER['REQUEST_TIME']);
$message .= "<br><br>";
$message .= 'User-Agent: '.$_SERVER['HTTP_USER_AGENT'];
$message .= "<br>";
$message .= 'IP: '.$_SERVER['REMOTE_ADDR'];
$message .= "<br>";
$message .= 'Host: '.$_SERVER['HTTP_HOST'];
$message .= "<br>";
$message .= 'Referer: '.$_SERVER['HTTP_REFERER'];



if(USE_PHPMAILER) {

	try {
    /*
     * For the working ajax request answer in json format on frontend, the debug needs to be set to 0 !!!
     * To enable verbose debug output, use:
     * 0 = off
     * 1 = client messages
     * 2 = client and server messages
     * 3 = client, server and connection messages
     */
		//$mail->SMTPDebug = SMTP::DEBUG_SERVER;
    $mail->SMTPDebug = 0;
		
    $mail->isSMTP();                                              //Send using SMTP
		$mail->Host       = MAIL_HOST;                                //Set the SMTP server to send through
		$mail->SMTPAuth   = true;                                     //Enable SMTP authentication
    $mail->Username   = MAIL_USERNAME;                            //SMTP username
		$mail->Password   = MAIL_PASSWORD;                            //SMTP password
		$mail->SMTPSecure = MAIL_SECURITY;                            //Enable implicit TLS encryption
		$mail->Port       = MAIL_PORT;                                //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
		$mail->SMTPAutoTLS = false;															      //needs to be enabled on localhost

		//Recipients
		$mail->setFrom(MAIL_FROM, MAIL_FROMNAME);
		$mail->addAddress(MAIL_TO);     //Add a recipient
		$mail->addReplyTo(MAIL_REPLYTO, MAIL_FROMNAME);
		//$mail->addCC('cc@example.com');
		//$mail->addBCC('bcc@example.com');

		//Attachments
		//$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
		//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

		//Content
		$mail->isHTML(true);                                  //Set email format to HTML
		$mail->Subject = 'Browser Check';
		$mail->Body    = $message;
		//$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

		//to simulate success mailsend, because localhost is not able to send mails
    //$phpmailer_success = true;
		
    $phpmailer_success = $mail->send();
    
		if($phpmailer_success) {
			echo json_encode(array(
				'message' => 'Hooray, thank you for submitting the information! <br /><br />' . $data[0] . ' <br />Please keep it for further reference.',
				'status' => 'success'
			));
		} else {
			echo json_encode(array(
				'message' => 'The Mail could not be sent! <br /><br />Please try again later or contact the administrator.',
				'status' => 'error'
			));
		}

	} catch (Exception $e) {
		echo json_encode(array('message' => 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo));
	}

} else {

	$to = MAIL_TO;
	$subject = 'Browser Check';

	$headers = 'From: ' . MAIL_FROM . "\r\n";
  $headers .=	'Reply-To: ' . MAIL_REPLYTO . "\r\n" ;

	$headers .=	'X-Mailer: PHP/' . phpversion();
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
 
	//to simulate success mailsend, because localhost is not able to send mails
	//$sendmail_success = true;
  
  $sendmail_success = mail($to, $subject, $message, $headers);
  
	if($sendmail_success) {
		echo json_encode(array('message' => 'Hooray, thank you for submitting the information!'));
	} else {
		echo json_encode(array('message' => 'The Mail could not be sent! Please try again later.'));
	}

}
