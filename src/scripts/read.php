<?php
require_once 'connect.php';
require_once 'helper.php';

/**
 * Fills the leaderboard with usernames and scores.
 * @param PDO $conn Connection to the database.
 */
function fillLeaderboard(PDO $conn) {
    try {
        $accounts = Helper::getAccountsOrderedByScore($conn);
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
 * Fills the tasks container.
 * @param PDO $conn Connection to the database.
 */
function fillTaskContainer(PDO $conn) {
    try {
        $tasks = Helper::getTasks($conn);
        $accUsername = $_SESSION['username'];
        $idAccount = Helper::getAccountId($conn, $accUsername);

        while ($row = $tasks -> fetch(PDO::FETCH_ASSOC)) {
            $isDone = Helper::getTaskState($conn, $idAccount, $row['idTask']);
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
?>