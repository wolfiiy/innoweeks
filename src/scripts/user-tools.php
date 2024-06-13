<?php
require_once 'connect.php';
require_once 'helper.php';
require_once 'session-check.php';

// Handle requests
if (isset($_GET['action'])) {
    if ($_GET['action'] == 'completeTask') {
        $id = $_GET['id'];
        completeTask($conn, $id);
    }

    if ($_GET['action'] == 'edit') {
        edit($conn);
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

/**
 * Lets the user edit their account data.
 */
function edit(PDO $conn) {
    $accUsername = $_SESSION['username'];
    $idAccount = Helper::getAccountId($conn, $accUsername);

    $newUsername = $_POST['username'] ?? null;
    $newEmail = $_POST['email'] ?? null;
    $newAge = $_POST['age'] ?? null;
    $newPassword = $_POST['password'] ?? null;
    $confirmPassword = $_POST['password-confirm'] ?? null;

    try {
        // Update username if provided
        if ($newUsername) {
            if ($accUsername === "admin") {
                header('Location: ' . $_SERVER['HTTP_REFERER'] 
                                    . "?error=admin_is_readonly");
                exit();
            }

            editUsername($conn, $idAccount, $newUsername);
        }

        // Update email if provided
        if ($newEmail) {
            editEmail($conn, $idAccount, $newEmail);
        }

        // Update age if provided
        if ($newAge) {
            editAge($conn, $idAccount, $newAge);
        }

        // Update password if provided and confirmed
        if ($newPassword && $newPassword === $confirmPassword) {
            editPassword($conn, $idAccount, $newPassword);
        }

        // Redirect back with success message
        header('Location: ../html/my.php?success=1');
        exit();
    } catch (PDOException $e) {
        error_log("An error occurred: " . $e->getMessage());
        header('Location: ../html/my.php?error=db_error');
        exit();
    }
}

/**
 * Changes the account's username.
 * @param PDO $conn Connection to the database.
 * @param int $idAccount ID of the account.
 * @param string $newUsername New username.
 */
function editUsername(PDO $conn, int $idAccount, string $newUsername) {
    try {
        $sql = "UPDATE t_Account
                SET accUsername = ?
                WHERE idAccount = ?";
        $stmt = $conn -> prepare($sql);
        $stmt -> execute([$newUsername, $idAccount]);
        error_log("Username of $idAccount updated.");

        $_SESSION['username'] = $newUsername;
        error_log("Username of session updated.");
    } catch (PDOException $e) {
        throwDbError();
    }

    header('Location: ' . $_SERVER['HTTP_REFERER']);
}

/**
 * Lets the user edit their age.
 * @param PDO $conn Connection to the database.
 * @param int $idAccount ID of the account.
 * @param int $newAge New age.
 */
function editAge(PDO $conn, int $idAccount, int $newAge) {
    try {
        $sql = "UPDATE t_Account
                SET accAge = ?
                WHERE idAccount = ?";
        $stmt = $conn -> prepare($sql);
        $stmt -> execute([$newAge, $idAccount]);
        error_log("Age of $idAccount updated.");
    } catch (PDOException $e) {
        throwDbError();
    }

    header('Location: ' . $_SERVER['HTTP_REFERER']);
}

/**
 * Lets the user edit their email address.
 * @param PDO $conn Connection to the database.
 * @param int $idAccount ID of the account.
 * @param string $newEmail New email address.
 */
function editEmail(PDO $conn, int $idAccount, string $newEmail) {
    try {
        $sql = "UPDATE t_Account
                SET accEmail = ?
                WHERE idAccount = ?";
        $stmt = $conn -> prepare($sql);
        $stmt -> execute([$newEmail, $idAccount]);
        error_log("Email address of $idAccount updated.");
    } catch (PDOException $e) {
        throwDbError();
    }

    header('Location: ' . $_SERVER['HTTP_REFERER']);
}

/**
 * Lets the user edit their email address.
 * @param PDO $conn Connection to the database.
 * @param int $idAccount ID of the account.
 * @param string $newPassword New password.
 * @throws PDOException if an error occurred executing the query.
 */
function editPassword(PDO $conn, int $idAccount, string $newPassword) {
    try {
        $newPassword = password_hash($newPassword, PASSWORD_BCRYPT);
        $sql = "UPDATE t_Account
                SET accPassword = ?
                WHERE idAccount = ?";
        $stmt = $conn -> prepare($sql);
        $stmt -> execute([$newPassword, $idAccount]);
        error_log("Password of $idAccount updated.");
    } catch (PDOException $e) {
        throwDbError();
    }

    header('Location: ' . $_SERVER['HTTP_REFERER']);
}

/**
 * Gets the account's current user age.
 */
function getAccountAge($conn) {
    $username = $_SESSION['username'];
    $email = Helper::getAccountAge($conn, $username);
    echo $email;
}

/**
 * Gets the account's current email address.
 */
function getAccountEmail($conn) {
    $username = $_SESSION['username'];
    $email = Helper::getAccountEmail($conn, $username);
    echo $email;
}

/**
 * Informs the user that an error occurred while executing a query.
 */
function throwDbError() {
    error_log("An error occurred. " . $e -> getMessage());
    header("Location: ../html/my.php?error=db_error");
    exit();
}
?>