<?php 

	require_once('functions/link_inviting.php');
	require_once('classes/User.php');
	require_once('classes/League.php');

	//are we logged in? get_user() is null if not
	$user = get_user();

	//if already logged in redirect them to their dashboard
	if ($user !== null)
	{
		header ("Location: dashboard.php");
		exit;
	}

	//check if there's a link
	if (!isset($_GET['link']))
	{
		header ("Location: login.php?status=no_registration_link_found");
		exit;
	}

	//only valid links can be used to get here
	$registration_data = check_link($_GET['link']);
	if ($registration_data == null)
	{
		header ("Location: login.php?status=invalid_registration_link");
		exit;
	}

	//stringify the user type
	if ($registration_data['user_type'] == 1)
	{
		$type_stringed = "League Owner";
	}
	else if ($registration_data['user_type'] == 2)
	{
		$type_stringed = "Coach";
	}

	//register them
	if (isset($_POST['register']))
	{
		if ($registration_data['user_type'] == 2)
		{
			$result = register_user($_POST['email'], $_POST['username'], $_POST['password'], $_POST['user_type'], $_POST['league']);
		}
		elseif ($registration_data['user_type'] == 1)
		{
			//create league
			$league_id = create_league($_POST['league_name']);

			//create user
			$user_id = register_user($_POST['email'], $_POST['username'], $_POST['password'], $_POST['user_type'], $league_id);

			//assign user to league
			$result = assign_owner($league_id, $user_id);
		}

		echo $result;

		//log them in
		login_user($_POST['username'], $_POST['password']);
	}
?>


<!DOCTYPE html>
<html>
<head>
	<title>Registration</title>
</head>
<body>
	<h2>Create Account</h2>

	<!--if registering as a coach-->
	<?php if ($registration_data['user_type'] == 2): ?>
	<?php 
		//get league name
		$league = get_league_by_ID($registration_data['league']);
	?>

	<form method="post">
		Email: <?php echo $registration_data['email']; ?><br>
		Type: <?php echo $type_stringed; ?><br>	
		League: <?php echo $league['League_name']; ?>
		<input type="number" name="league" value=<?php echo $registration_data['league']; ?> hidden>
		<input type="email" name="email" value="<?php echo $registration_data['email']; ?>"  required hidden><br>
		<input type="number" name="user_type" value="<?php echo $registration_data['user_type']; ?>" required hidden>
		Username: <input type="text" name="username" required><br>
		Password: <input type="text" name="password" required><br>
		<button type="submit" name="register">Register</button>
	</form>
	<?php endif; ?>


	<!--if registering as a league owner-->
	<?php if ($registration_data['user_type'] == 1): ?>

	<form method="post">
		Email: <?php echo $registration_data['email']; ?><br>
		Type: <?php echo $type_stringed; ?><br>
		League name: <input type="text" name="league_name">
		<input type="email" name="email" value="<?php echo $registration_data['email']; ?>"  required hidden><br>
		<input type="number" name="user_type" value="<?php echo $registration_data['user_type']; ?>" required hidden>
		Username: <input type="text" name="username" required><br>
		Password: <input type="text" name="password" required><br>
		<button type="submit" name="register">Register</button>
	</form>
	<?php endif; ?>

	
	<br>
	<a href="login.php">already have an account?</a>
</body>
</html>