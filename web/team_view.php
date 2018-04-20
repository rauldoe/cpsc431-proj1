<?php
	require_once('functions/setup_DB.php');
	require_once('classes/User.php');

	$user = get_user();

	//this page is accessed with POST
	if (!isset($_GET['team_ID']))
	{
		echo "make sure there's GET data like: 'team_view.php?team_ID=1'";
		throw new Exception('Found no GET data');
	}
	else
	{
		//get team with the requested 'GET' team id
		$team = get_team_by_ID($_GET['team_ID']);

		//put team in an object
		$team_object = new Team($team['info']['Team_name'],
								$team['info']['Coach'],
								get_league_name($team['info']['League']),
								get_players($team['info']['ID']));
	}
?>

<!DOCTYPE html>
<html>
<head>
	<title>team view</title>
	<link rel="stylesheet" type="text/css" href="css/table.css">
</head>
<body>
	<h3>Team name: <?php echo $team_object->name(); ?></h3>
	Coach: <?php echo $team_object->coach(); ?> <br>
	League: <?php echo $team_object->league(); ?><br>
	Players: <br>
	<table>
		<tr>
			<th>Name</th>
			<th>Address</th>
			<th>is active</th>
		</tr>
		<?php foreach($team_object->players() as $player): ?>
		<tr>
			<td><?php echo $player->name(); ?></td>
			<td><?php echo $player->get_full_address(); ?></td>
			<td><?php echo $player->is_active(); ?></td>
		</tr>
		<?php endforeach; ?>
	</table>
</body>
</html>