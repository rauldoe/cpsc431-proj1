<!DOCTYPE html>
<?php

    $id = $_POST['id'];

    // Connect to database
    $db = new mysqli('127.0.0.1', 'hw3user', 'password', 'hw3');
    //if (mysql_connect_errno())
    
    $query ="
        SELECT TOP 1 roster.name_Last
            , roster.name_First
            , roster.Street
            , roster.City
            , coalesce(roster.ZipCode, '') as ZipCode
            , roster.State
            , roster.Country
            
            FROM teamroster as roster
            WHERE ID = ?;
        ";


    $stmt = $db->prepare($query);
    $stmt->bind_param('s', $id); 

    $stmt->bind_result($name_Last, $name_First, $street, $city, $state, $country, $zipCode);
    $stmt->execute();
    $stmt->store_result();
    $num_rows = $stmt->num_rows;

    while($stmt->fetch()) { 

        $address = new Address($id, $name_First, $name_Last, $street, $city, $state, $zipCode, $country);
        break;
    }
    
    $db->close();
<html>
<head><title>Edit Address</title></head>
<body>
<form action="" method="post">
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
        echo '<td><input type="text" name="street" value="' . $address->getStreet() . '" size="35" maxlength="250"/></td>'
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
</body>
</html>
?>