<?php
$server = "TODO";
$username = "TODO";
$password = "TODO";
$database = "TODO";

try {
    // Connect to database using PDO instance
    $connection = new PDO("mysql:host=$server;$database=$dbname", 
                          $username, 
                          $password);

    // Throw exceptions on errors
    $connection -> setAttribute(PDO::ATTR_ERRMODE, 
                                PDO::ERRMODE_EXCEPTION);

    // Create tables if needed
    $sqlFile = "../sql/create.sql";
    $sql = file_get_contents($sqlfile);
    $connection -> exec($sql);

    echo "Tables successfully created.";
} catch (PDOException $e) {
    echo "Could not connect to database." . $e -> getMessage();
    exit();
}

?>