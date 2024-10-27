<?php
session_start();

// Kiểm tra nếu người dùng không phải admin, chuyển hướng về trang đăng nhập
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Kết nối đến cơ sở dữ liệu
require '../database/database.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
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

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #0c6980;
            color: white;
        }

        .btn {
            padding: 10px 20px;
            background-color: #0c6980;
            color: white;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-size: 16px;
            cursor: pointer;
        }

        .btn:hover {
            background-color: #094c5d;
        }

        .actions {
            display: flex;
            gap: 10px;
        }
    </style>
</head>
<body>

    <div class="sidebar">
        <h2>Admin Panel</h2>
        <a href="admin.php">Dashboard</a>
        <a href="manage_users.php">Manage Users</a>
    </div>

    <div class="main-content">
        <h1>Manage Users</h1>
        <a href="logout.php" class="logout-btn">Logout</a>

        <table>
            <tr>
                <th>Username</th>
                <th>Email</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
            <?php
            $pdo = Database::connect();
            $sql = 'SELECT * FROM users WHERE id != ?';
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$_SESSION['user_id']]);

            foreach ($stmt as $row) {
                echo '<tr>';
                echo '<td>' . $row['username'] . '</td>';
                echo '<td>' . $row['email'] . '</td>';
                echo '<td>' . $row['role'] . '</td>';
                echo '<td class="actions">
                        <a href="../controller/edit_user.php?id=' . $row['id'] . '" class="btn">Edit</a>
                        <a href="../controller/delete_user.php?id=' . $row['id'] . '" class="btn">Delete</a>
                      </td>';
                echo '</tr>';
            }
            Database::disconnect();
            ?>
        </table>

    </div>
</body>
</html>
