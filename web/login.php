<?php
	require_once('setup_DB.php');
	require_once('User.php');

	//check session data
	if (isset($_SESSION['user']))
	{
		echo "were logged in";
		var_dump($_SESSION['user']);
	}

	//check post data
	if (isset($_POST['login']))
	{
		//confirm valid username & password
		$result = login_user($_POST['username'], $_POST['password']);
		var_dump($result);
	}
	else if (isset($_POST['logout']))
	{
		logout_user();
	}
?>

<!DOCTYPE html>
<html>
<head>
	<title>login</title>
</head>
<body>
	<?php if (isset($_SESSION['user'])): ?>
	<form method="post">
		<button type="submit" name="logout">Log out</button>
	</form>
	<?php else: ?>
	<form method="post">
		Username: <input type="text" name="username" required>
		Password: <input type="text" name="password" required>
		<button type="submit" name="login">Register</button>
	</form>
	<?php endif; ?>
</body>
</html>