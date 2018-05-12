<?php
	require_once("functions/setup_DB.php");
	require_once("functions/link_inviting.php");
	require_once("classes/Team.php");
	require_once("classes/League.php");
	require_once("classes/User.php");
	session_start();

	//-----Database Querying-----

	//get user data based on ID
	function get_userinfo($user_id)
	{
		//setup variables
		$user_table = USER_TABLE;
		$db = db_connect();

		// find
		$query = "SELECT Email, Username, User_type, League FROM $user_table WHERE ID = $user_id";

		if (!$result = $db->query($query))
		{
			return "Query to insert user failed - try again later";
		}

		if ($result->num_rows == 0)
		{
			return "no user with this ID";
		}

		$user_info = $result->fetch_assoc();
		$db->close();
		return $user_info;
	}

	//add new user to database
	function register_user($email, $username, $password, $type, $league)
	{
		//setup variables
		$user_table = USER_TABLE;
		$db = db_connect();

		// check if username
		$same_names = $db->query("select ID from user where username='".$username."'");
		if ($same_names->num_rows > 0) 
		{
			return "Username is taken";
		}

		// check email is unique
		$same_email = $db->query("select ID from user where email='".$email."'");
		if ($same_email->num_rows > 0)
		{
			return "Email is taken";
		}

		//hash password and insert to database
		$hashed = password_hash($password, PASSWORD_DEFAULT);
		$query = "INSERT INTO $user_table (Email, Username, Password, User_type, League) 
					VALUES ('$email', '$username', '$hashed', '$type', '$league')";

		if (!$result = $db->query($query))
		{
			$db->close();
			return "Query to insert user failed - try again later";
		}

		//successful

		//delete the registration link(s) from DB with this email
		if (!delete_link($email))
		{
			$db->close();
			return "could not delete registration link, try again later";
		}

		//save the id inserted
		$id_of_inserted_user = $db->insert_id;

		//if successful return the id of the user created
		$db->close();
		return $id_of_inserted_user;
	}

	//log in the user using session data
	function login_user($username, $password)
	{
		//setup variables
		$user_table = USER_TABLE;
		$db = db_connect();

		//check username existance
		$same_names = $db->query("select * from user where username='".$username."'");
		if ($same_names->num_rows == 0)
		{
			return "username not found";
		}
		$result = $same_names->fetch_assoc();

		//check password
		$correct_password = password_verify($password, $result['Password']); 
		if (!$correct_password)
		{
			return "password incorrect";
		}
		$db->close();

		//if reached this point, successful login
		//update session data and refresh/redirect
		$_SESSION['user']['ID'] = $result['ID'];
		$_SESSION['user']['username'] = $result['Username'];
		$_SESSION['user']['type'] = $result['User_type'];
		$_SESSION['user']['league'] = $result['League'];
		header ("Location: dashboard.php");
		exit;
	}



	//logs out the user, reset session data
	function logout_user()
	{
		$_SESSION['user'] = null;

		//refresh/redirect
		header ("Location: login.php");
		exit;
	}

	//returns true if logged in, false if not
	function is_logged_in()
	{
		//check session data
		if (isset($_SESSION['user']))
		{
			$db = db_connect();
			$id = $_SESSION['user']['ID'];

			//update the data
			$same_names = $db->query("select * from user where ID = $id");
			if ($same_names->num_rows == 0)
			{
				return "user not found";
			}
			$result = $same_names->fetch_assoc();
			$db->close();

			//update session data
			$_SESSION['user']['ID'] = $result['ID'];
			$_SESSION['user']['username'] = $result['Username'];
			$_SESSION['user']['type'] = $result['User_type'];
			$_SESSION['user']['league'] = $result['League'];

			return true;
		}
		return false;
	}

	//return null if no user logged in, return the user if they are
	function get_user()
	{
		if (is_logged_in())
		{
			$logged_in_user = new User($_SESSION['user']['username'], $_SESSION['user']['type']);

			return $logged_in_user;
		}
		return null;
	}

	//get all the teamless coaches in a given league
	function get_teamless_coaches($league_ID)
	{
		//setup variables
		$user_table = USER_TABLE;
		$team_table = TEAM_TABLE;
		$db = db_connect();

		//Find the users who are coaches and don't have a team
		$query = "SELECT Users.ID, Users.Username, Users.Email 
 		  		  FROM 
 					User AS Users 
 				  LEFT JOIN 
 					Sports_team AS Team 
		 		  ON Users.ID = Team.Coach 
					WHERE Users.User_type = 2 
			 		AND Team.Coach IS NULL 
			 		AND Users.League = $league_ID";
		if (!$result = $db->query($query))
		{
			return "Teamless Coaches Query Failed";
		}

		if ($result->num_rows == 0)
		{
			return null;
		}

		//successful
		$teamless_coaches = array();
		while ($row = $result->fetch_assoc())
		{
			array_push($teamless_coaches, $row);
		}


		return $teamless_coaches;
		$db->close();
	}

	//change the password of the user with this user id
	function change_pass($user_id, $new_password)
	{
		//setup variables
		$user_table = USER_TABLE;
		$db = db_connect();

		//hash it
		$hashed_pass = password_hash($new_password, PASSWORD_DEFAULT);
		
		//"find user and update their password" query
		$query = "UPDATE $user_table
					SET Password = '$hashed_pass'
					WHERE ID = $user_id";

		//find user and update their password
		if (!$db->query($query)) 
		{
			return "Query to change password failed - try again later";
		}

		//successful
		$db->close();
		return true;
	}

	//change the password of the user with this user email
	function change_pass_by_email($user_email, $new_password)
	{
		//setup variables
		$user_table = USER_TABLE;
		$db = db_connect();

		//hash it
		$hashed_pass = password_hash($new_password, PASSWORD_DEFAULT);
		
		//"find user and update their password" query
		$query = "UPDATE $user_table
					SET Password = '$hashed_pass'
					WHERE Email = '$user_email'";

		$query_result = $db->query($query);

		if ($db->affected_rows == 0)
		{
			echo "Email not found";
			return false;
		}

		//find user and update their password
		if (!$query_result) 
		{
			var_dump("Query to change password failed - try again later");
			return false;
		}

		//successful
		$db->close();
		return true;
	}

	//generate unique password for forgot password
	function generate_random_pass()
	{
		$result_password = "";
		$password_length = 10;
		$alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';

		for ($i = 0; $i < $password_length; $i++)
		{
			$random_index = rand(0, strlen($alphabet) - 1);
			$result_password .= $alphabet[$random_index];
		}
	
		return $result_password;
	}
?>