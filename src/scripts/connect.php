<?php
require 'settings.php';

try {
    $conn = new PDO(DB_DSN, DB_USER, DB_PASSWORD);
    $conn -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    error_log("Connection to the database successfull.");
} catch (PDOException $e) {
    die("Connection failed. " . $e -> getMessage());
}
?>