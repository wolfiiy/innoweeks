<?php
require 'connect.php';
require 'session-check.php';

// Handle requests
if (isset($_GET['action'])) {
    if ($_GET['action'] == 'completeTask') {
        $id = $_GET['id'];
        completeTaskForAccount($conn, $id);
    }

    if ($_GET['action'] == 'createAccount') {
        createAccount($conn);
    }
}

/**
 * Lets the user create a new account.
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
 * Marks a task as completed.
 * @param PDO $conn Connection to the database.
 * @param int $idTask ID of the task.
 */
function completeTaskForAccount($conn, $idTask) {
    // Get task value
    $tasScore = getTaskScore($conn, $idTask);

    try {
        // Because of this, usernames are required to be unique
        // Session already started in manage.php
        $accUsername = $_SESSION['username'];
        $idAccount = getAccountByUsername($conn, $accUsername);
        
        // Do nothing if account could not be found
        if ($idAccount === false) {
            error_log("The username \"$accUsername\" does not exists.");
            return;
        }

        // Do nothing if task was already completed
        if (hasAccountCompletedTask($conn, $idTask, $idAccount)) {
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

        $score = getScoreByAccountId($conn, $idAccount);
        $score += $tasScore;
        $stmt = $conn -> prepare($sqlUser);
        $stmt -> execute([$score, $idAccount]);

        error_log("Task $idTask completed by $accUsername.");
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
function isTaskDone($conn, $idTask) {
    try {
        $sql = "SELECT tasState FROM t_Task WHERE idTask = ?";
        $stmt = $conn -> prepare($sql);
        $stmt -> execute([$idTask]);
        $result = $stmt -> fetch(PDO::FETCH_ASSOC);

        if ($result) {
            return $result['tasState'] === 0 ? false : true;
        } else {
            return false;
        }
    } catch (PDOException $e) {
        error_log("An error occurred. " . $e -> getMessage());
        return false;
    }
}

/**
 * Given an account, returns the state of a task.
 * @param PDO $conn Connection to the database.
 * @param int $idTask ID of the task.
 * @param int $idAccount ID of the account.
 */
function hasAccountCompletedTask($conn, $idTask, $idAccount) {
    try {
        $sql = "SELECT comState FROM Complete WHERE idAccount = ? AND idTask = ?";
        $stmt = $conn -> prepare($sql);
        $stmt -> execute([$idAccount, $idTask]);
        $result = $stmt -> fetch(PDO::FETCH_ASSOC);

        if ($result) {
            return $result['comState'] === 1 ? true : false;
        } else {
            return false;
        }
    } catch (PDOException $e) {
        error_log("An error occurred. " . $e -> getMessage());
        return false;
    }
}

function hasLoggedAccountCompletedTask($conn, $idTask) {
    $accUsername = $_SESSION['username'];
    $idAccount = getAccountByUsername($accUsername);
    return hasAccountCompletedTask($conn, $idTask, $idAccount);
}
?>