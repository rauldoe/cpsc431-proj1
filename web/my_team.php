<?php
	require_once('classes/User.php');
	require_once('classes/Team.php');

	$user = get_user();

	//coach's team
	if ($user->type() == 2)
	{
		//get team for this user
		$team = get_team($_SESSION['user']['ID']);

		//put it in an object
		$team_object = new Team ($team['Team_name'],
						 $user->username(),
						 get_league_name($team['ID']),
						 get_players($team['ID']));
	}

	//request to add a player to team
	if (isset($_POST['add_player_request']))
	{
		//make sure everything's good
		$first_name = htmlspecialchars($_POST['firstName']);
		$last_name  = htmlspecialchars($_POST['lastName']);
		$street     = htmlspecialchars($_POST['street']);
		$city    	= htmlspecialchars($_POST['city']);
		$state   	= htmlspecialchars($_POST['state']);
		$country	= htmlspecialchars($_POST['country']);
		$zip 		= htmlspecialchars($_POST['zipCode']);

		$zip_ok = false;
		if (preg_match("[(?!0{5})(?!9{5})\d{5}(-(?!0{4})(?!9{4})\d{4})?]", $zip) || $zip == NULL)
		{
			$zip_ok = true;
		}
		else
		{
			echo "Unsuccessful: invalid zip code";
		}

		if( ! empty($first_name) && $zip_ok)
		{
			//add player to team
			$result = add_player($team['ID'], $first_name, $last_name, $street, $city, $state, $country, $zip);
		}
	}

	//if no team, get coachless teams for coach to pick from
	if ($team == null)
	{
		$teams_in_league = get_teams($_SESSION['user']['league']);
		$teams_without_coach = array();
		foreach ($teams_in_league as $team_row)
		{
			if ($team_row['Coach'] == null)
			{
				array_push($teams_without_coach, $teams_in_league);
			}
		}
	}
?>

<!DOCTYPE html>
<html>
<head>
	<title>team view</title>
	<link rel="stylesheet" type="text/css" href="css/table.css">
</head>
<body>

	<!--If there's no team assigned-->
	<?php if ($team != null): ?>
	<h3>Team name: <?php echo $team_object->name(); ?></h3>
	Coach: <?php echo $team_object->coach(); ?> <br>
	League: <?php echo $team_object->league(); ?><br>

	<!--If there's no players -->
	<?php if ($team_object->players() == null): ?>
	There are no players on this team
	<?php else: ?>
	Players: <br>
	<table>
		<tr>
			<th>Name</th>
			<th>Address</th>
			<th>is active</th>
		</tr>
		<?php foreach($team_object->players() as $player): ?>
		<tr>
			<td><?php echo $player->name(); ?></td>
			<td><?php echo $player->get_full_address(); ?></td>
			<td><?php echo $player->is_active(); ?></td>
		</tr>
		<?php endforeach; ?>
	</table>
	<?php endif; ?>

	<!--Add player to team-->
	<h3>Add a player to team</h3>
	<form method="post">
		First name: <input type="text" name="firstName" value="" size="35" maxlength="250" required/><br>
		Last name: <input type="text" name="lastName" value="" size="35" maxlength="250"/><br>
		Street: <input type="text" name="street" value="" size="35" maxlength="250"/><br>
		City: <input type="text" name="city" value="" size="35" maxlength="250"/><br>
		State: <input type="text" name="state" value="" size="35" maxlength="100"/><br>
		Country: <input type="text" name="country" value="" size="20" maxlength="250"/><br>
		Zip Code: <input type="text" name="zipCode" value="" size="10" maxlength="10"/><br>
		<button type="submit" name="add_player_request">Add player</button>
	</form>

	<!--If they don't have a team yet-->
	<?php else: ?>
		<h4>You have no team</h4>

		<?php if ($teams_without_coach != []): ?>
		Select a team:
		<form method="post">
			<select name="team">
				<?php foreach ($teams_in_league as $team): ?>
					<?php if ($team['Coach'] == null): ?>
					<option value="<?php echo $team['ID'] ?>"><?php echo $team['Team_name'] ?></option>
					<?php endif; ?>
				<?php endforeach; ?>
			</select>

			<button>are we sure we want the coach to be able to pick?</button>
		</form>
		<?php else: ?>
		There are currently no open teams
		<?php endif; ?>
	<?php endif; ?>
</body>
</html>