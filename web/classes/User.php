<?php
	require_once("functions/user_fns.php");

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
?>