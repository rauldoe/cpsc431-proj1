<!DOCTYPE html>
<html>
  <head>
    <title>CPSC 431 HW-3</title>
  </head>
  <body>
    <h1 style="text-align:center">Game Schedule</h1>

<?php
      require_once('TeamStatistic.php');

      // Connect to database
      $db = new mysqli('127.0.0.1', 'hw3user', 'password', 'hw3');
      //if (mysql_connect_errno())
      //sample change
      $query ="
      SELECT roster.ID
      , roster.League
      , roster.Team_manager
      , roster.Team_name
      , roster.City";


      //$stmt = $db->prepare($query);
      //$stmt->bind_result($teamID, $league, $teamManager, $teamName);
      //$stmt->execute();
      //$stmt->store_result();

      $db->close();
?>

    <table style="width: 100%; border:0px solid black; border-collapse:collapse;">
      <tr>
        <th style="width: 50%;"></th>
        <th style="width: 50%;"></th>
      </tr>
      <tr>
        <td style="vertical-align:top; border:1px solid black;">
          <!-- FORM to enter game statistics for a particular player -->
          <form action="processTeamStatistic.php" method="post">
            <table style="margin: 0px auto; border: 0px; border-collapse:separate;">
              <tr>
                <td style="text-align: right; background: lightblue;">Team 1</td>
<!--            <td><input type="text" name="name" value="" size="50" maxlength="500"/></td>  -->
                <td><select name="team_ID" required>
                  <option value="" selected disabled hidden>Choose team here</option>
                  <?php
                    // for each row of data returned,
                    //   construct an Address object providing first and last name
                    //   emit an option for the pull down list such that
                    //     the displayed name is retrieved from the Address object
                    //     the value submitted is the unique ID for that player
                    // for example:
                    //     <option value="101">Duck, Daisy</option>

                    $len = count($teamList);

                    for ($i=0; $i < $len; ++$i ) {
                      $item = $teamList[$i];
                      echo '<option value="' . $item->getTeamID() . '">' . $item->getTeamName() . '</option>';
                    }
                  ?>
                </select></td>
              </tr>
            </table>
          </form>
        </td>

        <td style="vertical-align:top; border:1px solid black;">
          <!-- FORM to enter game statistics for a particular player -->
          <form action="processTeamStatistic.php" method="post">
            <table style="margin: 0px auto; border: 0px; border-collapse:separate;">
              <tr>
                <td style="text-align: right; background: lightblue;">Team 2</td>
<!--            <td><input type="text" name="name" value="" size="50" maxlength="500"/></td>  -->
                <td><select name="team_ID" required>
                  <option value="" selected disabled hidden>Choose team here</option>
                  <?php
                    // for each row of data returned,
                    //   construct an Address object providing first and last name
                    //   emit an option for the pull down list such that
                    //     the displayed name is retrieved from the Address object
                    //     the value submitted is the unique ID for that player
                    // for example:
                    //     <option value="101">Duck, Daisy</option>

                    $len = count($teamList);

                    for ($i=0; $i < $len; ++$i ) {
                      $item = $teamList[$i];
                      echo '<option value="' . $item->getTeamID() . '">' . $item->getTeamName() . '</option>';
                    }
                  ?>
                </select></td>
              </tr>
            </table>
          </form>
        </td>
      </tr>
    </table>

    <form>
      <center>
        Game date:
        <input type="date" name="Date">
      </center>
    </form>

    <center>
     <td colspan="2" style="text-align: center;"><input type="submit" value="Submit" /></td>
   </center>
  </body>
</html>
