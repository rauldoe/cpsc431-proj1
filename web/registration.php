<?php 
	require_once('functions/setup_DB.php');
	require_once('classes/User.php');

	//are we logged in? get_user() is null if not
	$user = get_user();

	//if already logged in redirect them to their dashboard
	if ($user !== null)
	{
		header ("Location: dashboard.php");
		exit;
	}

	//check post data
	if (isset($_POST['register']))
	{
		//add to database
		$user_type = 1; // test value
		$result = register_user($_POST['username'], $_POST['password'], $user_type);
		echo $result;
	}
?>


<!DOCTYPE html>
<html>
<head>
	<title>Registration</title>
</head>
<body>
	<h2>Create Account</h2>
	<form method="post">
		Username: <input type="text" name="username" required>
		Password: <input type="text" name="password" required>
		<button type="submit" name="register">Register</button>
	</form>
	<br>
	<a href="login.php">already have an account?</a>
</body>
</html>