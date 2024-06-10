<?php
include 'connect.php';

try {
    $sql = "SELECT idAccount, accUsername, accEmail FROM t_Account";
    $stmt = $conn -> query($sql);

    // Create HTML table
    echo "<table border='1'>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>";

    // Loop over users
    while ($row = $stmt -> fetch_assoc()) {
        echo "<tr>
                <td>" . htmlspecialchars($row["idAccount"]) . "</td>
                <td>" . htmlspecialchars($row["accUsername"]) . "</td>
                <td>" . htmlspecialchars($row["accEmail"]) . "</td>
                <td>
                    <a href='update.php?id=" . htmlspecialchars($row["idAccount"]) . "'>Edit</a> |
                    <a href='delete.php?id=" . htmlspecialchars($row["idAccount"]) . "'>Delete</a>
                </td>
            </tr>";
    }

    // Close HTML table
    echo "</table>";
} catch (Exception $e) {
    error_log("Error." . $e -> getMessage());
}

$conn = null;
?>