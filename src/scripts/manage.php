<?php
include 'connect.php';

/**
 * Adds a new account to the database.
 */
function createAccount() {

}

/**
 * Adds a new task to the database.
 */
function createTask($conn) {
    // Get input data iff POST request
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = $_POST['task-name'];
        $description = $_POST['task-description'];
        $score = $_POST['task-score'];
    
        // Insert data into database
        try {
            $sql = "INSERT INTO t_Task (tasName, tasDescription, tasScore)
                    VALUES (?, ?, ?)";
            $stmt = $conn -> prepare($sql);
            $stmt -> execute([$name, $description, $score]);
            error_log("Task successfully created.");
        } catch (Exception $e) {
            error_log("Task could not be created." . $e -> getMessage());
            header("Location: ../html/admin.php?error=db_error");
        }
    
        $conn -> close();
        header("Location: ../html/admin.php");
    }
}

// Handle calling <a> tags
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['action'])) {
    if ($_GET['action'] == 'createAccount') {
        createAccount();
    }
    
    if ($_GET['action'] == 'createTask') {
        createTask();
    }
}

// Handle POST request to createTask
if (isset($_GET['action']) 
    && $_GET['action'] == 'createTask') {
    createTask($conn);
}
?>