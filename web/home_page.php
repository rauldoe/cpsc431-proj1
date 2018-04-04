<!DOCTYPE html>
<html>
  <head>
    <title>CPSC 431 FINAL PROJECT</title>
  </head>
  <body>
    <h1 style="text-align:center">Cal State Fullerton Basketball Statistics</h1>

<?php
      require_once('Address.php');
      require_once('PlayerStatistic.php');

      // Connect to database
      $db = new mysqli('127.0.0.1', 'hw3user', 'password', 'hw3');
      //if (mysql_connect_errno())
      
      $query ="
      SELECT roster.ID
      , roster.name_Last
      , roster.name_First
      , roster.Street
      , roster.City
      , coalesce(roster.ZipCode, '') as ZipCode
      , roster.State
      , roster.Country
      , count(stat.Player) as games_played 
      , avg(coalesce(stat.TotalTime/60,0)) as time_on_court
      , avg(coalesce(stat.Points,0)) as points_scored
      , avg(coalesce(stat.Assists,0)) as number_of_assists
      , avg(coalesce(stat.Rebounds,0)) as number_of_rebounds
        
      FROM teamroster as roster LEFT JOIN statistics as stat ON (roster.ID=stat.Player)
      GROUP BY roster.ID
      ORDER BY roster.name_Last, roster.name_First;
";


      $stmt = $db->prepare($query);
      $stmt->bind_result($id, $name_Last, $name_First, $street, $city, $state, $country, $zipCode, $games_played, $time_on_court, $points_scored, $number_of_assists, $number_of_rebounds);
      $stmt->execute();
      $stmt->store_result();
      $num_rows = $stmt->num_rows;

      while($stmt->fetch()) { 

        $address = new Address($id, $name_First, $name_Last, $street, $city, $state, $zipCode, $country);

        $addressList[] = $address;
        $stat = new PlayerStatistic($id, $address->getName(), $time_on_court, $points_scored, $number_of_assists, $number_of_rebounds, $games_played);
        $statList[] = $stat;
      }
        
      $db->close();
?>

    <table style="width: 100%; border:0px solid black; border-collapse:collapse;">
      <tr>
        <th style="width: 40%;">Name and Address</th>
        <th style="width: 60%;">Statistics</th>
      </tr>
      <tr>
        <td style="vertical-align:top; border:1px solid black;">
          <!-- FORM to enter Name and Address -->
          <form action="processAddressUpdate.php" method="post">
            <table style="margin: 0px auto; border: 0px; border-collapse:separate;">
              <tr>
                <td style="text-align: right; background: lightblue;">First Name</td>
                <td><input type="text" name="firstName" value="" size="35" maxlength="250"/></td>
              </tr>

              <tr>
                <td style="text-align: right; background: lightblue;">Last Name</td>
               <td><input type="text" name="lastName" value="" size="35" maxlength="250"/></td>
              </tr>

              <tr>
                <td style="text-align: right; background: lightblue;">Street</td>
               <td><input type="text" name="street" value="" size="35" maxlength="250"/></td>
              </tr>

              <tr>
                <td style="text-align: right; background: lightblue;">City</td>
                <td><input type="text" name="city" value="" size="35" maxlength="250"/></td>
              </tr>

              <tr>
                <td style="text-align: right; background: lightblue;">State</td>
                <td><input type="text" name="state" value="" size="35" maxlength="100"/></td>
              </tr>

              <tr>
                <td style="text-align: right; background: lightblue;">Country</td>
                <td><input type="text" name="country" value="" size="20" maxlength="250"/></td>
              </tr>

              <tr>
                <td style="text-align: right; background: lightblue;">Zip</td>
                <td><input type="text" name="zipCode" value="" size="10" maxlength="10"/></td>
              </tr>

              <tr>
               <td colspan="2" style="text-align: center;"><input type="submit" value="Add Name and Address" /></td>
              </tr>
            </table>
          </form>
        </td>

        <td style="vertical-align:top; border:1px solid black;">
          <!-- FORM to enter game statistics for a particular player -->
          <form action="processStatisticUpdate.php" method="post">
            <table style="margin: 0px auto; border: 0px; border-collapse:separate;">
              <tr>
                <td style="text-align: right; background: lightblue;">Name (Last, First)</td>
