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
 * @param PDO $conn Connection to the database.
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
            exit();
        }
        
        try {
            // Hash password before saving to database
            $password = password_hash($password, PASSWORD_BCRYPT);
            
            // Send to database
            $sql = "INSERT INTO t_Account (accEmail, accUsername, accPassword, accAge) 
                    VALUES (?, ?, ?, ?)";
            $stmt = $conn -> prepare($sql);
            $stmt -> execute([$email, $username, $password, $age]);
            error_log("Account successfully created.");
        } catch (Exception $e) {
            error_log("Account could not be created." . $e -> getMessage());
            header("Location: ../html/create-account.html?error=db_error");
            exit();
        }
    }
    
    header("Location: ../html/signin.html");
    exit();
}

/**
 * Removes an account from the database.
 * @param int $id ID of the account to remove.
 * @param PDO $conn Connection to the database.
 */
function removeAccount($id, $conn) {
    try {
        $sql = "DELETE FROM t_Account WHERE idAccount = ?";
        $stmt = $conn -> prepare($sql);
        $stmt -> execute([$id]);

        error_log("Account successfully removed.");
        header("Location: ../html/admin.php");
        exit();
    } catch (PDOException $e) {
        error_log("Could not remove account. " . $e -> getMessage());
    }

    header("Location: ../html/admin.php");
}

/**
 * Adds a new task to the database.
 * @param PDO $conn Connection to the database.
 */
function createTask($conn) {
    // Get input data iff POST request
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = $_POST['task-name'];
        $description = $_POST['task-description'];
        $score = $_POST['task-score'];
        $state = 0;
    
        // Insert data into database
        try {
            $sql = "INSERT INTO t_Task (tasName, tasDescription, tasScore, tasState)
                    VALUES (?, ?, ?, ?)";
            $stmt = $conn -> prepare($sql);
            $stmt -> execute([$name, $description, $score, $state]);
            error_log("Task successfully created.");
        } catch (PDOException $e) {
            error_log("Task could not be created." . $e -> getMessage());
            header("Location: ../html/admin.php?error=db_error");
            exit();
        }
    
        header("Location: ../html/admin.php");
        exit();
    }
}

/**
 * Marks a task as completed.
 * @param mysqli $conn Connection to the database.
 * @param int $id ID of the task.
 */
function completeTask($conn, $id) {
    $score = getTaskScore($conn, $id);

    try {
        // TODO

        // Set task as completed
        $sqlTask = "UPDATE t_Task SET tasState = 1 WHERE idTask = $id";
        $sqlUser = "UPDATE t_Account SET accScore = accScore + $tasScore WHERE idAccount = $isAccount";


    } catch (Exception $e) {
        error_log("An error occurred. " . $e -> getMessage());
    }



    echo getTaskScore($conn, $id);
}

/**
 * Gets the score value of a task.
 * @param mysqli $conn Connection to the database.
 * @param int $id ID of the task.
 * @return The score value of the task or -1 if the task could not be found.
 */
function getTaskScore($conn, $id) {
    $tasScore = -1;

    try {
        $sql = "SELECT tasScore FROM t_Task WHERE idTask = ?";
        $stmt = $conn -> prepare($sql);
        $stmt -> execute([$id]);
        $stmt -> setFetchMode(PDO::FETCH_ASSOC);

        // Fetch result
        $result = $stmt -> fetch();
        if ($result) {
            $tasScore = $result['tasScore'];
        }
    } catch (PDOException $e) {
        error_log("An error occured. " . $e -> getMessage());
    }

    return $tasScore;
}
?>