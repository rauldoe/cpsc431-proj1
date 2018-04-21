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
		$my_team = new Team ($team['Team_name'],
						 $user->username(),
						 get_league_name($team['ID']),
						 get_players($team['ID']));
	}

	var_dump($_SESSION);
	$teams_in_league = get_teams($_SESSION['user']['league']);
	var_dump($teams_in_league);
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
		Select a team:
		<form method="post">
			<select name="team">
				<?php foreach ($teams_in_league as $team): ?>
				<option value="<?php echo $team['ID'] ?>"><?php echo $team['Team_name'] ?></option>
				<?php endforeach; ?>
			</select>
		</form>
	<?php endif; ?>
</body>
</html>