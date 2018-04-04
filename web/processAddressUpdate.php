<?php

require('Address.php');

$id = 0;
$firstName = $_POST['firstName'];
$lastName = $_POST['lastName'];
$street = $_POST['street'];
$city = $_POST['city'];
$state = $_POST['state'];
$zipCode = $_POST['zipCode'];
$country = $_POST['country'];

$address = new Address($id, $firstName, $lastName, $street, $city, $state, $zipCode, $country);

if( ! empty($firstName) )
{
  //file_put_contents('../data/address.txt', $address->toTSV()."\n", FILE_APPEND | LOCK_EX);
  $query = 'INSERT INTO TeamRoster(Name_First, Name_Last, Street, City, State, Country, ZipCode)' . ' VALUES(?, ?, ?, ?, ?, ?, ?);';
  
  $db = new mysqli('127.0.0.1', 'hw3user', 'password', 'hw3');
  
  $stmt = $db->prepare($query); 
  $stmt->bind_param('sssssss', $firstName, $lastName, $street, $city, $state, $country, $zipCode); 
  $stmt->execute();

  $db->close();  
}


require('home_page.php');
?>