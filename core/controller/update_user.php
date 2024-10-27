<?php
session_start();
require 'database.php';

$id = $_POST['id'];
$username = $_POST['username'];
$email = $_POST['email'];
$role = $_POST['role'];

// Kết nối đến cơ sở dữ liệu
$pdo = Database::connect();

// Kiểm tra nếu username đã tồn tại và không phải là của người dùng hiện tại
$sql = "SELECT * FROM users WHERE username = ? AND id != ?";
$q = $pdo->prepare($sql);
$q->execute([$username, $id]);
$user = $q->fetch(PDO::FETCH_ASSOC);

if ($user) {
    // Nếu username bị trùng, lưu thông báo lỗi vào session và quay lại trang edit
    $_SESSION['error'] = 'Username đã tồn tại!';
    $_SESSION['user_data'] = ['username' => $username, 'email' => $email, 'role' => $role];
    header("Location: edit_user.php?id=" . $id);
    Database::disconnect();
    exit();
}

// Kiểm tra nếu email đã tồn tại và không phải là của người dùng hiện tại
$sql = "SELECT * FROM users WHERE email = ? AND id != ?";
$q = $pdo->prepare($sql);
$q->execute([$email, $id]);
$user = $q->fetch(PDO::FETCH_ASSOC);

if ($user) {
    // Nếu email bị trùng, lưu thông báo lỗi vào session và quay lại trang edit
    $_SESSION['error'] = 'Email đã tồn tại!';
    $_SESSION['user_data'] = ['username' => $username, 'email' => $email, 'role' => $role];
    header("Location: edit_user.php?id=" . $id);
    Database::disconnect();
    exit();
}

// Nếu không có lỗi, cập nhật người dùng
$sql = "UPDATE users SET username = ?, email = ?, role = ? WHERE id = ?";
$q = $pdo->prepare($sql);
$q->execute([$username, $email, $role, $id]);

// Đóng kết nối
Database::disconnect();

// Lưu thông báo thành công vào session và quay lại trang edit
$_SESSION['success'] = 'Cập nhật người dùng thành công!';
header("Location: edit_user.php?id=" . $id);
exit();
?>
