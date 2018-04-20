<?php
	require_once('functions/setup_DB.php');
	require_once('classes/User.php');

	$user = get_user();

	//must be logged in, kick em out
	if ($user === null)
	{
		header ("Location: login.php");
		exit;
	}

	if ($user->type() == 1)
	{
		//get league for this user
		$league = get_league($_SESSION['user']['ID']);

		//put it in an object
		$my_league = new League($league['League_name'], 
								$league['League_owner'], 
								get_teams($league['ID']));

		$user->my_league($my_league);
	}
	else if ($user->type() == 2)
	{
		//get team for this user
		$team = get_team($_SESSION['user']['ID']);

		//put it in an object
		$my_team = new Team ($team['Team_name'],
						 $user->username(),
						 get_league_name($team['ID']),
						 get_players($team['ID']));
		$user->my_team($my_team);
	}
?>


<!DOCTYPE html>
<html>
<head>
	<title>dashboard</title>
</head>
<body>
	<?php echo "Hello ".$user->username(); ?>

	<?php if ($user->type() == 1): ?>
	<h2>League Manager Dashboard</h2>
	<div>
		Your League: <?php var_dump($user->my_league()); ?>

		<?php if ($league !== null): ?>
			<a href="manage_teams.php">Manage teams</a>
			<a href="schedule_game.php">Schedule games</a>
		<?php endif; ?>
			<a href="manage_staff.php">Manage staff</a>
	</div>
	<?php elseif($user->type() == 2): ?>
	<h2>Coach Dashboard</h2>
	<div>
		Your team: <?php var_dump($user->my_team()); ?>
		
		<?php if ($team !== null): ?>
			<a href="team_view.php?team_ID=<?php echo $team['ID'] ?>">Manage my team</a>
		<?php endif; ?>
	</div>
	<?php endif; ?>

	<br>
	<form method="post" action="login.php">
		<button type="submit" name="logout">Log out</button>
	</form>
</body>
</html>