<?php 
	require('setup_DB.php');
	require('User.php');

	if (isset($_POST['register']))
	{
		$user_type = 1; // test value
		$user = new User($_POST['username'], $user_type, $_POST['password']);

		//add to database
		$result = $user->add_to_DB();
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