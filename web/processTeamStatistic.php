<?php

// create short variable names
$teamID         = (int) $_POST['team_ID'];;
//$name       = preg_replace("/\t|\R/",' ',$_POST['name']);
$league      = preg_replace("/\t|\R/",' ',$_POST['time']);
$teamManager     = (int) $_POST['points'];
$teamName    = (int) $_POST['assists'];

require('TeamStatistic.php');

$newStat = new teamStatistic($teamID, $league, $teamManager, $teamName);

if( ! empty($teamID) )
{
  //file_put_contents('../data/statistics.txt', $newStat->toTSV()."\n", FILE_APPEND | LOCK_EX);
  $query = 'INSERT INTO Sports_team(ID, League, Team_manager, Team_name)'
	  . ' VALUES(?, ?, ?, ?, ?);';

  $db = new mysqli('127.0.0.1', 'hw3user', 'password', 'hw3');

  $stmt = $db->prepare($query);
  $stmt->bind_param('ddddd', $newStat->getTeamID(), $newStat->getLeague(), $newStat->getTeamManager(), $newStat->getTeamName());
  $stmt->execute();

  $db->close();
}

require('GameSchedule.php');
?>
