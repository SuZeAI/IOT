<?php
// Include file database.php để kết nối CSDL
include 'database/database.php';

// Mã hóa mật khẩu "admin"
$hashedPassword = password_hash('admin', PASSWORD_DEFAULT);

// Kết nối cơ sở dữ liệu
$pdo = Database::connect();

// Kiểm tra xem tài khoản admin đã tồn tại chưa
$sql = "SELECT * FROM users WHERE username = 'admin'";
$q = $pdo->prepare($sql);
$q->execute();

if ($q->rowCount() == 0) {
    // Nếu chưa tồn tại, thêm tài khoản admin vào CSDL
    $sql = "INSERT INTO users (username, password, role) VALUES ('admin', ?, 'admin')";
    $q = $pdo->prepare($sql);
    $q->execute([$hashedPassword]);
    echo "Tài khoản admin đã được tạo thành công!";
} else {
    echo "Tài khoản admin đã tồn tại!";
}

Database::disconnect();
?>
