<?php
require_once 'connect.php';

class Helper {
    /**
     * Gets the ID, username and email of all accounts registered int the
     * database.
     * @param PDO $conn Connection to the database.
     */
    public static function getAccounts($conn) {
        $sql = "SELECT idAccount, accUsername, accEmail, accScore 
                FROM t_Account";
        $result = $conn -> query($sql);
        return $result;
    }

    /**
     * Gets the ID, name and description of every task found in the database.
     * @param PDO $conn Connection to the database.
     * @return query All tasks found in the database.
     */
    public static function getTasks($conn) {
        $sql = "SELECT idTask, tasName, tasDescription, tasScore FROM t_Task";
        $result = $conn -> query($sql);
        return $result;
    }
}
?>