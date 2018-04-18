<?php
	require_once('setup_DB.php');
	session_start();

	//User class
	class User 
	{
		private $username;
		private $type;

		function __construct($username, $type)
		{
	  		$this->username = $username;
	  		$this->type = $type;
		}

		//-----getters / setters------

		//Getter/Setter for username
		function username()
		{
			// string username()
			if( func_num_args() == 0 )
			{
				return $this->username;
			}
			
			// void username($value)
			else if( func_num_args() == 1 )
			{
				$this->username = func_get_arg(0);
			}

			return $this;
		}

		function type()
		{
			// string type()
			if( func_num_args() == 0 )
			{
				return $this->type;
			}
			
			// void type($value)
			else if( func_num_args() == 1 )
			{
				$this->type = func_get_arg(0);
			}

			return $this;
		}
	}


	//-----Database Querying-----

	//TODO: redirecting to proper page after
	//add new user to database
	function register_user($username, $password, $type)
	{
		//setup variables
		$user_table = USER_TABLE;
		$db = db_connect();

		// check if username is unique
		$same_names = $db->query("select * from user where username='".$username."'");
		if ($same_names->num_rows > 0) 
		{
			return "Username is taken";
		}

		//hash password and insert to database
		$hashed = password_hash($password, PASSWORD_DEFAULT);
		$query = "INSERT INTO $user_table (Username, Password, User_type) 
					VALUES ('$username', '$hashed', '$type')";

		//return status message
		if (!$db->query($query)) 
		{
			$message = "Query to insert user failed - try again later";
		}
		else
		{
			$message = "Successful";
		}

		$db->close();
		return $message;
	}

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
		$_SESSION['user']['username'] = $result['Username'];
		$_SESSION['user']['type'] = $result['User_type'];
		header ("Location: login.php");
		exit;
	}

	function logout_user()
	{
		session_destroy(); //This is only effective after the next run
		$_SESSION = array(); //This fixes the problem uptop

		//refresh/redirect
		header ("Location: login.php");
		exit;
	}
?>