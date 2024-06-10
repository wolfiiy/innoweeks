<?php

$databaseHost = "localhost";
$databaseUsername = "root";
$databasePassword = "";
$databaseName = "httesting";
$sql = "CREATE DATABASE " . $databaseName;
$conn = new mysqli($databaseHost, $databaseUsername, $databasePassword);

// Check connection
if ($conn -> connect_error) {
    die("Connection failed: " . $conn -> connect_error);
}

// Attempt database creation
if ($conn->query($sql) === TRUE) {
    echo "Database created successfully";
} else {
    echo "Error creating database: " . $conn->error;
}

header("Location: ../html/admin.php");
$conn -> close();
?>