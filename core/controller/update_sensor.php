<?php
  require 'database.php';
  
  //---------------------------------------- Condition to check that POST value is not empty.
  if (!empty($_POST)) {
    //........................................ keep track POST values
    $id = $_POST['id'];
    $temperature = $_POST['temperature'];
    $humidity = $_POST['humidity'];
    $soil = $_POST['soil'];
    $light = $_POST['light'];
    $concentration = $_POST['concentration'];
    //........................................
    //........................................ Get the time and date.
    date_default_timezone_set("Asia/Ho_Chi_Minh"); // Look here for your timezone : https://www.php.net/manual/en/timezones.php
    $tm = date("H:i:s");
    $dt = date("Y-m-d");
    //........................................
    
    //........................................ Updating the data in the table.
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //thay thế_with_your_table_name, trong dự án này tôi sử dụng tên bảng 'esp32_table_dht11_leds_update'.
    // Bảng này dùng để lưu trữ dữ liệu cảm biến DHT11 được cập nhật bởi ESP32.
    // Bảng này còn dùng để lưu trữ trạng thái của các LED, trạng thái của các LED được điều khiển từ trang "home.php".
    // Bảng này được vận hành bằng lệnh "UPDATE", vì vậy bảng này sẽ chỉ chứa một hàng.
    $sql = "UPDATE updatesdata SET temperature = ?, humidity = ?, soil = ?, light = ?,concentration = ?, time = ?, date = ? WHERE id = ?";
    $q = $pdo->prepare($sql);
    $q->execute(array($temperature,$humidity,$soil,$light,$concentration,$tm,$dt,$id));
    Database::disconnect();
    //........................................ 
    
    //........................................ Nhập dữ liệu vào một bảng.
    $id_key;
    $board = $_POST['id'];
    $found_empty = false;
    
    $pdo = Database::connect();
    
    //:::::::: Quá trình kiểm tra xem "id" đã được sử dụng chưa.
    while ($found_empty == false) {
      $id_key = generate_string_id(10);
      // thay thế_with_your_table_name, trong dự án này tôi sử dụng tên bảng 'esp32_table_dht11_leds_record'.
      // Bảng này dùng để lưu trữ và ghi lại dữ liệu cảm biến DHT11 được ESP32 cập nhật.
      // Bảng này còn dùng để lưu trữ và ghi lại trạng thái của các LED, trạng thái của các LED được điều khiển từ trang "home.php".
      // Bảng này được vận hành bằng lệnh "INSERT", vì vậy bảng này sẽ chứa nhiều hàng.
      // Trước khi lưu và ghi dữ liệu vào bảng này, "id" sẽ được kiểm tra trước, để đảm bảo rằng "id" vừa tạo chưa được sử dụng trong bảng.   
    $sql = 'SELECT * FROM recorddata WHERE id="' . $id_key . '"';
      $q = $pdo->prepare($sql);
      $q->execute();
      
      if (!$data = $q->fetch()) {
        $found_empty = true;
      }
    }
    //::::::::
    
    //:::::::: The process of entering data into a table.
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  // thay thế_with_your_table_name, trong dự án này tôi sử dụng tên bảng 'esp32_table_dht11_leds_record'.
    // Bảng này dùng để lưu trữ và ghi lại dữ liệu cảm biến DHT11 được ESP32 cập nhật.
    // Bảng này còn dùng để lưu trữ và ghi lại trạng thái của các LED, trạng thái của các LED được điều khiển từ trang "home.php".
    // Bảng này được vận hành bằng lệnh "INSERT", vì vậy bảng này sẽ chứa nhiều hàng.
    $sql = "INSERT INTO recorddata (id,board,temperature,humidity,soil,light,concentration,time,date) values(?, ?, ?, ?, ?, ?, ?, ?,?)";
    $q = $pdo->prepare($sql);
    $q->execute(array($id_key,$board,$temperature,$humidity,$soil,$light,$concentration,$tm,$dt));
    //::::::::
    
    Database::disconnect();
    //........................................ 
  }
  //---------------------------------------- 
  
  //---------------------------------------- Function to create "id" based on numbers and characters.
  function generate_string_id($strength = 16) {
    $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $input_length = strlen($permitted_chars);
    $random_string = '';
    for($i = 0; $i < $strength; $i++) {
      $random_character = $permitted_chars[mt_rand(0, $input_length - 1)];
      $random_string .= $random_character;
    }
    return $random_string;
  }
  //---------------------------------------- 
?>
