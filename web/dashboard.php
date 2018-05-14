<?php
	require_once('functions/user_fns.php');
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
		$my_league = new League($league['LeagueName'], 
								$league['ManagerID'], 
								get_teams($league['ID']));

		//work on teams
		/*$my_league = new League($league['LeagueName'], 
								$league['ManagerID'], 
								get_teams($league['ID']));*/

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
	<!--something everyone can see/access-->
	<?php echo "Hello ".$user->username(); ?>

	<a href="change_password.php">Change password</a>

	<!--League owner dashboard-->
	<?php if ($user->type() == 1): ?>
	<h2>League Manager Dashboard</h2>
	<div>
		Your League: <?php var_dump($user->my_league()); ?>

		<?php if ($league !== null): ?>
			<a href="manage_teams.php">Manage teams</a>
			<a href="GameSchedule.php">Schedule games</a>
		<?php endif; ?>
			<a href="create_link.php">Create invite link</a>
			<a href="manage_staff.php">Manage staff</a>
	</div>
	<!--Coach dashboard-->
	<?php elseif($user->type() == 2): ?>
	<h2>Coach Dashboard</h2>
	<div>
		Your team: 

		<?php if ($user->my_team()->name() == null)
		{
			echo "<br>You currently have no team, go make one in ";
		}
		else
		{
			var_dump($user->my_team());
		}
		?>
		<a href="my_team.php">Manage my team</a>
	</div>
	<!--Admin dashboard-->
	<?php elseif($user->type() == 0): ?>
	<h2>admin Dashboard</h2>

	<a href="create_link.php">Create invite link</a>
	<?php endif; ?>


	<br>
	<form method="post" action="login.php">
		<button type="submit" name="logout">Log out</button>
	</form>
</body>
</html>