<!--            <td><input type="text" name="name" value="" size="50" maxlength="500"/></td>  -->
                <td><select name="name_ID" required>
                  <option value="" selected disabled hidden>Choose player's name here</option>
                  <?php
                    // for each row of data returned,
                    //   construct an Address object providing first and last name
                    //   emit an option for the pull down list such that
                    //     the displayed name is retrieved from the Address object
                    //     the value submitted is the unique ID for that player
                    // for example:
                    //     <option value="101">Duck, Daisy</option>

                    $len = count($addressList);

                    for ($i=0; $i < $len; ++$i ) {
                      $item = $addressList[$i];
                      echo '<option value="' . $item->getId() . '">' . $item->getName() . '</option>';
                    }
                  ?>
                </select></td>
              </tr>

              <tr>
                <td style="text-align: right; background: lightblue;">Playing Time (min:sec)</td>
               <td><input type="text" name="time" value="" size="5" maxlength="5"/></td>
              </tr>

              <tr>
                <td style="text-align: right; background: lightblue;">Points Scored</td>
               <td><input type="text" name="points" value="" size="3" maxlength="3"/></td>
              </tr>

              <tr>
                <td style="text-align: right; background: lightblue;">Assists</td>
                <td><input type="text" name="assists" value="" size="2" maxlength="2"/></td>
              </tr>

              <tr>
                <td style="text-align: right; background: lightblue;">Rebounds</td>
                <td><input type="text" name="rebounds" value="" size="2" maxlength="2"/></td>
              </tr>

              <tr>
               <td colspan="2" style="text-align: center;"><input type="submit" value="Add Statistic" /></td>
              </tr>
            </table>
          </form>
        </td>
      </tr>
    </table>


    <h2 style="text-align:center">Player Statistics</h2>

    <?php
      // emit the number of rows (records) in the table
      echo 'Number of Records: ' . $num_rows;
    ?>

    <table style="border:1px solid black; border-collapse:collapse;">
      <tr>
        <th colspan="1" style="vertical-align:top; border:1px solid black; background: lightgreen;"></th>
        <th colspan="2" style="vertical-align:top; border:1px solid black; background: lightgreen;">Player</th>
        <th colspan="1" style="vertical-align:top; border:1px solid black; background: lightgreen;"></th>
        <th colspan="4" style="vertical-align:top; border:1px solid black; background: lightgreen;">Statistic Averages</th>
      </tr>
      <tr>
        <th style="vertical-align:top; border:1px solid black; background: lightgreen;"></th>
        <th style="vertical-align:top; border:1px solid black; background: lightgreen;">Name</th>
        <th style="vertical-align:top; border:1px solid black; background: lightgreen;">Address</th>

        <th style="vertical-align:top; border:1px solid black; background: lightgreen;">Games Played</th>
        <th style="vertical-align:top; border:1px solid black; background: lightgreen;">Time on Court</th>
        <th style="vertical-align:top; border:1px solid black; background: lightgreen;">Points Scored</th>
        <th style="vertical-align:top; border:1px solid black; background: lightgreen;">Number of Assists</th>
        <th style="vertical-align:top; border:1px solid black; background: lightgreen;">Number of Rebounds</th>
      </tr>
      <?php
        // for each row (record) of data retrieved from the database emit the html to populate a row in the table
        // for example:
        //  <tr>
        //    <td  style="vertical-align:top; border:1px solid black;">1</td>
        //    <td  style="vertical-align:top; border:1px solid black;">Dog, Pluto</td>
        //    <td  style="vertical-align:top; border:1px solid black;">1313 S. Harbor Blvd.<br/>Anaheim, CA 92808-3232<br/>USA</td>
        //    <td  style="vertical-align:top; border:1px solid black;">1</td>
        //    <td  style="vertical-align:top; border:1px solid black;">10:0</td>
        //    <td  style="vertical-align:top; border:1px solid black;">18</td>
        //    <td  style="vertical-align:top; border:1px solid black;">2</td>
        //    <td  style="vertical-align:top; border:1px solid black;">4</td>
        //  </tr>
        // or if there exists no statistical data for the player
        //  <tr>
        //    <td  style="vertical-align:top; border:1px solid black;">2</td>
        //    <td  style="vertical-align:top; border:1px solid black;">Duck, Daisy</td>
        //    <td  style="vertical-align:top; border:1px solid black;">1180 Seven Seas Dr.<br/>Lake Buena Vista, FL 32830<br/>USA</td>
        //    <td  style="vertical-align:top; border:1px solid black;">0</td>
        //    <td  style="border:1px solid black; border-collapse:collapse; background: #e6e6e6;"></td>
        //    <td  style="border:1px solid black; border-collapse:collapse; background: #e6e6e6;"></td>
        //    <td  style="border:1px solid black; border-collapse:collapse; background: #e6e6e6;"></td>
        //    <td  style="border:1px solid black; border-collapse:collapse; background: #e6e6e6;"></td>
        //  </tr>
        //
          // construct Address and PlayerStatistic objects supplying as constructor parameters the retrieved database columns

          // Emit table row data using appropriate getters from the Address and PlayerStatistic objects

          $len = count($statList);

          for ($i=0; $i < $len; ++$i ) {
            $item = $statList[$i];
            $address = Address::getAddressItem($item->getPlayerId(), $addressList);

            if ($item->hasStat()) {
              echo '
               <tr>
                 <td  style="vertical-align:top; border:1px solid black;">' . (string)($i+1) . '</td>
                 <td  style="vertical-align:top; border:1px solid black;">' . $item->name() . '</td>
                 <td  style="vertical-align:top; border:1px solid black;">' . $address->getAddress() . '</td>
                 <td  style="vertical-align:top; border:1px solid black;">' . $item->getGamesPlayed() . '</td>
                 <td  style="vertical-align:top; border:1px solid black;">' . $item->playingTime() . '</td>
                 <td  style="vertical-align:top; border:1px solid black;">' . $item->pointsScored() . '</td>
                 <td  style="vertical-align:top; border:1px solid black;">' . $item->assists() . '</td>
                 <td  style="vertical-align:top; border:1px solid black;">' . $item->rebounds() . '</td>
               </tr>
              ';
            } 
            else {
              echo '
               <tr>
                 <td  style="vertical-align:top; border:1px solid black;">' . (string)($i+1) . '</td>
                 <td  style="vertical-align:top; border:1px solid black;">' . $item->name() . '</td>
                 <td  style="vertical-align:top; border:1px solid black;">' . $address->getAddress() . '</td>
                 <td  style="vertical-align:top; border:1px solid black;">' . $item->getGamesPlayed() . '</td>
                 <td  style="border:1px solid black; border-collapse:collapse; background: #e6e6e6;"></td>
                 <td  style="border:1px solid black; border-collapse:collapse; background: #e6e6e6;"></td>
                 <td  style="border:1px solid black; border-collapse:collapse; background: #e6e6e6;"></td>
                 <td  style="border:1px solid black; border-collapse:collapse; background: #e6e6e6;"></td>
               </tr>
              ';
            }
          }
      ?>
    </table>

  </body>
</html>
