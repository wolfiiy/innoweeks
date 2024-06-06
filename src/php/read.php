<?php
include 'config.php';

try {
    $sql = "SELECT id, nom, prenom, email, telephone, message FROM formulaires";
    $stmt = $conn->query($sql);

    echo "<table border='1'>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Email</th>
                <th>Téléphone</th>
                <th>Message</th>
                <th>Actions</th>
            </tr>";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>
                <td>" . htmlspecialchars($row["id"]) . "</td>
                <td>" . htmlspecialchars($row["nom"]) . "</td>
                <td>" . htmlspecialchars($row["prenom"]) . "</td>
                <td>" . htmlspecialchars($row["email"]) . "</td>
                <td>" . htmlspecialchars($row["telephone"]) . "</td>
                <td>" . htmlspecialchars($row["message"]) . "</td>
                <td>
                    <a href='update.php?id=" . htmlspecialchars($row["id"]) . "'>Modifier</a> |
                    <a href='delete.php?id=" . htmlspecialchars($row["id"]) . "'>Supprimer</a>
                </td>
            </tr>";
    }
    echo "</table>";
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}

$conn = null;
?>
