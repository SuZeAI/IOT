<?php
session_start();

// Hủy tất cả các session hiện tại
session_unset();
session_destroy();

// Xóa cookie session (nếu có)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, 
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Ngăn trình duyệt lưu trữ cache
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Chuyển hướng về trang đăng nhập
header("Location: login.php");
exit();
?>
