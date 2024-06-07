<?php
// Connection settings
$databaseHost = "localhost";
$databaseUsername = "root";
$databasePassword = "";
$databaseName = "aaa";
   
// Connection to the database
$conn = new mysqli($databaseHost, 
                   $databaseUsername, 
                   $databasePassword, 
                   $databaseName);
 
if ($conn -> connect_errno) {
    die ("Connection failed: " . $conn -> connect_error);
}
?>