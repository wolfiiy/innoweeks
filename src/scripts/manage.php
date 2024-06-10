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

    if ($_GET['action'] == 'completeTask') {
        $id = $_GET['id'];
        completeTask($conn, $id);
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
 * Removes an account from the database.
 */
function removeAccount($id, $conn) {
    try {
        $sql = "DELETE FROM t_Account WHERE id=?";
        $stmt = $conn -> prepare($sql);
        $stmt -> execute([$id]);

        error_log("Account successfully removed.");
        $conn -> close();
        header("Location: ../html/admin.php");
        exit();
    } catch (Exception $e) {
        error_log("Could not remove account. " . $e -> getMessage());
    }

    $conn -> close();
    header("Location: ../html/admin.php");
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

/**
 * Marks a task as completed.
 * @param mysqli $conn Connection to the database.
 * @param int $id ID of the task.
 */
function completeTask($conn, $id) {
    try {
        $sql = "SELECT tasScore FROM t_Task WHERE idTask = ?";
        $stmt = $conn -> prepare($sql);
        $stmt -> bind_param("i", $id);
        $stmt -> execute();
        $stmt -> bind_result($tasScore);
        $stmt -> fetch();
        $stmt -> close();

        echo $tasScore;
    } catch (Exception $e) {
        error_log("Could not set the task as complete. " . $e -> getMessage());
    }
}
?>