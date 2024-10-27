<?php
session_start();
require '../database/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Kết nối với cơ sở dữ liệu
    $pdo = Database::connect();
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Kiểm tra xem mật khẩu có đúng không
    if ($user && password_verify($password, $user['password'])) {
        // Lưu thông tin người dùng vào session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        // Chuyển hướng đến trang home
        header("Location: home.php");
        exit();
    } else {
        $error = "Invalid username or password!";
    }
    Database::disconnect();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
        }

        .login-container {
            width: 100%;
            max-width: 400px;
            margin: 100px auto;
            background-color: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .login-container h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
        }

        .form-control {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        .btn {
            width: auto; /* Điều chỉnh để chiều rộng của nút tự động */
            padding: 10px 20px; /* Tăng padding để nút lớn hơn */
            background-color: #0c6980;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            display: block;
            margin: 0 auto; /* Căn giữa nút */
        }

        .btn:hover {
            background-color: #094c5d;
        }

        .register-link {
            text-align: center;
            margin-top: 20px;
        }

        .register-link a {
            text-decoration: none;
            color: #0c6980;
        }

        .error-message {
            color: red;
            margin-bottom: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <form action="login.php" method="POST">
            <h2>Login</h2>
            <?php if (!empty($error)) { echo "<p class='error-message'>$error</p>"; } ?>
            <input type="text" name="username" value="" class="form-control" placeholder="Username" required="required">
            <input type="password" name="password" value="" class="form-control" placeholder="Password" required="required">
            <button type="submit" class="btn">Login</button>
        </form>
        <div class="register-link">
            <p>Don't have an account? <a href="../controller/register.php">Sign up here</a></p>
        </div>
    </div>
</body>
</html>
