<?php
include 'connect.php';

session_start();

error_log("Attempting to log in...");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        error_log("Username or password is empty!");
        exit();
    }

    $sql = "SELECT accPassword FROM t_Account WHERE accUsername = ?";
    $stmt = $conn -> prepare($sql);
    $stmt -> bind_param("s", $username);
    $stmt -> execute();
    $stmt -> bind_result($hash);
    $stmt -> fetch();
    $stmt -> close();

    if ($hash && password_verify($password, $hash)) {
        $_SESSION['username'] = $username;
        error_log("Sign in successful");
        header("Location: ../html/index.php");
        exit();
    } else {
        error_log("Username or password is invalid.");
    }

    $conn -> close();
}
?>