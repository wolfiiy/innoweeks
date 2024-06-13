<?php
require_once 'connect.php';
require_once 'helper.php';
require_once 'session-check.php';

// Handle requests
if (isset($_GET['action'])) {
    if ($_GET['action'] == 'editUsername') {
        editUsername($conn);
    }

    if ($_GET['action'] == 'editEmail') {
        editEmail($conn);
    }

    if ($_GET['action'] == 'editPassword') {
        editPassword($conn);
    }

    if ($_GET['action'] == 'editAge') {
        editAge($conn);
    }
}

/**
 * Lets the user edit their username.
 * @param PDO $conn Connection to the database.
 * @throws PDOException If an error occurred executing the query.
 */
function editUsername($conn) {
    $accUsername = $_SESSION['username'];
    if ($accUsername === "admin") {
        header('Location: ' . $_SERVER['HTTP_REFERER'] 
                            . "?error=admin_is_readonly");
        exit();
    }

    $idAccount = Helper::getAccountId($conn, $accUsername);
    $newUsername = $_POST['username'];

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
 * @throws PDOException if an error occurred executing the query.
 */
function editAge($conn) {
    $accUsername = $_SESSION['username'];
    $idAccount = Helper::getAccountId($conn, $accUsername);
    $newAge = $_POST['age'];

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
 * @throws PDOException if an error occurred executing the query.
 */
function editEmail($conn) {
    $accUsername = $_SESSION['username'];
    $idAccount = Helper::getAccountId($conn, $accUsername);
    $newEmail = $_POST['email'];

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