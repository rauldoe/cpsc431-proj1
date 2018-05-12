<?php
	require_once('classes/User.php');
	require_once('classes/League.php');

	//Bring in PHPMailer
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;
	require_once('../PHPMailer/Exception.php');
	require_once('../PHPMailer/PHPMailer.php');
	require_once('../PHPMailer/SMTP.php');


    /*email we're using: tesla.sports.manager@gmail.com, password: teslasportsmanager
	  mail() wouldnt work so just using PHPmailer (https://github.com/PHPMailer/PHPMailer)
	  from https://github.com/PHPMailer/PHPMailer */
	if (isset($_POST['send_pass_to_email']))
	{
		//generate random password and change this user's password to it
		$random_pass = generate_random_pass();
		$success = change_pass_by_email($_POST['email'], $random_pass);

		if (!$success)
		{
			echo $success;
		}
		else
		{
			//setup email variables
			$to_email = $_POST['email'];
			$subject = "Your new password!";
			$body = "Your password has been changed to: " . $random_pass;

			//send new generated password to email
			$mail = new PHPMailer(true);                              // Passing `true` enables exceptions
			try {

			    //Server settings
			    $mail->isSMTP();                                      // Set mailer to use SMTP
			    $mail->Host = 'ssl://smtp.gmail.com';  				  // Specify main and backup SMTP servers
			    $mail->SMTPAuth = true;                               // Enable SMTP authentication
			    $mail->Username = 'tesla.sports.manager@gmail.com';   // SMTP username
			    $mail->Password = 'teslasportsmanager';               // SMTP password
			    $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
			    $mail->Port = '465';                                  // TCP port to connect to

			    //Recipients
			    $mail->setFrom('tesla.sports.manager@gmail.com', 'Tesla Sports Manager');
			    $mail->addAddress($to_email);               		  // Send to $to_email
			    $mail->addReplyTo('tesla.sports.manager@gmail.com', 'Tesla Sports Manager');

			    //Content
			    $mail->isHTML(true);                                  // Set email format to HTML
			    $mail->Subject = $subject;
			    $mail->Body    = $body;
			    $mail->AltBody = $body; 							  //for non-HTML mail clients

			    //send it
			    $mail->send();
			    echo 'Password has been changed. Email with your new password has been sent';
			} catch (Exception $e) {
			    echo 'Email could not be sent. Mailer Error: ', $mail->ErrorInfo;
			}
		}
	}
?>

<!DOCTYPE html>
<html>
<head>
	<title>forgot password</title>
</head>
<body>
	<a href="login.php">Back to login page</a>
	<form method="post">
		Email: <input type="text" name="email" required>
		<button type="submit" name="send_pass_to_email">Send new password to email</button>
	</form>
</body>
</html>