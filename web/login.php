<?php
	require_once('functions/setup_DB.php');
	require_once('classes/User.php');

	//if log out request found
	if (isset($_POST['logout']))
	{
		logout_user();
	}

	//are we logged in? get_user() is null if not
	$user = get_user();

	//redirect to dashboard if logged in
	if ($user !== null)
	{
		header ("Location: dashboard.php");
		exit;
	}

	//check post data
	if (isset($_POST['login']))
	{
		//confirm valid username & password
		$result = login_user($_POST['username'], $_POST['password']);
		var_dump($result);
	}
?>

<!DOCTYPE html>
<html>
<head>
	<title>login</title>
</head>
<body>
	<h2>Login</h2>
	<form method="post">
		Username: <input type="text" name="username" required>
		Password: <input type="text" name="password" required>
		<button type="submit" name="login">Login</button>
	</form>
	<br>
	<a href="registration.php">Don't have an account?</a>
</body>
</html>