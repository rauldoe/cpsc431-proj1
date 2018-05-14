<?php
	require_once("functions/setup_DB.php");
	require_once("functions/config.php");

	//Bring in PHPMailer for send_mail()
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;
	require_once('../PHPMailer/Exception.php');
	require_once('../PHPMailer/PHPMailer.php');
	require_once('../PHPMailer/SMTP.php');

	//inserts a registration link to the database
	//used by admin to let people register by sending a link to their email that leads to the registration page
	//returns generated link
	function insert_registration_link($email, $type, $league_ID=null)
	{
		//setup variables
		$links_table = LINKS_TABLE;
		$user_table = USER_TABLE;
		$db = db_connect();

    	//hash the email and then get curr time to make it unique
    	$now = date("Ymdhis");
    	$link = $email.$now;
    	$link_md5ed = md5($link);

    	//if their email is currently in use, or they have already requested a registration email. Don't create a registration link (makes everything a lot less complicated)
		// check email is unique
		$same_email = $db->query("select ID from $user_table where email='".$email."'");
		if ($same_email->num_rows > 0)
		{
			return "Email is taken";
		}
		$same_email_in_links = $db->query("select ID from $links_table where Email='".$email."'");
		if ($same_email_in_links->num_rows > 0)
		{
			return "Link was already made for this email";
		}

    	//if inviting a coach:
    	if ($type == 2)
    	{
    		$query = "INSERT INTO $links_table 
						(Link, User_type, Email, League)
						VALUES
						('$link_md5ed', $type, '$email', $league_ID)";
    	}
    	//if inviting a league owner
    	else if ($type == 1)
    	{
    		$query = "INSERT INTO $links_table 
						(Link, User_type, Email)
						VALUES
						('$link_md5ed', $type, '$email')";
    	}

		//insert the link
		if (!$result = $db->query($query))
		{
			return "query failed";
		}

		//query was successful
		$full_link = BASE_URL."/registration.php?link=$link_md5ed";

		//setup email variables
		if ($type == 1)
			{$type_stringed = "League owner";}
		else if ($type == 2)
			{$type_stringed = "Coach";}
		$to = $email;
		$subject = "Registration Link: $type_stringed account";
		$msg = "Click this link to register: <a href=\"$full_link\">$full_link</a>";

		//send email
		send_email($to, $subject, $msg);
		
		$db->close();
		return "Generated Link, emailed this to $email: <a href=\"$full_link\">$full_link</a>";
	}

	//check if registration link is valid
	function check_link($link)
	{
		//setup variables
		$links_table = LINKS_TABLE;
		$db = db_connect();

		//find the link
		$query = "SELECT * 
					FROM $links_table 
					WHERE Link = '$link'";

		if (!$result = $db->query($query))
		{
			return null;
		}

		if ($result->num_rows == 0)
		{
			return null;
		}

		//found it! valid link
		//return data for registration
		$link_data = $result->fetch_assoc();
		$data['email'] = $link_data['Email'];
		$data['user_type'] = $link_data['User_type'];
		$data['league'] = $link_data['League'];
		$data['league_name'] = $link_data['League_name'];

		$db->close();
		return $data;
	}

	//deletes all the links with the Email 'email'
	//returns true if successful and false if not
	function delete_link($email)
	{
		//setup variables
		$links_table = LINKS_TABLE;
		$db = db_connect();

		$query = "DELETE FROM $links_table WHERE Email = '$email'";

		if (!$result = $db->query($query))
		{
			return false;
		}

		//successful deletion
		return true;
	}

	//helper functions

	//sends email
	function send_email($to_email, $subject, $msg)
	{

	    /*email we're using: tesla.sports.manager@gmail.com, password: teslasportsmanager
		  mail() wouldnt work so just using PHPmailer (https://github.com/PHPMailer/PHPMailer)
		  from https://github.com/PHPMailer/PHPMailer */
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
		    $mail->Body    = $msg;
		    $mail->AltBody = $msg; 							  //for non-HTML mail clients

		    //send it
		    $mail->send();
		    return true;
		} catch (Exception $e) {
		    echo 'Email could not be sent. Mailer Error: ', $mail->ErrorInfo;
		}
	}

?>