<?php
	require_once("functions/setup_DB.php");
	require_once("classes/Player.php");
	require_once("classes/Team.php");

	//-----Database Querying-----

	//create a team with 'coach_id' as the coach
	function insert_team($league_id, $coach_id, $team_name)
	{
		//setup variables
		$team_table = TEAM_TABLE;
		$db = db_connect();

		//if it's -1, we will make a team with no coach
		if ($coach_id == -1)
		{
			$coach_id = "NULL";
		}

		//insert player to this coach's team
		$query = "INSERT INTO $team_table (League, Coach, Team_name)
					VALUES ($league_id, $coach_id, '$team_name')";

		if (!$result = $db->query($query))
		{
			return "query failed";
		}

		//success, refresh the page
		$db->close();
		header("refresh: 0;");
		exit;
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

	//add player to a coach's team
	function add_player($team_id, $first_name, $last_name, $street, $city, $state, $country, $zip)
	{
		//setup variables
		$players_table = PLAYERS_TABLE;
		$db = db_connect();


		//insert player to this coach's team
		$query = "INSERT INTO $players_table 
					(Team, Name_first, Name_last, Street, City, State, Country, ZipCode)
					VALUES 
					($team_id, '$first_name', '$last_name', '$street', '$city', '$state', '$country', '$zip')";

		if (!$result = $db->query($query))
		{
			return "query failed";
		}

		//success, refresh the page
		$db->close();
		header("refresh: 0;");
		exit;
	}
?>