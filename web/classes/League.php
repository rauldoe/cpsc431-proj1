<?php
	require_once("functions/setup_DB.php");
	require_once("classes/Team.php");

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

	//create a new league, returns the id of the inserted league
	function create_league($name, $owner_ID=null)
	{
		//setup variables
		$league_table = LEAGUE_TABLE;
		$db = db_connect();

		//need this so SQL knows
		if ($owner_ID == null)
		{
			$owner_ID = "NULL";
		}

		//create league
		$query = "INSERT INTO $league_table (League_name, League_owner)
					VALUES ('$name', $owner_ID)";

		echo $query;
		//log them in, else return error message
		if (!$db->query($query)) 
		{
			return "Query to insert league failed - try again later";
		}
		//save the id inserted
		$id_of_inserted_league = $db->insert_id;

		//successful
		$db->close();
		return $id_of_inserted_league;
	}

	//assign an owner to a league
	function assign_owner($league_id, $owner_ID)
	{
		//setup variables
		$league_table = LEAGUE_TABLE;
		$db = db_connect();

		//create league
		$query = "UPDATE $league_table
					SET League_owner = $owner_ID
					WHERE ID = $league_id";

		//log them in, else return error message
		if (!$db->query($query)) 
		{
			return "Query to insert league failed - try again later";
		}

		//successful
		$db->close();
		return "success";
	}


	//get the league owned by 'user id', returns league object
	function get_league($user_id)
	{
		//setup variables
		$league_table = LEAGUE_TABLE;
		$db = db_connect();

		//find league
		$query = "SELECT * 
					FROM $league_table 
					WHERE League_owner = $user_id";

		if (!$result = $db->query($query))
		{
			throw new Exception('Could not execute query');
		}

		if ($result->num_rows == 0)
		{
			return null;
		}

		//if successful and found a league
		$league = $result->fetch_assoc();

		//return the object
		$db->close();
		return $league;
	}

	//get teams by 'league_id', returns a team of arrays
	function get_teams($league_id)
	{
		//setup variables
		$team_table = TEAM_TABLE;
		$db = db_connect();

		//find all teams owned by this league
		$query = "SELECT *
					FROM $team_table
					WHERE League = $league_id";

		if (!$result = $db->query($query))
		{
			return null;
		}

		if ($result->num_rows == 0)
		{
			return null;
		}

		//if successful and found teams
		$team_array = array();
		while ($row = $result->fetch_assoc()) 
		{
			array_push($team_array, $row);
		}

		//return the object
		$db->close();
		return $team_array;
	}

	function get_all_leagues()
	{
		//setup variables
		$league_table = LEAGUE_TABLE;
		$db = db_connect();

		//find all teams owned by this league
		$query = "SELECT *
					FROM $league_table";

		if (!$result = $db->query($query))
		{
			return null;
		}

		if ($result->num_rows == 0)
		{
			return null;
		}

		//if successful and found teams
		$league_array = array();
		while ($row = $result->fetch_assoc()) 
		{
			array_push($league_array, $row);
		}

		//return the object
		$db->close();
		return $league_array;
	
	}

	function get_league_by_ID($league_id)
	{
		//setup variables
		$league_table = LEAGUE_TABLE;
		$db = db_connect();

		//find league
		$query = "SELECT * 
					FROM $league_table 
					WHERE ID = $league_id";

		if (!$result = $db->query($query))
		{
			throw new Exception('Could not execute query');
		}

		if ($result->num_rows == 0)
		{
			return null;
		}

		//if successful and found a league
		$league = $result->fetch_assoc();

		//return the object
		$db->close();
		return $league;
	}

	/*
	//find league ID based on the name
	function league_to_ID($league_name)
	{
		//setup variables
		$league_table = LEAGUE_TABLE;
		$db = db_connect();

		//for special characters
		$league_name = $db->real_escape_string($league_name);

		//find all teams owned by this league
		$query = "SELECT *
					FROM $league_table
					WHERE League_name = '$league_name'";

					echo $query;
		if (!$result = $db->query($query))
		{
			return null;
		}

		if ($result->num_rows == 0)
		{
			return null;
		}

		//if successful and found a league
		$league = $result->fetch_assoc();
		var_dump($league);

		$db->close();
		return $league['ID'];
	}*/

?>