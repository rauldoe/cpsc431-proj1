<?php
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

	//check get data
	if (isset($_GET['status']))
	{
		$status = $_GET['status'];
		echo "Sorry: $status";
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
	Don't have an account? email us at 'fake_email@gmail.com' to request for one
</body>
</html>