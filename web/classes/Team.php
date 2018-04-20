<?php
	require_once("functions/setup_DB.php");
	require_once("classes/Player.php");

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

	//get team by it's 'team_id'
	function get_team_by_ID($team_id)
	{
		//setup variables
		$team_table = TEAM_TABLE;
		$db = db_connect();

		//find team
		$query = "SELECT * 
					FROM $team_table 
					WHERE ID = $team_id";

		if (!$result = $db->query($query))
		{
			return null;
		}

		if ($result->num_rows == 0)
		{
			return null;
		}

		//get team
		$data['info'] = $result->fetch_assoc();

		//get players in team
		$data['players'] = get_players($team_id);

		$db->close();
		return $data;
	}

	//get team owned by 'user_id'
	function get_team($user_id)
	{
		//setup variables
		$team_table = TEAM_TABLE;
		$db = db_connect();

		//find team
		$query = "SELECT * 
					FROM $team_table 
					WHERE Coach = $user_id";

		if (!$result = $db->query($query))
		{
			return null;
		}

		if ($result->num_rows == 0)
		{
			return null;
		}


		//team found
		$team = $result->fetch_assoc();

		$db->close();
		return $team;
	}

	//get league name that 'team_id' belongs to, returns string
	function get_league_name($team_id)
	{
		//setup variables
		$league_table = LEAGUE_TABLE;
		$team_table = TEAM_TABLE;
		$db = db_connect();

		//find league
		$query = "SELECT * 
					FROM $league_table 
					WHERE ID = (SELECT League
								FROM $team_table
								WHERE ID = $team_id)";

		if (!$result = $db->query($query))
		{
			return null;
		}

		if ($result->num_rows == 0)
		{
			return null;
		}

		//return it
		$league = $result->fetch_assoc();
		$db->close();
		return $league['League_name'];
	}

	//get players in 'team_id', returns array of players
	function get_players($team_id)
	{
		//setup variables
		$players_table = PLAYERS_TABLE;
		$db = db_connect();

		//get players from this team
		$query = "SELECT * 
					FROM $players_table 
					WHERE Team = $team_id";

		if (!$result = $db->query($query))
		{
			return null;
		}

		if ($result->num_rows == 0)
		{
			return null;
		}

		//if successful and found players
		$player_array = array();
		while ($row = $result->fetch_assoc()) 
		{
			$player_object = new Player($row['Name_first'], $row['Name_last'], $row['Street'], $row['City'], $row['State'], $row['Country'], $row['ZipCode'], $row['currently_active']);
			array_push($player_array, $player_object);
		}

		//return the object
		$db->close();
		return $player_array;

	}

?>