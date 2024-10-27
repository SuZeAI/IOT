<?php
session_start();

// Kiểm tra nếu người dùng chưa đăng nhập hoặc không phải admin, chuyển hướng về trang đăng nhập
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Kết nối cơ sở dữ liệu
require '../database/database.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            min-height: 100vh;
        }
        .sidebar {
            width: 15%;
            background-color: #0c6980;
            padding: 20px;
            color: white;
            height: 100vh;
        }
        .sidebar h2 {
            text-align: center;
            font-size: 1.5rem;
        }
        .sidebar a {
            text-decoration: none;
            color: white;
            display: block;
            padding: 10px 0;
            background-color: #094c5d;
            border-radius: 5px;
            text-align: center;
            margin-bottom: 10px;
        }
        .sidebar a:hover {
            background-color: #073845;
        }
        .main-content {
            width: 85%;
            padding: 20px;
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .main-content h1 {
            text-align: center;
            font-size: 2rem;
        }
        .logout-btn {
            position: absolute;
            right: 20px;
            top: 20px;
            background-color: #f44336;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            font-size: 1rem;
            border: none;
            border-radius: 10px;
            cursor: pointer;
        }
        .logout-btn:hover {
            background-color: #d32f2f;
        }
        .device-grid {
            display: flex;
            justify-content: space-evenly;
            width: 100%;
            margin-top: 20px;
        }
        .device {
            background-color: #f5f5f5;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 18%;
        }
        .device button {
            padding: 10px 20px;
            font-size: 1rem;
            margin: 5px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .on-btn {
            background-color: #4CAF50;
            color: white;
        }
        .off-btn {
            background-color: #f44336;
            color: white;
        }
        .device-state {
            margin-top: 10px;
            font-weight: bold;
        }

        /* CSS cho phần thông số kỹ thuật */
        .specifications {
            margin-top: 40px;
            width: 100%;
        }
        .specifications h2 {
            text-align: center;
            font-size: 1.8rem;
        }
        .specs-table {
            width: 90%;
            margin: 20px auto;
            border-collapse: collapse;
        }
        .specs-table th, .specs-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        .specs-table th {
            background-color: #0c6980;
            color: white;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>Admin Panel</h2>
        <a href="admin.php">Dashboard</a>
        <a href="manage_users.php">Manage Users</a>
        <a href="home.php">home</a>
    </div>

    <div class="main-content">
        <h1>Admin Dashboard</h1>
        <a href="logout.php" class="logout-btn">Logout</a>
        
        <!-- Phần bật tắt thiết bị -->
        <div class="device-grid">
            <!-- Thiết bị 1: Quạt -->
            <div class="device">
                <h3>Quạt</h3>
                <button class="on-btn">ON</button>
                <button class="off-btn">OFF</button>
                <div class="device-state">State: OFF</div>
            </div>
            <!-- Thiết bị 2: Máy bơm -->
            <div class="device">
                <h3>Máy bơm</h3>
                <button class="on-btn">ON</button>
                <button class="off-btn">OFF</button>
                <div class="device-state">State: OFF</div>
            </div>
            <!-- Thiết bị 3: Sensor điều khiển mái che -->
            <div class="device">
                <h3>Điều khiển mái che</h3>
                <button class="on-btn">ON</button>
                <button class="off-btn">OFF</button>
                <div class="device-state">State: OFF</div>
            </div>
        </div>

        <!-- Phần hiển thị thông số kỹ thuật dưới dạng bảng -->
        <div class="specifications">
            <h2>Thông số kỹ thuật của các cảm biến</h2>
            <table class="specs-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Model</th>
                        <th>Measurement Range</th>
                        <th>Accuracy</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Lấy dữ liệu từ cơ sở dữ liệu
                    $pdo = Database::connect();
                    $sql = 'SELECT * FROM sensors';
                    foreach ($pdo->query($sql) as $row) {
                        echo '<tr>';
                        echo '<td>' . $row['name'] . '</td>';
                        echo '<td>' . $row['model'] . '</td>';
                        echo '<td>' . $row['measurement_range'] . '</td>';
                        echo '<td>' . $row['accuracy'] . '</td>';
                        echo '<td>' . $row['description'] . '</td>';
                        echo '</tr>';
                    }
                    Database::disconnect();
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
