<?php
include 'connect.php';

/**
 * Displays a table that contains all users found in the database.
 * @param mysqli $conn Connection to the database.
 */
function displayAccounts($conn) {
    try {
        $stmt = getAccounts($conn);
    
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
}

/**
 * Gets the ID, username and email of all users of the habit tracker web app.
 * @param mysqli $conn Connection to the database. 
 * @return query Details of all accounts.
 */
function getAccounts($conn) {
    $sql = "SELECT idAccount, accUsername, accEmail FROM t_Account";
    $result = $conn -> query($sql);
    return $result;
}

?>