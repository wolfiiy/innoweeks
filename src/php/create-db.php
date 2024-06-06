<?php
$dbPath = '../../db/aaa.db';

$server = "localhost";
$username = "root";
$password = "";
$database = "aaa";

$conn = new mysqli($server, $username, $password);

if ($conn -> connect_error) {
    die ("Connection failed: " . $conn -> connect_error);
}

// Create database
$sql = "CREATE DATABASE " . $database;

// Execute the query and check for success
if ($conn -> query($sql) === TRUE) {
    echo "Database created successfully.";
} else {
    echo "Error creating database: " . $conn -> error;
}

$conn -> close();
?>