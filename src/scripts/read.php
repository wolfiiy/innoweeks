<?php
include 'connect.php';

/**
 * Fills the leaderboard with usernames and scores.
 * @param PDO $conn Connection to the database.
 */
function fillLeaderboard($conn) {
    try {
        $accounts = getAccountsOrderedByScore($conn);
        $counter = 0;
        $styleCounter = 0;

        while ($row = $accounts -> fetch(PDO::FETCH_ASSOC)) {
            if ($counter < 5) {
                $styleCounter++;
            }

            $counter++;
            $score = is_null($row["accScore"]) ? 0 : htmlspecialchars($row["accScore"]);
            echo 
               "<div class=\"leaderboard-item\">
                    <div class=\"rank rank-$styleCounter\">$counter</div>
                    <div class=\"leaderboard-username\">" . htmlspecialchars($row["accUsername"]) . "</div>
                    <div class=\"points\"> " . $score . " Pts</div>
                </div>";
        }

    } catch (PDOException $e) {
        error_log("An error occurred. " . $e -> getMessage());
        exit();
    }
}

/**
 * Displays a table that contains all users found in the database.
 * @param PDO $conn Connection to the database.
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
                    <th>Score</th>
                    <th>Actions</th>
                </tr>";
    
        // Loop over users
        while ($row = $stmt -> fetch(PDO::FETCH_ASSOC)) {
            $score = is_null($row["accScore"]) ? 0 : htmlspecialchars($row["accScore"]);
            echo "<tr>
                    <td>" . htmlspecialchars($row["idAccount"]) . "</td>
                    <td>" . htmlspecialchars($row["accUsername"]) . "</td>
                    <td>" . htmlspecialchars($row["accEmail"]) . "</td>
                    <td>" . $score . "</td>
                    <td>
                        <a href='remoasfdsdfve-account.php?id=" . htmlspecialchars($row["idAccount"]) . "'>Edit</a> |
                        <a href='../scripts/remove-account.php?id=" . htmlspecialchars($row["idAccount"]) . "'>Delete</a>
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
 * Gets the ID, username and email of all users of the habit tracker web app.
 * @param PDO $conn Connection to the database. 
 */
function getAccounts($conn) {
    $sql = "SELECT idAccount, accUsername, accEmail, accScore FROM t_Account";
    $result = $conn -> query($sql);
    return $result;
}

/**
 * Retrieves user accounts, order by score.
 * @param PDO $conn Connection to the database.
 */
function getAccountsOrderedByScore($conn) {
    $sql = "SELECT idAccount, accUsername, accEmail, accScore 
            FROM t_Account
            ORDER BY accScore DESC";
    $result = $conn -> query($sql);
    return $result;
}

/**
 * Fills the tasks container.
 * @param PDO $conn Connection to the database.
 */
function fillTaskContainer($conn) {
    try {
        $tasks = getTasks($conn);

        while ($row = $tasks -> fetch(PDO::FETCH_ASSOC)) {
            $isDone = hasLoggedAccountCompletedTask($conn, $row["idTask"]);
            $btnClass = $isDone ? "locked " : "";
            $btnText = $isDone ? "Complétée!" : "Valider";

            echo 
               "<div class=\"task-item\">
                    <div class=\"task-texts\">
                        <p class=\"task-name\">
                            " . htmlspecialchars($row["tasName"]) . " | 
                            " . htmlspecialchars($row["tasScore"]) . " 
                            pts
                        </p>

                        <p class=\"task-description\">
                            " . htmlspecialchars($row["tasDescription"]) . "
                        </p>
                    </div>

                    <a href=\"../scripts/manage.php?action=completeTask&id="
                        . htmlspecialchars($row["idTask"])
                        . "\" 
                        class=\"button 
                        " . $btnClass . "
                        \">
                        " . $btnText . "
                    </a>
                </div>";
        }

    } catch (PDOException $e) {
        error_log("An error occurred. " . $e -> getMessage());
        exit();
    }
}

/**
 * Displays a table that contains all tasks found in the database.
 * @param PDO $conn Connection to the database.
 */
function displayTasks($conn) {
    try {
        $stmt = getTasks($conn);
    
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

/**
 * Gets the ID, name and description of every task found in the database.
 * @param PDO $conn Connection to the database.
 * @return query All tasks found in the database.
 */
function getTasks($conn) {
    $sql = "SELECT idTask, tasName, tasDescription, tasScore FROM t_Task";
    $result = $conn -> query($sql);
    return $result;
}

/**
 * Checks whether a task has already been done.
 * @param PDO $conn Connection to the database.
 * @param int $idTask ID of the task.
 * @return true|false If the task is done, and false otherwise.
 */
function isTaskDone(PDO $conn, int $idTask) {
    try {
        $sql = "SELECT tasState FROM t_Task WHERE idTask = ?";
        $stmt = $conn -> prepare($sql);
        $stmt -> execute([$idTask]);
        $result = $stmt -> fetch(PDO::FETCH_ASSOC);

        if ($result) {
            return $result['tasState'] === 0 ? false : true;
        } else {
            return false;
        }
    } catch (PDOException $e) {
        error_log("An error occurred. " . $e -> getMessage());
        return false;
    }
}

/**
 * Given an account, returns the state of a task.
 * @param PDO $conn Connection to the database.
 * @param int $idTask ID of the task.
 * @param int $idAccount ID of the account.
 */
function hasAccountCompletedTask($conn, $idTask, $idAccount) {
    try {
        $sql = "SELECT comState FROM Complete WHERE idAccount = ? AND idTask = ?";
        $stmt = $conn -> prepare($sql);
        $stmt -> execute([$idAccount, $idTask]);
        $result = $stmt -> fetch(PDO::FETCH_ASSOC);

        if ($result) {
            return $result['comState'] === 1 ? true : false;
        } else {
            return false;
        }
    } catch (PDOException $e) {
        error_log("An error occurred. " . $e -> getMessage());
        return false;
    }
}

function hasLoggedAccountCompletedTask($conn, $idTask) {
    $accUsername = $_SESSION['username'];
    $idAccount = getAccountByUsername($conn, $accUsername);
    return hasAccountCompletedTask($conn, $idTask, $idAccount);
}

/**
 * Gets the account ID from the username.
 * @param PDO $conn Connection to the database.
 * @param string $username Username for which to fetch the account ID.
 * @return int|false The account ID if found, false otherwise.
 */
function getAccountByUsername($conn, $username) {
    try {
        $sql = "SELECT idAccount FROM t_Account WHERE accUsername = ?";
        $stmt = $conn -> prepare($sql);
        $stmt -> execute([$username]);

        $result = $stmt -> fetch(PDO::FETCH_ASSOC);

        if ($result) {
            return $result['idAccount'];
        } else {
            return false;
        }

    } catch (PDOException $e) {
        error_log("An error occurred. " . $e -> getMessage());
        return false;
    }
}
?>