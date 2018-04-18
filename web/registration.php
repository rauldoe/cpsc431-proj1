<?php 
	require_once('setup_DB.php');
	require_once('User.php');

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
	<form method="post">
		Username: <input type="text" name="username" required>
		Password: <input type="text" name="password" required>
		<button type="submit" name="register">Register</button>
	</form>
</body>
</html>