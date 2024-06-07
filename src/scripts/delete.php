<?php
include 'config.php';

$id = $_GET['id'];

try {
    $sql = "DELETE FROM formulaires WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id]);
    echo "Formulaire supprimé avec succès";
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}

$conn = null;

header("Location: index.php");
exit();
?>
