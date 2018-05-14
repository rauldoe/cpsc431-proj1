<?php
	require_once('functions/user_fns.php');
  require_once('classes/User.php');
  require_once('classes/League.php');

  $user = get_user();

  //must be logged in, only league owners/admins can access this page
  if ($user === null || $user->type() > 1)
  {
    header ("Location: login.php");
    exit;
  }

  //get the teams in the league
  if ($user->type() == 1)
  {
    //get league for this user
    $league = get_league($_SESSION['user']['ID']);

    //put it in an object
    $my_league = new League($league['League_name'], 
                $league['League_owner'], 
                get_teams($league['ID']));

    $user->my_league($my_league);
  }


  //get the teams in league
  $teams_in_league = $user->my_league()->teams();

  //get the league schedule
  $league_schedule = get_league_schedule($league['ID']);

  //inserting a new schedule
  if (isset($_POST['new_game_sched']))
  {
    $home_team_id = $_POST['home_team_id'];
    $away_team_id = $_POST['away_team_id'];
    $game_sched = $_POST['game_date']." ".$_POST['game_time'].":00";
    $league_id = $league['ID'];

    if ($home_team_id == $away_team_id)
    {
      echo "cannot have two teams match each other";
    }
    else
    {
      $success = create_game_schedule($league_id, $home_team_id, $away_team_id, $game_sched);
      if ($success === true)
      {
        //refresh page
        header("refresh: 0;");
        exit;
      }
      else
      {
        echo $success;
      }
    }
  }
?>

<!DOCTYPE html>
<html>
  <head>
    <title>CPSC 431 HW-3</title>
    <link rel="stylesheet" type="text/css" href="css/table.css">
  </head>
  <body>


    <h1 style="text-align:center">Game Schedule</h1>
    <a href="dashboard.php">Back to dashboard</a>
    <!--displaying league schedule-->
    <table>
      <tr>
        <th>Home Team</th>
        <th>Away Team</th>
        <th>Start Date</th>
      </tr>

      <?php foreach ($league_schedule as $schedule): ?>
      <?php 
        //get the team infos, just want the names
        $home_team_obj = get_team_by_ID($schedule['Home_team']); 
        $away_team_obj = get_team_by_ID($schedule['Away_team']);

        $home_team_obj = new Team($home_team_obj['info']['Team_name'], null, null, null);
        $away_team_obj = new Team($away_team_obj['info']['Team_name'], null, null, null);
      ?>
        <tr>
          <td><?php echo $home_team_obj->name(); ?></td>
          <td><?php echo $away_team_obj->name(); ?></td>
          <td><?php echo $schedule['Start_date']; ?></td>
        </tr>
      <?php endforeach; ?>
    </table>

    <br><br>
    <!--inserting a new schedule-->
    <table style="width: 100%; border:0px solid black; border-collapse:collapse;">
      <tr>
        <td style="vertical-align:top; border:1px solid black;">
          <!-- FORM to enter game statistics for a particular player -->
          <form method="post">

            <!--team 1-->
            <table style="margin: 0px auto; border: 0px; border-collapse:separate;">
              <tr>
                <td style="text-align: right; background: lightblue;">Home Team</td>
                <td>
                  <select name="home_team_id" required>
                    <?php foreach ($teams_in_league as $team): ?>
                    <?php 
                      //just want the name
                      $team_obj = get_team_by_ID($team['ID']); 
                      $team_obj = new Team($team_obj['info']['Team_name'], null, null, null);
                    ?>
                    <option value = "<?php echo $team['ID'] ?>"><?php echo $team_obj->name(); ?></option>
                    <?php endforeach ?>
                  </select>
                </td>
              </tr>
            </table>

            <!--team 2-->
            <table style="margin: 0px auto; border: 0px; border-collapse:separate;">
              <tr>
                <td style="text-align: right; background: lightblue;">Away team</td>
                <td>
                  <select name="away_team_id" required>
                    <?php foreach ($teams_in_league as $team): ?>
                    <?php 
                      //just want the name
                      $team_obj = get_team_by_ID($team['ID']); 
                      $team_obj = new Team($team_obj['info']['Team_name'], null, null, null);
                    ?>
                    <option value = "<?php echo $team['ID'] ?>"><?php echo $team_obj->name(); ?></option>
                    <?php endforeach ?>
                  </select>
                </td>
              </tr>
            </table>

            <!--date time for game-->
            <center>
              Game date:
              <input type="date" name="game_date" required>
              <input type="time" name="game_time" required>
            </center>

            <button type="submit" name="new_game_sched">Submit</button>
          </form>
        </td>
      </tr>
    </table>

  </body>
</html>
