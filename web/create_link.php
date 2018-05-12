<?php
	require_once('functions/link_inviting.php');
	require_once('classes/User.php');
	require_once('classes/League.php');

	$user = get_user();

	//must be logged in and be a admin, kick em out
	if ($user === null || $user->type() > 1)
	{
		header ("Location: login.php");
		exit;
	}

	//pick the user type first
	//if inviting a Coach, pick a league
	//if inviting a league manager, create a league
	$inviting_owner = false;
	$inviting_coach = false;
	if (isset($_POST['pick_user_type']))
	{
		if ($_POST['user_to_invite'] == 1)
		{
			$type_stringed = "League Owner";
			$inviting_owner = true;
		}
		else if ($_POST['user_to_invite'] == 2)
		{
			$type_stringed = "Coach";
			$inviting_coach = true;
		}
	}

	//create the link
	if (isset($_POST['make_link_coach']))
	{
		$result = insert_registration_link($_POST['email'], $_POST['user_type'], $_POST['league']);
		echo $result;
	}
	else if (isset($_POST['make_link_league_owner']))
	{
		$result = insert_registration_link($_POST['email'], $_POST['user_type'], null);
		echo $result;
	}

	//both admin and league owners can access this page, but have different restrictions
	if ($user->type() == 0)
	{
		$leagues = get_all_leagues();
	}
	else if ($user->type() == 1)
	{
		//get league for this user
		$league = get_league($_SESSION['user']['ID']);

		//put it in an object
		$my_league = new League($league['League_name'], 
								$league['League_owner'], 
								get_teams($league['ID']));

		$user->my_league($my_league);
	}
?>

<!DOCTYPE html>
<html>
<head>
	<title>admin page</title>
</head>
<body>

	<h4>Create Registration Link</h4>
	<a href="dashboard.php">back to dashboard</a>

	<!--For admins, pick if want to invite league owner or coach-->
	<?php if (!isset($_POST['pick_user_type'])): ?>
	<form method="post">

		<?php if ($user->type() == 0): ?>
		<select name="user_to_invite">
			<option value="1">League Owner</option>
			<option value="2">Coach</option>
		</select>
		<br>
		<button type="submit" name="pick_user_type">Pick invite type</button>
	</form>
		<?php elseif($user->type() == 1): ?>
			Their email: <input type="email" name="email"><br>
			League: <?php echo $user->my_league()->name(); ?><br>
			<input type="number" name="league" value="<?php echo $league['ID']; ?>" hidden>
			Type: Coach
			<input type="number" name="user_type" value="2" hidden>
			<br>
			<button type="submit" name = "make_link_coach">Create link</button>
		<?php endif; ?>
	<?php endif; ?>

	<!--For admin, if want to invite a coach-->
	<?php if ($inviting_coach): ?>
	<form method="post">
		Their email: <input type="email" name="email"><br>
		Type: <?php echo $type_stringed; ?>
		<input type="number" name="user_type" value="<?php echo $_POST['user_to_invite']; ?>" hidden><br>
		League:
		<select name="league">
			<?php foreach($leagues as $league): ?>
			<option value = "<?php echo $league['ID'] ?>"><?php echo $league['League_name'] ?></option>
			<?php endforeach; ?>
		</select>
		<br>
		<button type="submit" name = "make_link_coach">Create link</button>
	</form>

	<!--if want to invite a league owner-->
	<?php elseif($inviting_owner): ?>
	<form method="post">
		Their email: <input type="email" name="email"><br>
		Type: <?php echo $type_stringed; ?>
		<input type="number" name="user_type" value="<?php echo $_POST['user_to_invite']; ?>" hidden><br>
		<button type="submit" name = "make_link_league_owner">Create link</button>	
	</form>
	<?php endif; ?>

</body>
</html>