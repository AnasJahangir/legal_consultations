<?php
   
   
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class mymailer{
		
		public $mail = null;
		
		function __construct(){
				   require 'Mailer/src/Exception.php';
				   require 'Mailer/src/PHPMailer.php';
				   require 'Mailer/src/SMTP.php';
				   
				   $this->mail = new PHPMailer;
	    }



	function sendmail($name, $email, $subject, $message){
			
			$mssg = '<!doctype html><html> <head> <meta name="viewport" content="width=device-width, initial-scale=1"> <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> <title>'.$subject.'</title> </head> <body style="text-align:left; background-color:#FFF;">'.$message.'</body></html>';
			
			// die($mssg);
			
			//Create a new PHPMailer instance
			$mail = $this->mail;
			
			//Remove this block. It is unsecure to some extent. It was added because of encryption problem at my local host
			   $mail->SMTPOptions = array(
				'ssl' => array(
					'verify_peer' => false,
					'verify_peer_name' => false,
					'allow_self_signed' => true
				)
			);
			
			
				  $mail->isSMTP();
				   $mail->Host = smtp_host;
				   $mail->Port = smtp_port;
				   //$mail->SMTPDebug = 5;
				  
				   $mail->CharSet = 'utf-8';
				   $mail->SMTPAuth = true;
					  //Username to use for SMTP authentication
				   $mail->Username = smtp_username;
					//Password to use for SMTP authentication
				   $mail->Password = smtp_password;
					// Enable TLS encryption, `ssl` also accepted
				   $mail->SMTPSecure = smtp_secure;                             
			
			//Set who the message is to be sent from
			$mail->setFrom(smtp_username, smtp_sender_name);
			//Set an alternative reply-to address
			$mail->addReplyTo('noreply@'.glob_site_url_fd, 'NoReply');
			//Set who the message is to be sent to
			
			// Remove previous recipients
			$mail->ClearAllRecipients();
			
			$mail->addAddress($email, $name);
			//Set the subject line
			$mail->Subject = $subject;
			//convert HTML into a basic plain-text alternative body
			$mail->msgHTML($mssg);
			
			if (!@$mail->send()) {
				return "error"; 
			} else {
				return "good";
			}

			
	}
	
	
	


}