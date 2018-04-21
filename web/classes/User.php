<?php
	require_once("functions/setup_DB.php");
	require_once("functions/link_inviting.php");
	require_once("classes/Team.php");
	require_once("classes/League.php");
	session_start();

	//User class
	class User 
	{
		private $username;
		private $type;
		private $team = null;
		private $league = null;

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

		//for user type
		function type()
		{
			// int type()
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

		//for team (will have to query database)
		function my_team()
		{
			//only a coach can have a personal team
			if($this->type != 2) 
			{
				return null;
			}

			// Team()
			if( func_num_args() == 0 )
			{
				return $this->team;
			}
			
			// void type(Team $team)
			else if( func_num_args() == 1 )
			{
				$this->team = func_get_arg(0);
			}

			return $this;	
		}

		//for league (will have to query database)
		function my_league()
		{
			//only a league owner can have a league
			if($this->type != 1) 
			{
				return null;
			}

			// league()
			if( func_num_args() == 0 )
			{
				return $this->league;
			}
			
			// void league(League $league)
			else if( func_num_args() == 1 )
			{
				$this->league = func_get_arg(0);
			}

			return $this;	
		}
	}


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

?>