<?php
include 'settings.php';

/*$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

if ($conn -> connect_error) {
    die ("Connection failed. " . $conn -> connect_error);
}*/

// TODO complete PDO transition
try {
    $conn = new PDO(DB_DSN, DB_USER, DB_PASSWORD);
    $conn -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    error_log("Connection to the database successfull.");
} catch (PDOException $e) {
    die("Connection failed. " . $e -> getMessage());
}
?>