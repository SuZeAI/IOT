<?php
include 'database.php';

if (!empty($_POST)) {
    $id = $_POST['id'];
    $myObj = (object) array();

    // Kết nối tới cơ sở dữ liệu
    $pdo = Database::connect();
    $sql = 'SELECT * FROM updatesdata WHERE id = :id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        $date = date_create($row['date']);
        $dateFormat = date_format($date, "d-m-Y");

        $myObj->id = $row['id'];
        $myObj->temperature = $row['temperature'];
        $myObj->humidity = $row['humidity'];
        $myObj->soil = $row['soil'];
        $myObj->light = $row['light'];
        $myObj->concentration = $row['concentration'];      
        $myObj->ls_time = $row['time'];
        $myObj->ls_date = $dateFormat;
    }

    // Trả về kết quả dưới dạng JSON
    echo json_encode($myObj);
}
?>
