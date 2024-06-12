<?php
require_once 'connect.php';
require 'admin-tools.php';

$id = $_GET['id'];
removeAccountAsAdmin($id, $conn);
$conn -> close();
?>
