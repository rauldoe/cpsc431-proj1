<?php
	require_once("functions/league_fns.php");

	//League class
	class League 
	{
		private $id;
		private $ownerId;
		private $name;
		private $owner;
		private $teams;
		private $obj;

		function __construct($name, $owner, $obj, $teams = null)
		{
			$this->name = $name;
			$this->owner = $owner;
			$this->obj = $obj;
			$this->teams = $teams;
		}


		//-----getters / setters------

		//Getter/Setter for league name
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

		//Getter/Setter for teams
		function obj()
		{
			// array of teams teams()
			if( func_num_args() == 0 )
			{
				return $this->obj;
			}
			
			// void teams(Team array = $teams)
			else if( func_num_args() == 1 )
			{
				$this->obj = func_get_arg(0);
			}

			return $this;
		}

		//Methods
		public static function createFromDataSet($ds)
		{
			$instance = new self();
			$instance->id = $ds['ID'];
			$instance->ownerId = $ds['ManagerID'];
			$instance->name = $ds['LeagueName'];
			$instance->name = $ds['owner'];
			$instance->type = $_SESSION['user']['RoleID'];
			$instance->obj = $_SESSION['user']['obj'];

			return $instance;
		}
	}

?>