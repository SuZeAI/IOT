<?php
require 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $status = $_POST['status'];

    $pdo = Database::connect();
    $sql = "UPDATE devices SET status = ? WHERE id = ?";
    $q = $pdo->prepare($sql);
    $result = $q->execute([$status, $id]);

    if ($result) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }

    Database::disconnect();
}
?>
