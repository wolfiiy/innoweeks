<?php
include 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $passwordConfirm = $_POST['password-confirm'];
    $age = $_POST['age'];

    error_log("Attempting to add new account...");

    // TODO js
    if ($password != $passwordConfirm) {
        header("Location: ../html/create-account.html?error=password_mismatch");
        exit();
    }
    
    try {
        // TODO
        $password = password_hash($password);
        $sql = "INSERT INTO t_Account (accEmail, accUsername, accPassword, accAge) 
                VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$email, $username, $password, $age]);
        error_log("Account successfully created.");
    } catch (Exception $e) {
        throw new Exception("Account could not be created." . $e -> getMessage());
        //header("Location: ../html/create-account.html?error=db_error");
        exit();
    }

    $conn = null;
}

header("Location: ../html/signin.html");
exit();

?>