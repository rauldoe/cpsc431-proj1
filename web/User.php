<?php
	require_once('setup_DB.php');

	class User 
	{
		private $username;
		private $type;
		private $password;

		function __construct($username, $type, $password=NULL)
		{
	  		$this->username = $username;
	  		$this->type = $type;
	  		$this->password = $password;
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


		//-----Database Querying-----

		//TODO: hash the password
		//add new user to database
		function add_to_DB()
		{
			//setup variables
			$user_table = USER_TABLE;
			$db = db_connect();

			// check if username is unique
  			$same_names = $db->query("select * from user where username='".$this->username."'");
  			if ($same_names->num_rows > 0) 
  			{
  				return "Username is taken";
  			}

  			//HASH IT

			//IF successful
			$query = "INSERT INTO $user_table (Username, Password, User_type) 
						VALUES ('$this->username', '$this->password', '$this->type')";

			if (!$db->query($query)) 
			{
				return "Query to insert user failed - try again later";
			}
			else
			{
				return "Successful";
			}

			$db->close();
		}
	}
?>