<?php
	require_once("functions/setup_DB.php");
	require_once("classes/Team.php");

	//-----Database Querying-----

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
			return "Query to insert league owner failed - try again later";
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
					WHERE ManagerID = $user_id";

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
			return array();
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

	//get a list of game schedule for a league
	function get_league_schedule($league_id)
	{
		
		//setup variables
		$games_table = GAMES_TABLE;
		$db = db_connect();

		//find league
		$query = "SELECT * 
					FROM $games_table 
					WHERE League = $league_id";

		if (!$result = $db->query($query))
		{
			throw new Exception('Could not execute query');
		}

		if ($result->num_rows == 0)
		{
			return array();
		}

		//if successful and found game schedules
		$schedules = array();
		while ($row = $result->fetch_assoc()) 
		{
			array_push($schedules, $row);
		}

		//return the object
		$db->close();
		return $schedules;
		
	}

	//insert new game schedule
	function create_game_schedule($league, $home_team, $away_team, $date)
	{
		//setup variables
		$games_table = GAMES_TABLE;
		$db = db_connect();

		//"insert new game schedule" query
		$query = "INSERT INTO $games_table (League, Home_team, Away_team, Start_date) 
					VALUES ($league, $home_team, $away_team, '$date')";

		//insert new game schedule
		if (!$db->query($query)) 
		{
			return "Query to insert game schedule failed - try again later";
		}

		//successful
		$db->close();
		return true;
	}
?>