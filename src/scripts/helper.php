<?php
require_once 'connect.php';

/**
 * Helper class containing various getters and conversion utilities.
 */
class Helper {
    /**
     * Gets the account ID from the username.
     * @param PDO $conn Connection to the database.
     * @param string $username Username for which to fetch the account ID.
     * @return int|false The account ID if found, false otherwise.
     */
    public static function getAccountId(PDO $conn, string $username) {
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

    /**
     * Gets an account's email address.
     * @param PDO $conn Connection to the database.
     * @param string $username Username of the account.
     * @return string|false The current email if found, false otherwise.
     */
    public static function getAccountEmail(PDO $conn, string $username) {
        try {
            $idAccount = self::getAccountId($conn, $username);
            $sql = "SELECT accEmail FROM t_Account WHERE idAccount = ?";
            $stmt = $conn -> prepare($sql);
            $stmt -> execute([$idAccount]);
            $result = $stmt -> fetch(PDO::FETCH_ASSOC);

            return $result ? $result['accEmail'] : false;
        } catch (PDOException $e) {
            error_log("An error ocurred. " . $e -> getMessage());
            return false;
        }
    }

    /**
     * Gets an account's email address.
     * @param PDO $conn Connection to the database.
     * @param string $username Username of the account.
     * @return int|false The current age if found, false otherwise.
     */
    public static function getAccountAge(PDO $conn, string $username) {
        try {
            $idAccount = self::getAccountId($conn, $username);
            $sql = "SELECT accAge FROM t_Account WHERE idAccount = ?";
            $stmt = $conn -> prepare($sql);
            $stmt -> execute([$idAccount]);
            $result = $stmt -> fetch(PDO::FETCH_ASSOC);

            return $result ? $result['accAge'] : false;
        } catch (PDOException $e) {
            error_log("An error ocurred. " . $e -> getMessage());
            return false;
        }
    }

    /**
     * Gets the ID, username and email of all accounts registered int the
     * database.
     * @param PDO $conn Connection to the database.
     * @return PDOStatement The result set that contains all user accounts.
     */
    public static function getAccounts($conn) {
        $sql = "SELECT idAccount, accUsername, accEmail, accScore 
                FROM t_Account";
        $result = $conn -> query($sql);
        return $result;
    }

    /**
     * Retrieves all user accounts from the database, ordered by their score in
     * descending order.
     * @param PDO $conn Connection to the database.
     * @return PDOStatement|false The result set as a PDOStatement on success,
     * or false on failure.
     */
    public static function getAccountsOrderedByScore($conn) {
        $sql = "SELECT idAccount, accUsername, accEmail, accScore 
                FROM t_Account
                ORDER BY accScore DESC";
        $result = $conn -> query($sql);
        return $result;
    }

    /**
     * Gets the current score of a given user.
     * @param PDO $conn Connection to the database.
     * @param int $idAccount ID of the account.
     * @return int|false The score if found, false otherwise.
     */
    public static function getAccountScore(PDO $conn, int $idAccount) {
        try {
            $sql = "SELECT accScore FROM t_Account WHERE idAccount = ?";
            $stmt = $conn -> prepare($sql);
            $stmt -> execute([$idAccount]);
            
            $result = $stmt -> fetch(PDO::FETCH_ASSOC);
    
            if ($result) {
                return $result['accScore'];
            } else {
                return false;
            }
        } catch (PDOException $e) {
            error_log("An error occurred. " . $e -> getMessage());
            return false;
        }
    }

    /**
     * Gets the ID, name and description of every task found in the database.
     * @param PDO $conn Connection to the database.
     * @return PDOStatement All tasks found in the database.
     */
    public static function getTasks($conn) {
        $sql = "SELECT idTask, tasName, tasDescription, tasScore 
                FROM t_Task";
        $result = $conn -> query($sql);
        return $result;
    }

    /**
     * Gets the score value of a task.
     * @param PDO $conn Connection to the database.
     * @param int $idTask ID of the task.
     * @return int The score value of the task or -1 if it could not be found.
     * @throws PDOException If an error occurred during the execution of the 
     * query.
     */
    public static function getTaskScore(PDO $conn, int $idTask) {
        $tasScore = -1;

        try {
            $sql = "SELECT tasScore FROM t_Task WHERE idTask = ?";
            $stmt = $conn -> prepare($sql);
            $stmt -> execute([$idTask]);
            $stmt -> setFetchMode(PDO::FETCH_ASSOC);
    
            // Fetch result
            $result = $stmt -> fetch();
            if ($result) {
                $tasScore = $result['tasScore'];
            }
        } catch (PDOException $e) {
            error_log("An error occured. " . $e -> getMessage());
        }
    
        return $tasScore;
    }

    /**
     * Gets the state of a task, given an account ID.
     * @param PDO $conn Connection to the database.
     * @param int $idAccount ID of the account.
     * @param int $idTask ID of the task.
     * @return true|false True if the task was completed, false otherwise.
     * @throws PDOException If there is an error executing the query.
     */
    public static function getTaskState($conn, $idAccount, $idTask) {
        try {
            $sql = "SELECT comState 
                    FROM Complete 
                    WHERE idAccount = ? AND idTask = ?";
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
}
?>