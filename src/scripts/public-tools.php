<?php
require_once 'connect.php';

// Handle requests
if (isset($_GET['action'])) {
    if ($_GET['action'] == 'createAccount') {
        createAccount($conn);
    }
}

/**
 * Lets the user create a new account.
 * @param PDO $conn Connection to the database.
 * @throws PDOException If an error occurred while executing the query.
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
    
    header("Location: ../html/signin.php");
}
?>