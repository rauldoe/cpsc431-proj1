<?php
	require_once("functions/league_fns.php");

	//League class
	class League 
	{
		private $name;
		private $owner;
		private $teams;

		function __construct($name, $owner, $teams = null)
		{
			$this->name = $name;
			$this->owner = $owner;
			$this->teams = $teams;
		}


		//-----getters / setters------

		//Getter/Setter for team name
		function name()
		{
			// string name()
			if( func_num_args() == 0 )
			{
				return $this->name;
			}
			
			// void name($value)
			else if( func_num_args() == 1 )
			{
				$this->name = func_get_arg(0);
			}

			return $this;
		}

		//Getter/Setter for owner
		function owner()
		{
			// string owner()
			if( func_num_args() == 0 )
			{
				return $this->owner;
			}
			
			// void owner($value)
			else if( func_num_args() == 1 )
			{
				$this->owner = func_get_arg(0);
			}

			return $this;
		} 

		//Getter/Setter for teams
		function teams()
		{
			// array of teams teams()
			if( func_num_args() == 0 )
			{
				return $this->teams;
			}
			
			// void teams(Team array = $teams)
			else if( func_num_args() == 1 )
			{
				$this->teams = func_get_arg(0);
			}

			return $this;
		}
	}

?>