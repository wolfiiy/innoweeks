<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $passwordConfirm = $_POST['password-confirm'];
    $age = $_POST['age'];

    // TODO error message / go back
    if ($password != $passwordConfirm)
        return;
    
    try {
        $sql = "INSERT INTO t_Account (email, username, password, age) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$nom, $prenom, $email, $telephone, $message]);
        echo "Formulaire ajouté avec succès";
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }

    $conn = null;
}

header("Location: index.php");
exit();

?>