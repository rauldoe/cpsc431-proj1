<?php 
	require_once('classes/User.php');

	$user = get_user();

	//must be logged in and be a league owner, kick em out
	if ($user === null || $user->type() != 1)
	{
		header ("Location: login.php");
		exit;
	}
	
	//get league for this user
	$league = get_league($_SESSION['user']['ID']);

	//get the teams for that league
	$users = get_users_by_league($league['ID']);

	//if no league found take em back
	if ($league == null)
	{
		header ("Location: dashboard.php");
		exit;
	}

	//request to add a user
	if (isset($_POST['add_user_request']))
	{
        //insert_staff($email, $username, $password, $type, $league)
		$result = insert_staff($_POST['email'], $_POST['username'], $_POST['password'], 3, $_SESSION['user']['league']);
		echo $result;
	}

	//get all teamless coaches to assign to a team
    //$teamless_coaches = get_teamless_coaches($_SESSION['user']['league']);
    $role_array = get_roles();
?>

<!DOCTYPE html>
<html>
<head>
	<title>Manage Staff</title>
	<link rel="stylesheet" type="text/css" href="css/table.css">
</head>
<body>

	<h3>Staff in The League: </h3>
	<a href="dashboard.php">back to dashboard</a>
	<table>
		<tr>
			<th>Username</th>
			<th>Email</th>
			<th>User Type</th>
		</tr>
		<?php foreach($users as $user): ?>
		<?php
        $user_object = new User('', '');
        $user_object->my_id($user["ID"]);
        $user_object->my_email($user["Email"]);
        $user_object->username($user["Username"]);
        $user_object->type($user["User_type"]);
        //$user_object->my_league($user["ID"]);
        
		?>
		<tr>
			<td><?php echo $user_object->username(); ?></td>
			<td><?php echo $user_object->my_email(); ?></td>
			<td>
                <?php
                // for each row of data returned,
                //   construct an Address object providing first and last name
                //   emit an option for the pull down list such that
                //     the displayed name is retrieved from the Address object
                //     the value submitted is the unique ID for that player
                // for example:
                //     <option value="101">Duck, Daisy</option>

                $len = count($role_array);

                for ($i=0; $i < $len; ++$i ) {
                    $item = $role_array[$i];
                    if ($user_object->type() == $item['ID'])
                    {
                        echo $item['RoleName'];
                        break;
                    }
                }
                ?>
			</td>
		</tr>
		<?php endforeach; ?>
	</table>

	<!--Create a Staff-->
	<br>
	Create a Staff Member
	<form method="post">
		Username: <input type="text" name="username"><br/>
		Email: <input type="email" name="email"><br/>
		Password: <input type="password" name="password"><br/>
		<br>
		<button type="submit" name="add_user_request">Create Staff Member</button>
	</form>

	<a href="create_link.php">Need a coach? Invite them here</a>
</body>
</html>