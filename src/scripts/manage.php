<?php
require 'connect.php';
require 'helper.php';
require_once 'session-check.php';

// Handle requests
if (isset($_GET['action'])) {
    if ($_GET['action'] == 'completeTask') {
        $id = $_GET['id'];
        completeTask($conn, $id);
    }

    if ($_GET['action'] == 'createAccount') {
        createAccount($conn);
    }
}

/**
 * Lets the user create a new account.
 * @param PDO $conn Connection to the database.
 */
function createAccount(PDO $conn) {
    // Get input data iff POST request
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Form input
        // TODO POST contenu vardump
        $email = $_POST['email'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        $passwordConfirm = $_POST['password-confirm'];
        $age = $_POST['age'];
    
        // Stop if passwords do not match
        if ($password != $passwordConfirm) {
            header("Location: ../html/signup.php?error=password_mismatch");
            exit();
        }
        
        error_log("Attempting to create a new user account...");
        try {
            // Hash password before saving to database
            $password = password_hash($password, PASSWORD_BCRYPT);
            
            // Send to database
            $sql = "INSERT INTO t_Account (accEmail, accUsername, accPassword, accAge) 
                    VALUES (?, ?, ?, ?)";
            $stmt = $conn -> prepare($sql);
            $stmt -> execute([$email, $username, $password, $age]);
            error_log("Account successfully created.");
        } catch (PDOException $e) {
            error_log("Account could not be created." . $e -> getMessage());
            header("Location: ../html/signup.php?error=db_error");
            exit();
        }
    }
    
    header("Location: ../html/index.php");
}

/**
 * Marks a task as completed.
 * @param PDO $conn Connection to the database.
 * @param int $idTask ID of the task.
 */
function completeTask($conn, $idTask) {
    // Get task value
    $tasScore = Helper::getTaskScore($conn, $idTask);

    try {
        // Because of this, usernames are required to be unique
        // Session already started in manage.php
        $accUsername = $_SESSION['username'];
        $idAccount = Helper::getAccountId($conn, $accUsername);
        
        // Do nothing if account could not be found
        if ($idAccount === false) {
            error_log("The username \"$accUsername\" does not exists.");
            return;
        }

        // Do nothing if task was already completed
        if (Helper::getTaskState($conn, $idAccount, $idTask)) {
            error_log("Task is already completed.");
            header("Location: ../html/tasks.php?error=task_already_completed");
            return;
        }

        // Mark task as completed
        $sqlComplete = "INSERT INTO Complete (idAccount, idTask, comState)
                        VALUES (?, ?, 1)
                        ON DUPLICATE KEY UPDATE comState = 1";

        $stmt = $conn -> prepare($sqlComplete);
        $stmt -> execute([$idAccount, $idTask]);

        // Add score to user account
        $sqlUser = "UPDATE t_Account 
                    SET accScore = ? 
                    WHERE idAccount = ?";

        $score = Helper::getAccountScore($conn, $idAccount);
        $score += $tasScore;
        $stmt = $conn -> prepare($sqlUser);
        $stmt -> execute([$score, $idAccount]);

        error_log("Task $idTask completed by $accUsername.");
        header("Location: ../html/tasks.php");
    } catch (PDOException $e) {
        error_log("An error occurred. " . $e -> getMessage());
        header("Location: ../html/tasks.php?error=task_error");
        exit();
    }
}
?>