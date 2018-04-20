<?php
	define("DB_HOST", '127.0.0.1');
	define("DB_USERNAME", 'user_1');
	define("DB_PASSWORD", 'password');
	define("DB_NAME", 'final_project');

	define("USER_TABLE", 'User');
	define("TEAM_TABLE", 'Sports_team');
	define("LEAGUE_TABLE", "League");
	define("PLAYERS_TABLE", "Players");

	//connect to database
	function db_connect () 
	{
		$db = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
		if ($db)
		{
			return $db;
		}
		else
		{
			throw new Exception('Could not connect to database server');
		}
	}
?>