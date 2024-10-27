<?php
session_start();
require 'database.php';

$id = $_GET['id'];

// Lấy thông tin người dùng từ database để hiển thị trong form
$pdo = Database::connect();
$sql = "SELECT * FROM users WHERE id = ?";
$q = $pdo->prepare($sql);
$q->execute([$id]);
$user = $q->fetch(PDO::FETCH_ASSOC);
Database::disconnect();

// Nếu có dữ liệu session từ lần cập nhật trước, sử dụng chúng thay cho dữ liệu trong database
if (isset($_SESSION['user_data'])) {
    $user['username'] = $_SESSION['user_data']['username'];
    $user['email'] = $_SESSION['user_data']['email'];
    $user['role'] = $_SESSION['user_data']['role'];
    unset($_SESSION['user_data']); // Xóa session sau khi hiển thị
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit User</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            width: 400px; /* Tất cả các phần tử đều có chiều rộng tối đa 400px */
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        label {
            font-size: 16px;
            color: #333;
            margin-bottom: 5px;
            display: block;
        }

        input[type=text], select {
            width: 100%; /* Các ô nhập liệu và ô chọn đều có chiều rộng 100% */
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
            box-sizing: border-box; /* Đảm bảo padding và border không làm thay đổi chiều rộng */
        }

        .btn {
            width: 100%; /* Nút cũng có chiều rộng 100% */
            padding: 10px;
            background-color: #0c6980;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            text-align: center;
        }

        .btn:hover {
            background-color: #094c5d;
        }

        .success-message, .error-message {
            padding: 10px;
            margin-bottom: 20px;
            text-align: center;
            border-radius: 5px;
        }

        .success-message {
            background-color: #4CAF50;
            color: white;
        }

        .error-message {
            background-color: #f44336;
            color: white;
        }

    </style>

    <script>
        window.onload = function() {
            const successMessage = document.querySelector('.success-message');
            const errorMessage = document.querySelector('.error-message');
            
            if (successMessage || errorMessage) {
                setTimeout(function() {
                    window.location.href = 'manage_users.php';
                }, 1000); // 0.7 giây
            }
        }
    </script>
</head>
<body>
    <div class="container">
        <h1>Edit User</h1>
        
        <!-- Hiển thị thông báo thành công -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="success-message">
                <?php 
                echo $_SESSION['success'];
                unset($_SESSION['success']);
                ?>
            </div>
        <?php endif; ?>

        <!-- Hiển thị thông báo lỗi -->
        <?php if (isset($_SESSION['error'])): ?>
            <div class="error-message">
                <?php 
                echo $_SESSION['error'];
                unset($_SESSION['error']);
                ?>
            </div>
        <?php endif; ?>

        <form action="update_user.php" method="POST">
            <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="<?php echo $user['username']; ?>" required>

            <label for="email">Email:</label>
            <input type="text" id="email" name="email" value="<?php echo $user['email']; ?>" required>

            <label for="role">Role:</label>
            <select id="role" name="role">
                <option value="user" <?php if ($user['role'] == 'user') echo 'selected'; ?>>User</option>
                <option value="admin" <?php if ($user['role'] == 'admin') echo 'selected'; ?>>Admin</option>
            </select>

            <input type="submit" class="btn" value="Update">
        </form>
    </div>
</body>
</html>
