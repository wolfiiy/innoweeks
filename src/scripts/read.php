<?php
require_once 'connect.php';
require_once 'helper.php';

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
        $tasks = Helper::getTasks($conn);

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