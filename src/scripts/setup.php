<?php
include 'settings.php';

/**
 * Creates a database using settings found in the 'settings.php' file.
 */
function createDatabase() {
    $conn = new PDO(DB_DSN, DB_USER, DB_PASSWORD);
    $sql = "CREATE DATABASE " . DB_NAME;

    // Check connection
    if ($conn -> connect_error) {
        die("Connection failed: " . $conn -> connect_error);
    }

    // Create database
    if ($conn -> query($sql) === TRUE) {
        error_log("Database successfully created.");
    } else {
        error_log("Database could not be created. " . $conn -> error);
    }

    // Go back to the admin panel
    header("Location: " . $_SERVER["HTTP_REFERER"]);
}

/**
 * Creates the required tables for the habit tracker web app database.
 */
function createTables() {
    $sqlFile = "../sql/create-tables.sql";
    
    try {
        // Read SQL script from file
        $sql = file_get_contents($sqlFile);
        if ($sql === false) {
            throw new Exception("Failed to read SQL script.");
        }

        // Connect to database
        $conn = new PDO(DB_DSN, DB_USER, DB_PASSWORD);
        if ($conn -> connect_error) {
            die ("Connection failed. " . $conn -> connect_error);
        }

        // Create tables
        $conn -> exec($sql);

        error_log("Tables successfully created.");
    } catch (Exception $e) {
        error_log("Failed to create tables. " . $e -> getMessage());
    }

    header("Location: " . $_SERVER["HTTP_REFERER"]);
}

// Handle calling <a> tags
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['action'])) {
    if ($_GET['action'] == 'createDatabase') {
        createDatabase();
    }
    
    if ($_GET['action'] == 'createTables') {
        createTables();
    }
}
?>