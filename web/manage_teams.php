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
	$teams = get_teams($league['ID']);

	//if no league found take em back
	if ($league == null)
	{
		header ("Location: dashboard.php");
		exit;
	}

	//request to add a team
	if (isset($_POST['add_team_request']))
	{
		$result = insert_team($_SESSION['user']['league'], $_POST['coach_id'], $_POST['team_name']);
		echo $result;
	}

	//get all teamless coaches to assign to a team
	$teamless_coaches = get_teamless_coaches($_SESSION['user']['league']);
?>

<!DOCTYPE html>
<html>
<head>
	<title>Manage teams</title>
	<link rel="stylesheet" type="text/css" href="css/table.css">
</head>
<body>

	<h3>Teams in The League: </h3>
	<a href="dashboard.php">back to dashboard</a>
	<table>
		<tr>
			<th>Team Name</th>
			<th>Coach</th>
			<th>Actions</th>
		</tr>
		<?php foreach($teams as $team): ?>
		<?php
		if ($team['Coach'] != null)
		{
			$coach = get_userinfo($team['Coach']);
		}
		else
		{
			$coach['Username'] = "";
		}
		$team_object = new Team($team['Team_name'],
								$coach['Username'],
								get_league_name($team['League']),
								get_players($team['ID']));
		?>
		<tr>
			<td><?php echo $team_object->name(); ?></td>
			<td><?php echo $team_object->coach(); ?></td>
			<td>
				<form method="get" action="team_view.php">
					<input type="text" name="team_ID" value="<?php echo $team['ID']; ?>" hidden>
					<button type="submit">View Team</button>
				</form>
			</td>
		</tr>
		<?php endforeach; ?>
	</table>

	<!--Create a team-->
	<br>
	Create a team
	<form method="post">
		Team name: <input type="text" name="team_name"><br>
		Coach:
		<select name="coach_id">
			<option value="-1"><?php echo "no coach" ?></option>
			<?php foreach ($teamless_coaches as $coach): ?>
			<option value=<?php echo $coach['ID']; ?>><?php echo $coach['Username']; ?></option>
			<?php endforeach; ?>
		</select>
		<br>
		<button type="submit" name="add_team_request">Create Team</button>
	</form>

	<a href="create_link.php">Need a coach? Invite them here</a>
</body>
</html>