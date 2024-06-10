<?php
include 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['task-name'];
    $description = $_POST['task-description'];
    $score = $_POST['task-score'];

    error_log("Attempting to add a new task...");

    try {
        $sql = "INSERT INTO t_Task (tasName, tasDescription, tasScore)
                VALUES (?, ?, ?)";
        $stmt = $conn -> prepare($sql);
        $stmt -> execute([$name, $description, $score]);
        error_log("Task successfully created.");
    } catch (Exception $e) {
        error_log("Task could not be created." . $e -> getMessage());
        header("Location: ../html/admin.php?error=db_error");
    }

    $conn = null;
}

header("Location: ../html/admin.php");
exit();

?>