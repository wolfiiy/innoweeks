<?php
require 'connect.php';
require 'session-check.php';

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
 * @param PDO $conn Connection to the database.
 * @param int $id ID of the task.
 */
function completeTask($conn, $id) {
    $tasScore = getTaskScore($conn, $id);

    try {
        if (!isTaskAvailable($conn, $id)) {
            error_log("Task is already completed.");
            header("Location: ../html/admin.php?error=task_already_completed");
            return;
        }

        // Because of this, usernames are required to be unique
        // Session already started in manage.php
        $accUsername = $_SESSION['username'];
        $idAccount = getAccountByUsername($conn, $accUsername);
        
        if ($idAccount === false) {
            error_log("The username \"$accUsername\" does not exists.");
            return;
        }

        // Mark task as completed
        $sqlTask = "UPDATE t_Task 
                    SET tasState = 1 
                    WHERE idTask = ?";

        $stmt = $conn -> prepare($sqlTask);
        $stmt -> execute([$id]);

        // Add score to user account
        $sqlUser = "UPDATE t_Account 
                    SET accScore = ? 
                    WHERE idAccount = ?";

        $score = getScoreByAccountId($conn, $idAccount);
        $score += $tasScore;
        $stmt = $conn -> prepare($sqlUser);
        $stmt -> execute([$score, $idAccount]);

        error_log("Task $id completed by $accUsername.");
        header("Location: ../html/admin.php");
    } catch (PDOException $e) {
        error_log("An error occurred. " . $e -> getMessage());
        header("Location: ../html/admin.php?error=task_error");
        exit();
    }
}

/**
 * Gets the score value of a task.
 * @param PDO $conn Connection to the database.
 * @param int $id ID of the task.
 * @return int The score value of the task or -1 if the it could not be found.
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

/**
 * Gets the account ID from the username.
 * @param PDO $conn Connection to the database.
 * @param string $username Username for which to fetch the account ID.
 * @return int|false The account ID if found, false otherwise.
 */
function getAccountByUsername($conn, $username) {
    try {
        $sql = "SELECT idAccount FROM t_Account WHERE accUsername = ?";
        $stmt = $conn -> prepare($sql);
        $stmt -> execute([$username]);

        $result = $stmt -> fetch(PDO::FETCH_ASSOC);

        if ($result) {
            return $result['idAccount'];
        } else {
            return false;
        }

    } catch (PDOException $e) {
        error_log("An error occurred. " . $e -> getMessage());
        return false;
    }
}

/**
 * Gets the current score of a given user.
 * @param PDO $conn Connection to the database.
 * @param int $idAccount ID of the account.
 * @return int|false The score if found, false otherwise.
 */
function getScoreByAccountId($conn, $idAccount) {
    try {
        $sql = "SELECT accScore FROM t_Account WHERE idAccount = ?";
        $stmt = $conn -> prepare($sql);
        $stmt -> execute([$idAccount]);
        
        $result = $stmt -> fetch(PDO::FETCH_ASSOC);

        if ($result) {
            return $result['accScore'];
        } else {
            return false;
        }
    } catch (PDOException $e) {
        error_log("An error occurred. " . $e -> getMessage());
        return false;
    }
}

/**
 * Returns the state of a task.
 * @param PDO $conn Connection to the database.
 * @param int $idTask ID of the task.
 */
function isTaskAvailable($conn, $idTask) {
    try {
        $sql = "SELECT tasState FROM t_Task WHERE idTask = ?";
        $stmt = $conn -> prepare($sql);
        $stmt -> execute([$idTask]);
        $result = $stmt -> fetch(PDO::FETCH_ASSOC);

        if ($result) {
            return $result['tasState'] > 0 ? false : true;
        } else {
            return false;
        }
    } catch (PDOException $e) {
        error_log("An error occurred. " . $e -> getMessage());
        return false;
    }
}
?>