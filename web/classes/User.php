<?php
	require_once("functions/setup_DB.php");
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

		//if successful log them in, else return error message
		if ($db->query($query)) 
		{
			$db->close();
			login_user($username, $password);
		}
		else
		{
			$db->close();
			return "Query to insert user failed - try again later";
		}
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