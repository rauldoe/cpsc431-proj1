<?php
	require_once("functions/setup_DB.php");
	require_once("functions/config.php");

	//inserts a registration link to the database
	//used by admin to let people register by sending a link to their email that leads to the registration page
	//returns generated link
	function insert_registration_link($email, $type, $league_ID=null)
	{
		//setup variables
		$links_table = LINKS_TABLE;
		$db = db_connect();

    	//hash the email and then get curr time to make it unique
    	$now = date("Ymdhis");
    	$link = $email.$now;
    	$link_md5ed = md5($link);

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

		//send email to user
		/* emailing is currently difficult, just gonna return the link back
		if ($type == 1)
			{$type_stringed = "League owner";}
		else if ($type == 2)
			{$type_stringed = "Coach";}

		$to = $email;
		$subject = "Registration Link: $type_stringed account";
		$full_link = BASE_URL."$link_md5ed";
		$msg = "Click this link to register: <a href=\"$full_link;\">$full_link</a>";
		$hi = send_email($to, $subject, $msg);
		*/

		$db->close();
		return "Generated Link, email this to $email: <a href=\"$full_link;\">$full_link</a>";
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
	function send_email($to, $subject, $msg)
	{
		if (mail($to, $subject, $msg)) {
			return true;
		} else {
			throw new Exception('Could not send email.');
		}
	}

?>