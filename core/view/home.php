<?php
session_start();

// Kiểm tra nếu người dùng đã đăng nhập, nếu chưa, chuyển hướng về trang đăng nhập
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Lấy vai trò và tên người dùng từ session
$user_role = $_SESSION['role'];
$username = $_SESSION['username'];
?>

<!DOCTYPE HTML>
<html>
  <head>
    <title>ESP8266 WITH MYSQL DATABASE</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Thêm thư viện Ionicons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/5.5.2/ionicons.min.css">
    <link rel="icon" href="data:,">
    <style>
      html {font-family: Arial; display: inline-block; text-align: center;}
      p {font-size: 1.2rem;}
      h4 {font-size: 0.8rem;}
      body {margin: 0;}
      
      .topnav {
        overflow: hidden;
        background-color: #0c6980;
        color: white;
        font-size: 1.2rem;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 10px 20px;
        position: relative;
      }


      .topnav h3 {
        text-align: center;
        margin: 0;
      }

      .topnav .admin-controls {
        position: absolute;
        left: 20px;
      }

      .topnav .admin-controls a {
        color: white;
        text-decoration: none;
        font-weight: bold;
        font-size: 1rem;
        cursor: pointer;
      }

      .topnav .admin-controls a:hover {
        text-decoration: underline;
      }

      .topnav .user-info {
        position: absolute;
        right: 20px;
        display: flex;
        align-items: center;
        gap: 15px;
      }

      .topnav .user-info span {
        font-size: 1rem;
        color: white;
      }

      .btn-logout {
        padding: 8px 12px;
        background-color: #f44336;
        color: white;
        border: none;
        border-radius: 10px;
        font-size: 16px;
        cursor: pointer;
        display: flex;
        align-items: center;
        text-decoration: none;
        gap: 5px;
      }

      .btn-logout ion-icon {
        font-size: 20px;
      }

      .btn-logout:hover {
        background-color: #d32f2f;
      }

      .content {padding: 5px;}
      .card {
        background-color: white;
        box-shadow: 0px 0px 10px 1px rgba(140,140,140,.5);
        border: 1px solid #0c6980;
        border-radius: 15px;
      }
      .card.header {
        background-color: #0c6980;
        color: white;
        border-bottom-right-radius: 0px;
        border-bottom-left-radius: 0px;
        border-top-right-radius: 12px;
        border-top-left-radius: 12px;
      }
      .cards {
        max-width: 700px;
        margin: 0 auto;
        display: grid;
        grid-gap: 2rem;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      }
      .reading {font-size: 1.3rem;}
      .temperatureColor {color: #fd7e14;}
      .humidityColor {color: #1b78e2;}
    </style>
  </head>
  
  <body>
    <div class="topnav">
      <?php if ($user_role == 'admin'): ?>
        <div class="admin-controls">
          <a href="admin.php">Admin Controls</a>
        </div>
      <?php endif; ?>
      <h3>ESP8266 WITH MYSQL DATABASE</h3>
      <div class="user-info">
        <span>Welcome, <?php echo $username; ?></span>
        <!-- Thay nút Logout bằng biểu tượng Ion Logout -->
        <a href="logout.php" class="btn-logout">
          <ion-icon name="log-out-outline"></ion-icon> Logout
        </a>
      </div>
    </div>
    
    <br>
    
    <!-- Monitoring Data -->
    <div class="content">
      <div class="cards">
        <div class="card">
          <div class="card header">
            <h3 style="font-size: 1rem;">AGRICULTURAL IOT</h3>
          </div>
          
          <!-- Display temperature, humidity, etc. -->
          <h4 class="temperatureColor"><i class="fas fa-thermometer-half"></i> TEMPERATURE</h4>
          <p class="temperatureColor"><span class="reading"><span id="ESP8266_01_Temp"></span> &deg;C</span></p>
          <h4 class="humidityColor"><i class="fas fa-tint"></i> HUMIDITY</h4>
          <p class="humidityColor"><span class="reading"><span id="ESP8266_01_Humd"></span> &percnt;</span></p>

          <h4 class="temperatureColor"><i class="fas fa-tint"></i> SOIL HUMIDITY</h4>
          <p class="temperatureColor"><span class="reading"><span id="ESP8266_01_Soil"></span> &percnt;</span></p>
          <h4 class="humidityColor"><i class="fas fa-thermometer-half"></i> LIGHT INTENSITY </h4>
          <p class="humidityColor"><span class="reading"><span id="ESP8266_01_Light"></span> Lux</span></p>

          <h4 class="temperatureColor"><i class="fas fa-thermometer-half"></i> CARBON DIOXIDE</h4>
          <p class="temperatureColor"><span class="reading"><span id="ESP8266_01_Ppm"></span> Ppm</span></p>
        </div>
      </div>
    </div>
    
    <br>
    
    <!-- Last Time Received Data -->
    <div class="content">
      <div class="cards">
        <div class="card header" style="border-radius: 15px;">
            <h3 style="font-size: 0.7rem;">LAST TIME RECEIVED DATA FROM ESP8266 [ <span id="ESP8266_01_LTRD"></span> ]</h3>
            <button onclick="window.open('recordtable.php', '_blank');">Open Record Table</button>
        </div>
      </div>
    </div>

    <!-- Data Fetching Script -->
    <script>
      //------------------------------------------------------------
      document.getElementById("ESP8266_01_Temp").innerHTML = "NN"; 
      document.getElementById("ESP8266_01_Humd").innerHTML = "NN";
      document.getElementById("ESP8266_01_Soil").innerHTML = "NN"; 
      document.getElementById("ESP8266_01_Light").innerHTML = "NN";
      document.getElementById("ESP8266_01_Ppm").innerHTML = "NN";
      document.getElementById("ESP8266_01_LTRD").innerHTML = "NN";
      //------------------------------------------------------------
      
      Get_Data("esp8266_01");
      
      setInterval(myTimer, 5000);
      
      //------------------------------------------------------------
      function myTimer() {
        Get_Data("esp8266_01");
      }
      //------------------------------------------------------------
      
      //------------------------------------------------------------
      function Get_Data(id) {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
          if (this.readyState == 4 && this.status == 200) {
            const myObj = JSON.parse(this.responseText);
            if (myObj.id == "esp8266_01") {
              document.getElementById("ESP8266_01_Temp").innerHTML = myObj.temperature;
              document.getElementById("ESP8266_01_Humd").innerHTML = myObj.humidity;
              document.getElementById("ESP8266_01_Soil").innerHTML = myObj.soil;
              document.getElementById("ESP8266_01_Light").innerHTML = myObj.light;
              document.getElementById("ESP8266_01_Ppm").innerHTML = myObj.concentration;
              document.getElementById("ESP8266_01_LTRD").innerHTML = "Time: " + myObj.ls_time + " | Date: " + myObj.ls_date + " (dd-mm-yyyy)";
            }
          }
        };
        xmlhttp.open("POST", "getdata.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send("id=" + id);
      }
    </script>
  </body>
</html>
