<?php
require_once 'connect.php';
require_once 'helper.php';

// Handle requests
if (isset($_GET['action'])) {
    if ($_GET['action'] == 'createTask') {
        createTaskAsAdmin($conn);
    }

    if ($_GET['action'] == 'createAccount') {
        createAccountAsAdmin($conn);
    }

    if ($_GET['action'] == 'removeAccount') {
        $idAccount = $_GET['id'];
        removeAccountAsAdmin($conn, $idAccount);
    }
}

/**
 * Adds a new account to the database.
 * @param PDO $conn Connection to the database.
 * @throws PDOException If something went wrong while handling the query.
 */
function createAccountAsAdmin(PDO $conn) {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Form input
        $email = $_POST['email'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        $passwordConfirm = $_POST['password-confirm'];
        $age = $_POST['age'];
    
        // Stop if passwords do not match
        if ($password != $passwordConfirm) {
            header("Location: ../html/create-account.html?error=password_mismatch");
            exit();
        }
        
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
            header("Location: ../html/create-account.html?error=db_error");
            exit();
        }
    }
    
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit();
}

/**
 * Adds a new task to the database.
 * @param PDO $conn Connection to the database.
 * @throws PDOException If something went wrong while handling the query.
 */
function createTaskAsAdmin(PDO $conn) {
    // Get input data iff POST request
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = $_POST['task-name'];
        $description = $_POST['task-description'];
        $score = $_POST['task-score'];
        $state = 0;
    
        // Insert data into database
        try {
            $sql = "INSERT INTO t_Task (tasName, tasDescription, tasScore, tasState)
                    VALUES (?, ?, ?, ?)";
            $stmt = $conn -> prepare($sql);
            $stmt -> execute([$name, $description, $score, $state]);
            error_log("Task successfully created.");
        } catch (PDOException $e) {
            error_log("Task could not be created." . $e -> getMessage());
            header("Location: ../html/admin.php?error=db_error");
            exit();
        }
    
        header("Location: ../html/admin.php");
        exit();
    }
}

/**
 * Removes an account and all associated data from the database.
 * @param int $id ID of the account to remove.
 * @param PDO $conn Connection to the database.
 */
function removeAccountAsAdmin(PDO $conn, $idAccount) {
    try {
        // Delete task records of the account
        $sql = "DELETE FROM Complete
                WHERE idAccount = ?";
        $stmt = $conn -> prepare($sql);
        $stmt -> execute([$idAccount]);

        // Delete the account itself
        $sql = "DELETE FROM t_Account 
                WHERE idAccount = ?";
        $stmt = $conn -> prepare($sql);
        $stmt -> execute([$idAccount]);

        error_log("Account successfully removed.");
    } catch (PDOException $e) {
        error_log("Could not remove account. " . $e -> getMessage());
        header("Location: ../html/admin.php?error=db_error");
        exit();
    }

    header("Location: ../html/admin.php");
}

/**
 * Displays a table that contains all users found in the database.
 * @param PDO $conn Connection to the database.
 */
function displayAllAccounts(PDO $conn) {
    try {
        $stmt = Helper::getAccounts($conn);
    
        // Create HTML table
        echo "<table border='1'>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Score</th>
                    <th>Actions</th>
                </tr>";
    
        // Loop over users
        while ($row = $stmt -> fetch(PDO::FETCH_ASSOC)) {
            $score = is_null($row["accScore"]) ? 
                0 : htmlspecialchars($row["accScore"]);
            echo "<tr>
                    <td>" . htmlspecialchars($row["idAccount"]) . "</td>
                    <td>" . htmlspecialchars($row["accUsername"]) . "</td>
                    <td>" . htmlspecialchars($row["accEmail"]) . "</td>
                    <td>" . $score . "</td>
                    <td>
                        <a href='TODOedit-account.php?id=" 
                            . htmlspecialchars($row["idAccount"]) 
                            . "'>Edit</a> |
                        <a href='../scripts/admin-tools.php?action=removeAccount&id=" 
                            . htmlspecialchars($row["idAccount"]) 
                            . "'>Delete</a>
                    </td>
                </tr>";
        }
    
        // Close HTML table
        echo "</table>";
    } catch (PDOException $e) {
        error_log("Error." . $e -> getMessage());
    }
}

/**
 * Displays a table that contains all tasks found in the database.
 * @param PDO $conn Connection to the database.
 */
function displayAllTasks($conn) {
    try {
        $stmt = Helper::getTasks($conn);
    
        // Create HTML table
        echo "<table border='1'>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Value</th>
                    <th>Complete</th>
                    <th>Actions</th>
                </tr>";
    
        // Loop over tasks
        while ($row = $stmt -> fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>
                    <td>" . htmlspecialchars($row["idTask"]) . "</td>
                    <td>" . htmlspecialchars($row["tasName"]) . "</td>
                    <td>" . htmlspecialchars($row["tasDescription"]) . "</td>
                    <td>" . htmlspecialchars($row["tasScore"]) . "</td>
                    <td>
                        <a href=\"../scripts/manage.php?action=completeTask&id=" 
                        . htmlspecialchars($row["idTask"]) 
                        . "\">Valider</a>
                    </td>
                    <td>
                        <a href='update.php?id=" . htmlspecialchars($row["idTask"]) . "'>Edit</a> |
                        <a href='delete.php?id=" . htmlspecialchars($row["idTask"]) . "'>Delete</a>
                    </td>
                </tr>";
        }
    
        // Close HTML table
        echo "</table>";
    } catch (PDOException $e) {
        error_log("Error." . $e -> getMessage());
    }
}
?>