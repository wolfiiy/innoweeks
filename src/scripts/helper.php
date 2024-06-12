<?php
require_once 'connect.php';

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