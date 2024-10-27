<?php
require 'database.php';

$pdo = Database::connect();
$sql = 'SELECT * FROM sensors';
$query = $pdo->prepare($sql);
$query->execute();
$sensors = $query->fetchAll(PDO::FETCH_ASSOC);
Database::disconnect();

echo json_encode($sensors);
?>
