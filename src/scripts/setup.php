<?php
include 'settings.php';

/**
 * Creates a database using settings found in the 'settings.php' file.
 */
function createDatabase() {
    $conn = new mysqli (DB_HOST, DB_USER, DB_PASSWORD);
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
    header("Location: ../html/admin.php");
    $conn -> close();
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
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        if ($conn -> connect_error) {
            die ("Connection failed. " . $conn -> connect_error);
        }

        // Get all queries from file
        if ($conn -> multi_query($sql)) {
            do {
                if ($result = $conn -> store_result()) {
                    $result -> free();
                }
            } while ($conn -> more_results() && $conn -> next_result());
        }

        error_log("Tables successfully created.");
    } catch (Exception $e) {
        error_log("Failed to create tables. " . $e -> getMessage());
    }

    header("Location: " . $_SERVER["HTTP_REFERER"]);
    $conn -> close();
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