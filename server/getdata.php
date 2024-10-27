<?php
    include 'database.php';
    
    // Kiểm tra rằng giá trị POST không rỗng.
    if (!empty($_POST)) {
        // Lấy giá trị từ POST
        $id = $_POST['id'];
    
        $myObj = (object)array();
    
        //...
        
        // Truy vấn dữ liệu và tạo đối tượng
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
    
        // Chuyển đối tượng thành chuỗi JSON
        $myJSON = json_encode($myObj);
    
        // Xuất chuỗi JSON
        echo $myJSON;
    }
        
?>