<?php
include "connect.php";
error_log("Attempting to create tables...");

try {
    $sqlFile = "../sql/create-tables.sql";
    $sql = file_get_contents($sqlFile);

    if ($sql === false) {
        throw new Exception("Failed to read SQL script.");
    }

    if ($conn -> multi_query($sql)) {
        do {
            // Use next_result() to move to the next result set if available
            if ($result = $conn->store_result()) {
                $result->free();
            }
        } while ($conn->more_results() && $conn->next_result());
    } else {
        throw new Exception("SQL execution error: " . $conn -> error);
    }

    error_log("Tables successfully created.");
} catch (Exception $e) {
    error_log("Failed to create tables." . $e -> getMessage());
}

header("Location: " . $_SERVER["HTTP_REFERER"]);
exit();
?>