<?php
include 'connect.php';

// Handle requests
if (isset($_GET['action'])) {
    if ($_GET['action'] == 'createTask') {
        createTask($conn);
    }

    if ($_GET['action'] == 'createAccount') {
        createAccount($conn);
    }
}

/**
 * Adds a new account to the database.
 */
function createAccount($conn) {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Form input
        $email = $_POST['email'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        $passwordConfirm = $_POST['password-confirm'];
        $age = $_POST['age'];
    
        // Stop if passwords do not match
        if ($password != $passwordConfirm) {
            header("Location: ../html/create-account.html?error=password_mismatch");
            $conn -> close();
            exit();
        }
        
        try {
            // Hash password before saving to database
            $password = password_hash($password, PASSWORD_BCRYPT);
            
            // Send to database
            $sql = "INSERT INTO t_Account (accEmail, accUsername, accPassword, accAge) 
                    VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$email, $username, $password, $age]);
            error_log("Account successfully created.");
        } catch (Exception $e) {
            error_log("Account could not be created." . $e -> getMessage());
            header("Location: ../html/create-account.html?error=db_error");
        }
    }
    
    $conn -> close();
    header("Location: ../html/signin.html");
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
?>