<?php

// create short variable names
$playerId         = (int) $_POST['name_ID'];;
//$name       = preg_replace("/\t|\R/",' ',$_POST['name']);
$time       = preg_replace("/\t|\R/",' ',$_POST['time']);
$points     = (int) $_POST['points'];
$assists    = (int) $_POST['assists'];
$rebounds   = (int) $_POST['rebounds'];


require('PlayerStatistic.php');

$newStat = new PlayerStatistic($playerId, $name, $time, $points, $assists, $rebounds);

if( ! empty($playerId) )
{
  //file_put_contents('../data/statistics.txt', $newStat->toTSV()."\n", FILE_APPEND | LOCK_EX);
  $query = 'INSERT INTO Statistics(Player, PlayingTimeMin, PlayTimeSec, Points, Assists, Rebounds)'
	  . ' VALUES(?, ?, ?, ?, ?, ?);';
  
  $db = new mysqli('127.0.0.1', 'hw3user', 'password', 'hw3');
  
  $stmt = $db->prepare($query); 
  $stmt->bind_param('dddddd', $newStat->getPlayerId(), $newStat->getPlayingTimeMin(), $newStat->getPlayingTimeSec(), $newStat->pointsScored(), $newStat->assists(), $newStat->rebounds()); 
  $stmt->execute();

  $db->close();  
}

require('home_page.php');
?>

