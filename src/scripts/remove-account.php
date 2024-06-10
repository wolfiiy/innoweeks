<?php
require 'connect.php';
require 'manage.php';

$id = $_GET['id'];
removeAccount($id, $conn);
$conn -> close();
?>
