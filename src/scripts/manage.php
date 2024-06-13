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