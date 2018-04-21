<?php
	require_once('functions/setup_DB.php');
	require_once('classes/User.php');

	$user = get_user();

	//coach's team
	if ($user->type() == 2)
	{
		//get team for this user
		$team = get_team($_SESSION['user']['ID']);

		//put it in an object
		$team_object = new Team ($team['Team_name'],
						 $user->username(),
						 get_league_name($team['ID']),
						 get_players($team['ID']));
	}

	$teams_in_league = get_teams($_SESSION['user']['league']);

	$teams_without_coach = array();
	foreach ($teams_in_league as $team_row)
	{
		if ($team_row['Coach'] == null)
		{
			array_push($teams_without_coach, $teams_in_league);
		}
	}
?>

<!DOCTYPE html>
<html>
<head>
	<title>team view</title>
	<link rel="stylesheet" type="text/css" href="css/table.css">
</head>
<body>

	<?php if ($team != null): ?>
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
	<?php else: ?>
		<h4>You have no team</h4>

		<?php if ($teams_without_coach != []): ?>
		Select a team:
		<form method="post">
			<select name="team">
				<?php foreach ($teams_in_league as $team): ?>
					<?php if ($team['Coach'] == null): ?>
					<option value="<?php echo $team['ID'] ?>"><?php echo $team['Team_name'] ?></option>
					<?php endif; ?>
				<?php endforeach; ?>
			</select>
		</form>
		<?php else: ?>
		There are currently no open teams
		<?php endif; ?>
	<?php endif; ?>
</body>
</html>