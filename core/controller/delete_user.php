<?php
session_start();
require 'database.php';

// Kiểm tra nếu người dùng không phải admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

$id = $_GET['id'];

// Xóa người dùng khỏi cơ sở dữ liệu
$pdo = Database::connect();
$sql = "DELETE FROM users WHERE id = ?";
$q = $pdo->prepare($sql);
$q->execute([$id]);
Database::disconnect();

// Lưu thông báo thành công vào session
$_SESSION['success'] = "Xóa người dùng thành công!";

// Chuyển hướng lại trang quản lý người dùng (manage_users.php) với thông báo thành công
header("Location: manage_users.php");
exit();
?>
