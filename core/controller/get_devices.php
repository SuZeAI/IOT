<?php
require 'database.php';

$pdo = Database::connect();
$sql = "SELECT * FROM devices";
$q = $pdo->prepare($sql);
$q->execute();
$devices = $q->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($devices);

Database::disconnect();
?>
