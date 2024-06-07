<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $telephone = $_POST['telephone'];
    $message = $_POST['message'];

    try {
        $sql = "UPDATE formulaires SET nom=?, prenom=?, email=?, telephone=?, message=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$nom, $prenom, $email, $telephone, $message, $id]);
        echo "Formulaire mis à jour avec succès";
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }

    $conn = null;

    header("Location: index.php");
    exit();
} else {
    $id = $_GET['id'];
    try {
        $sql = "SELECT * FROM formulaires WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
        exit();
    }
}
?>