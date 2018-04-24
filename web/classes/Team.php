<?php
	require_once("functions/team_fns.php");

	//Team class
	class Team 
	{
		private $name;
		private $coach;
		private $league;
		private $players;
		
		function __construct($name, $coach, $league, $players = null)
		{
			$this->name = $name;
			$this->coach = $coach;
			$this->league = $league;
			$this->players = $players;
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

		//for coach
		function coach()
		{
			// string coach()
			if( func_num_args() == 0 )
			{
				return $this->coach;
			}
			
			// void coach($value)
			else if( func_num_args() == 1 )
			{
				$this->coach = func_get_arg(0);
			}

			return $this;
		}

		//for league
		function league()
		{
			// string league()
			if( func_num_args() == 0 )
			{
				return $this->league;
			}
			
			// void league($value)
			else if( func_num_args() == 1 )
			{
				$this->league = func_get_arg(0);
			}

			return $this;
		}

		//for players
		function players()
		{
			// string players()
			if( func_num_args() == 0 )
			{
				return $this->players;
			}
			
			// void players($value)
			else if( func_num_args() == 1 )
			{
				$this->players = func_get_arg(0);
			}

			return $this;
		}

	}

?>