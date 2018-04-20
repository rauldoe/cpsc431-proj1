<?php 
	require_once('functions/setup_DB.php');
	require_once('classes/User.php');

	$user = get_user();

	//must be logged in and be a league owner, kick em out
	if ($user === null || $user->type() != 1)
	{
		header ("Location: login.php");
		exit;
	}
	
	//get league for this user
	$league = get_league($_SESSION['user']['ID']);

	//get the teams for that league
	$teams = get_teams($league['ID']);

	//if no league found take em back
	if ($league == null)
	{
		header ("Location: dashboard.php");
		exit;
	}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Manage teams</title>
	<link rel="stylesheet" type="text/css" href="css/table.css">
</head>
<body>

	<h3>Teams in The League: </h3>
	<?php foreach($teams as $team): ?>
	<?php
		$team_object = new Team($team['Team_name'],
								$team['Coach'],
								get_league_name($team['League']),
								get_players($team['ID']));
	?>
	<h3><?php echo $team_object->name(); ?></h3>
	<table>
		<tr>
			<th>Name</th>
			<th>Address</th>
			<th>is active</th>
			<th>View</th>
		</tr>
		<?php foreach($team_object->players() as $player): ?>
		<tr>
			<td><?php echo $player->name(); ?></td>
			<td><?php echo $player->get_full_address(); ?></td>
			<td><?php echo $player->is_active(); ?></td>
			<td>
				<form method="get" action="team_view.php">
					<input type="text" name="team_ID" value="<?php echo $team['ID']; ?>" hidden>
					<button type="submit">View Team</button>
				</form>

			</td>
		</tr>
		<?php endforeach; ?>
	</table>
	<?php endforeach; ?>

</body>
</html>