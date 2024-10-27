<?php
session_start();
require 'database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Kiểm tra xem mật khẩu có khớp không
    if ($password != $confirm_password) {
        $_SESSION['error'] = "Passwords do not match!";
        header("Location: register.php");
        exit();
    } else {
        // Mã hóa mật khẩu
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Kết nối cơ sở dữ liệu và thêm người dùng
        $pdo = Database::connect();
        
        // Kiểm tra xem tên đăng nhập hoặc email đã tồn tại chưa
        $sql_check = "SELECT * FROM users WHERE username = ? OR email = ?";
        $stmt_check = $pdo->prepare($sql_check);
        $stmt_check->execute([$username, $email]);
        if ($stmt_check->fetch()) {
            $_SESSION['error'] = "Username or email already exists!";
            header("Location: register.php");
            exit();
        } else {
            // Thêm người dùng mới
            $sql = "INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, 'user')";
            $stmt = $pdo->prepare($sql);
            if ($stmt->execute([$username, $email, $hashed_password])) {
                // Lưu thông báo thành công vào session
                $_SESSION['success'] = "Registration successful!";
                header("Location: register.php");
                exit();
            } else {
                $_SESSION['error'] = "Error occurred during registration!";
                header("Location: register.php");
                exit();
            }
        }

        Database::disconnect();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
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

        .register-container {
            width: 100%;
            max-width: 400px;
            background-color: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .register-container h2 {
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
            width: auto;
            padding: 10px 20px;
            background-color: #0c6980;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            display: block;
            margin: 0 auto;
        }

        .btn:hover {
            background-color: #094c5d;
        }

        .error-message, .success-message {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            text-align: center;
            font-weight: bold;
        }

        .error-message {
            background-color: #f8d7da;
            color: #721c24;
        }

        .success-message {
            background-color: #d4edda;
            color: #155724;
        }
    </style>

    <script>
        window.onload = function() {
            const successMessage = document.querySelector('.success-message');
            const errorMessage = document.querySelector('.error-message');

            // Thông báo thành công: Sau 0.7s chuyển trang
            if (successMessage) {
                setTimeout(function() {
                    window.location.href = 'login.php';
                }, 700);
            }

            // Thông báo lỗi: Sau 0.7s thông báo biến mất
            if (errorMessage) {
                setTimeout(function() {
                    errorMessage.style.display = 'none';
                }, 700);
            }
        }
    </script>
</head>
<body>
    <div class="register-container">
        <h2>Register</h2>

        <!-- Hiển thị thông báo lỗi nếu có -->
        <?php if (isset($_SESSION['error'])): ?>
            <p class="error-message"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></p>
        <?php endif; ?>

        <!-- Hiển thị thông báo thành công nếu có -->
        <?php if (isset($_SESSION['success'])): ?>
            <p class="success-message"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></p>
        <?php endif; ?>

        <form action="register.php" method="POST">
            <input type="text" name="username" class="form-control" placeholder="Username" required>
            <input type="email" name="email" class="form-control" placeholder="Email" required>
            <input type="password" name="password" class="form-control" placeholder="Password" required>
            <input type="password" name="confirm_password" class="form-control" placeholder="Confirm Password" required>
            <button type="submit" class="btn">Register</button>
        </form>
    </div>
</body>
</html>

