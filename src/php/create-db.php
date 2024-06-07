<?php
include "connect.php";

$sql = "CREATE DATABASE " . $databaseName;

// Execute the query and check for success
echo "Attempting to create database...";
if ($conn -> query($sql) === TRUE) {
    echo "Database created successfully.";
} else {
    echo "Error creating database: " . $conn -> error;
}

$conn -> close();
?>