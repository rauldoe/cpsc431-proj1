<?php
	require_once('classes/User.php');
	require_once('classes/League.php');

	$user = get_user();

	//must be logged in
	if ($user === null)
	{
		header ("Location: login.php");
		exit;
	}

	if (isset($_POST['change_pass']))
	{
		$successful = change_pass($_SESSION['user']['ID'], $_POST['new_password']);
		if ($successful)
		{
			echo "password change successful";
		}
		else
		{
			echo $successful;
		}
	}
?>

<!DOCTYPE html>
<html>
<head>
	<title>change password</title>
</head>
<body>
<h2>password change</h2>
<?php echo "Hello ".$user->username(); ?>

<a href="dashboard.php">Back to dashboard</a>

<form method="post">
	New password: <input type="text" name="new_password" required><br>
	<button type="submit" name="change_pass">change password</button>
</form>

</body>
</html>