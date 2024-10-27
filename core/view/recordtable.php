<?php
session_start();

// Kiểm tra nếu người dùng đã đăng nhập, nếu chưa, chuyển hướng về trang đăng nhập
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

?>
<!DOCTYPE HTML>
<html>
  <head>
    <title>ESP8266 WITH MYSQL DATABASE</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
      html {font-family: Arial; display: inline-block; text-align: center;}
      p {font-size: 1.2rem;}
      h4 {font-size: 0.8rem;}
      body {margin: 0;}

      /* ----------------------------------- TOPNAV STYLE */
      .topnav {
        overflow: hidden;
        background-color: #0c6980;
        color: white;
        font-size: 1.2rem;
        display: flex;
        justify-content: center; /* Đưa nội dung ra giữa */
        align-items: center;
        padding: 10px 20px;
        position: relative;
      }

      .topnav h3 {
        margin: 0;
        text-align: center;
      }

      .logout-btn {
          position: absolute;
          right: 20px;
          background-color: #f44336;
          color: white;
          padding: 10px 15px;
          text-align: center;
          text-decoration: none;
          font-size: 1rem;
          border: none;
          border-radius: 10px;
          cursor: pointer;
          transition: background-color 0.3s ease, transform 0.2s ease;
          box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
      }

      .logout-btn:hover {
          background-color: #d32f2f;
          transform: translateY(-2px);
      }

      .logout-btn:active {
          background-color: #b71c1c;
          transform: translateY(1px);
      }

      /* ----------------------------------- */

      /* ----------------------------------- TABLE STYLE */
      .styled-table {
        border-collapse: collapse;
        margin-left: auto; 
        margin-right: auto;
        font-size: 0.9em;
        font-family: sans-serif;
        min-width: 400px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
        border-radius: 0.5em;
        overflow: hidden;
        width: 90%;
      }

      .styled-table thead tr {
        background-color: #0c6980;
        color: #ffffff;
        text-align: left;
      }

      .styled-table th, .styled-table td {
        padding: 12px 15px;
        text-align: left;
      }

      .styled-table tbody tr:nth-of-type(even) {
        background-color: #f3f3f3;
      }

      td:hover {
        background-color: rgba(12, 105, 128, 0.21);
      }
      
      .styled-table tbody tr:nth-of-type(even):hover {
        background-color: rgba(12, 105, 128, 0.15);
      }
      
      /* ----------------------------------- BUTTON STYLE */
      .btn-group .button {
        background-color: #0c6980;
        color: white;
        padding: 5px 8px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 14px;
        cursor: pointer;
        margin-right: 5px;
      }

      .btn-group .button:hover {
        background-color: #094c5d;
      }

      .btn-group .button:disabled {
        background-color: #a0a0a0;
        pointer-events: none;
      }
      /* ----------------------------------- */
    </style>
  </head>
  
  <body>
    <div class="topnav">
      <h3>ESP8266 WITH MYSQL DATABASE</h3>
      <!-- Nút Logout -->
      <a href="logout.php" class="logout-btn">Logout</a>
    </div>
    
    <br>
    
    <h3 style="color: #0c6980;">ESP8266_01 RECORD DATA TABLE</h3>
    
    <table class="styled-table" id="table_id">
      <thead>
        <tr>
          <th>NO</th>
          <th>ID</th>
          <th>BOARD</th>
          <th>TEMPERATURE (°C)</th>
          <th>HUMIDITY (%)</th>
          <th>SOIL HUMIDITY(%)</th>
          <th>LIGHT INTENSITY (LUX)</th>
          <th>CARBON DIOXIDE (PPM)</th>
          <th>TIME</th>
          <th>DATE (dd-mm-yyyy)</th>
        </tr>
      </thead>
      <tbody id="tbody_table_record">
        <?php
          include '../database/database.php';
          $num = 0;
          $pdo = Database::connect();
          $sql = 'SELECT * FROM recorddata ORDER BY date, time';
          foreach ($pdo->query($sql) as $row) {
            $date = date_create($row['date']);
            $dateFormat = date_format($date,"d-m-Y");
            $num++;
            echo '<tr>';
            echo '<td>'. $num . '</td>';
            echo '<td class="bdr">'. $row['id'] . '</td>';
            echo '<td class="bdr">'. $row['board'] . '</td>';
            echo '<td class="bdr">'. $row['temperature'] . '</td>';
            echo '<td class="bdr">'. $row['humidity'] . '</td>';
            echo '<td class="bdr">'. $row['soil'] . '</td>';
            echo '<td class="bdr">'. $row['light'] . '</td>';
            echo '<td class="bdr">'. $row['concentration'] . '</td>';
            echo '<td class="bdr">'. $row['time'] . '</td>';
            echo '<td>'. $dateFormat . '</td>';
            echo '</tr>';
          }
          Database::disconnect();
        ?>
      </tbody>
    </table>
    
    <br>
    
    <div class="btn-group">
      <button class="button" id="btn_prev" onclick="prevPage()">Prev</button>
      <button class="button" id="btn_next" onclick="nextPage()">Next</button>
      <div style="display: inline-block; position:relative; border: 0px solid #e3e3e3;">
        <p style="position:relative; font-size: 14px;"> Table : <span id="page"></span></p>
      </div>
      <select name="number_of_rows" id="number_of_rows">
        <option value="10">10</option>
        <option value="25">25</option>
        <option value="50">50</option>
        <option value="100">100</option>
      </select>
      <button class="button" id="btn_apply" onclick="apply_Number_of_Rows()">Apply</button>
    </div>

    <br>
    
    <script>
      var current_page = 1;
      var records_per_page = 10;
      var l = document.getElementById("table_id").rows.length;
      
      function apply_Number_of_Rows() {
        var x = document.getElementById("number_of_rows").value;
        records_per_page = x;
        changePage(current_page);
      }
      
      function prevPage() {
        if (current_page > 1) {
          current_page--;
          changePage(current_page);
        }
      }
      
      function nextPage() {
        if (current_page < numPages()) {
          current_page++;
          changePage(current_page);
        }
      }
      
      function changePage(page) {
        var btn_next = document.getElementById("btn_next");
        var btn_prev = document.getElementById("btn_prev");
        var listing_table = document.getElementById("table_id");
        var page_span = document.getElementById("page");
        
        if (page < 1) page = 1;
        if (page > numPages()) page = numPages();

        [...listing_table.getElementsByTagName('tr')].forEach((tr) => {
          tr.style.display = 'none';
        });

        listing_table.rows[0].style.display = ""; // Display the title row

        for (var i = (page - 1) * records_per_page + 1; i < (page * records_per_page) + 1; i++) {
          if (listing_table.rows[i]) {
            listing_table.rows[i].style.display = "";
          }
        }
        
        page_span.innerHTML = page + "/" + numPages() + " (Total Number of Rows = " + (l - 1) + ") | Number of Rows : ";

        btn_prev.disabled = page == 1;
        btn_next.disabled = page == numPages();
      }
      
      function numPages() {
        return Math.ceil((l - 1) / records_per_page);
      }
      
      window.onload = function() {
        var x = document.getElementById("number_of_rows").value;
        records_per_page = x;
        changePage(current_page);
      };
    </script>
  </body>
</html>
