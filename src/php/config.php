<?php

$server = "TODO";
$username = "TODO";
$password = "TODO";
$database = "database";

try {
    // Connect to database using PDO instance
    $connection = new PDO("mariadb:host=$server;$database=$dbname", 
                          $username, 
                          $password);

    // Throw exceptions on errors
    $connection -> setAttribute(PDO::ATTR_ERRMODE, 
                                PDO::ERRMODE_EXCEPTION);

    // Choose correct database                            
    $connection -> exec("CREATE DATABSE IF NOT EXISTS $database");
   // $connection -> exec("USE $database");

    // Create tables if needed
    $sqlFile = "../sql/create-tables.sql";
    $sql = file_get_contents($sqlfile);
    $connection -> exec($sql);

    echo "Tables successfully created.";
} catch (PDOException $e) {
    echo "Could not connect to database." . $e -> getMessage();
    exit();
}

?>