#include <ESP8266WiFi.h>
#include <WiFiClient.h> 
#include <ESP8266WebServer.h>
#include <ESP8266HTTPClient.h>
#include <EEPROM.h>
#include <DNSServer.h>
#include <WiFiManager.h> 
#include <SoftwareSerial.h>
#include <ArduinoJson.h>
#include <Wire.h>


// SoftwareSerial mySerial(D7, D8); // TX, RX
// #define ON_Board_LED D3 
// #define bt D5

// //======================================== SSID and Password of your WiFi router.
// const char* ssid = "UyenneZerosKyeneSuzeai";
// const char* password = "Squart2024";
// WiFiManager wifiManager;

// //======================================== Variables for HTTP POST request data.
// String postData = ""; //--> Variables sent for HTTP POST request data.
// String payload = "";  //--> Variable for receiving response from HTTP POST.

// //======================================== 
// float hum, temp, lux, ppm;
// int soil_humi;
// int moc[10];                  // mảng lưu số dữ liệu trong 1 khung truyền
// String data1[10], data2[10];  // mảng lưu dữ liệu trong một khung truyền

// void shownode() {
//   char tempstr[2];
//   char humstr[2];
//   char Soil_humstr[2];
//   char lux_str[2];
//   char ppmstr[2];
//   sprintf(tempstr, "%.1f", temp);      //chuyển nhiệt độ sang kiểu xâu kí tự
//   //
//   sprintf(humstr, "%.1f", hum);  // chuyển độ ẩm sang kiểu xâu kí tự
//   //
//   sprintf(Soil_humstr, "%d", soil_humi);  // chuyển độ ẩm sang kiểu xâu kí tự
//   //
//   sprintf(lux_str, "%.1f", lux);      //chuyển nhiệt độ sang kiểu xâu kí tự
//   //
//   sprintf(ppmstr, "%.1f", ppm);
// }


// void nhan_du_lieu() {
//   int j = 1;
//   int end;
//   moc[0] = -1;
//   if (mySerial.available()) {
//     String input = mySerial.readString();
//     Serial.println(input);
//     for (int i = 0; i < input.length(); i++) {
//       if (input[i] == '*') {
//         end = i;
//         break;
//       }
//       if (input[i] == ';') {
//         moc[j] = i;
//         j++;
//       }
//     }
//     if (input[0] == '1') {
//       for (int t = 0; t < j; t++) {
//         if (t < j - 1) data1[t] = input.substring(moc[t] + 1, moc[t + 1]);
//         else data1[t] = input.substring(moc[t] + 1, end);
//         Serial.println(data1[t]);
//       }
//     }
//     if (input[0] == '2') {
//       for (int t = 0; t < j; t++) {
//         if (t < j - 1) data2[t] = input.substring(moc[t] + 1, moc[t + 1]);
//         else data2[t] = input.substring(moc[t] + 1, end);
//       }
//     }
//   }
// }

void setup() {
  Serial.begin(115200);
  mySerial.begin(115200);
  pinMode(D2, OUTPUT);
  // pinMode(ON_Board_LED,OUTPUT); //--> On Board LED port Direction output.
  // digitalWrite(ON_Board_LED, LOW); //--> Turn off Led On Board.
 //---------------------------------------- Make WiFi on ESP32 in "STA/Station" mode and start connecting to WiFi Router/Hotspot
  // long t = millis();
  // pinMode(bt, INPUT); //bt wire D5 and 3.3V
  // EEPROM.begin(512);
  // Serial.begin(9600);
  // delay(3000);
  // if (digitalRead(bt) == 1) {
  // wifiManager.resetSettings();
  // delay(1000);
  // }
  wifiManager.autoConnect("ESP8266");
  Serial.println("connected...ok :)");
  ////////////////////
  // delay(1000);
  // Serial.begin(115200);
  WiFi.mode(WIFI_OFF);        
  // delay(1000);
  WiFi.mode(WIFI_STA);        
  WiFi.begin(WiFi.SSID(),WiFi.psk());    
  Serial.println("");
  Serial.print("Connecting");
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println("");
  Serial.print("Connected to ");
  Serial.print("IP address: ");
  Serial.println(WiFi.localIP());
  Serial.println("connected...ok :)");
    //........................................ 
  //---------------------------------------- 
  // digitalWrite(ON_Board_LED, LOW); //--> Turn off the On Board LED when it is connected to the wifi router.
  // delay(2000);
  // while (!Serial); // Đợi kết nối với Serial Monitor để hoạt động ở chế độ Normal  
}


void loop() {
  Serial.println("NODE 1: ");
  // WiFiClient client;
  // // nhan_du_lieu();
  //   /*nhận data node 1*/
  // hum = data1[1].toFloat();
  // temp = data1[2].toFloat();
  // soil_humi = data1[3].toInt();
  // Serial.println("NODE 1: ");
  // Serial.print("Recieved Humidity:  ");
  // Serial.println(hum);
  // Serial.print("Recieved Temperature:  ");
  // Serial.println(temp);
  // Serial.print("Recieved Soil_humidity:  ");
  // Serial.println(soil_humi);
  // Serial.println("-----------------------------------------");
  // /*Nhận data node 2 */
  // lux = data2[1].toFloat();
  // ppm = data2[2].toFloat();
  // Serial.println("NODE 2: ");
  // Serial.print("Recieved light:  ");
  // Serial.println(lux);
  // Serial.print("Recieved ppm:  ");
  // Serial.println(ppm);
  // Serial.println("-----------------------------------------");
  // // shownode();
  // if(WiFi.status()== WL_CONNECTED) {
  //   HTTPClient http;  //--> Declare object of class HTTPClient.
  //   int httpCode;     //--> Variables for HTTP return code.
    
  //   //........................................ Process to get LEDs data from database to control LEDs.
  //   postData = "id=esp8266_01";
  //   payload = "";
  
  //   digitalWrite(ON_Board_LED, HIGH);
  //   Serial.println();
  //   Serial.println("---------------getdata.php");
  //   // Example : http.begin("http://192.168.0.0/ESP32_MySQL_Database/Final/getdata.php");
  //   http.begin(client,"http://192.168.206.162/ServerDoAnTotNghiep/getdata.php");;  //--> Specify request destination
  //   http.addHeader("Content-Type", "application/x-www-form-urlencoded");        //--> Specify content-type header
   
  //   httpCode = http.POST(postData); //--> Send the request
  //   payload = http.getString();     //--> Get the response payload
  
  //   Serial.print("httpCode : ");
  //   Serial.println(httpCode); //--> Print HTTP return code
  //   Serial.print("payload  : ");
  //   Serial.println(payload);  //--> Print request response payload
    
  //   http.end();  //--> Close connection
  //   Serial.println("---------------");
  //   digitalWrite(ON_Board_LED, LOW);
  //   postData = "id=esp8266_01";
  //   //float hum, temp, val_lux, val_soil;
  //   postData += "&temperature=" + String(temp);
  //   postData += "&humidity=" + String(hum);
  //   postData += "&soil=" + String(soil_humi);
  //   postData += "&light=" + String(lux);
  //   postData += "&concentration=" + String(ppm);
  //   payload = "";
  
  //   digitalWrite(ON_Board_LED, HIGH);
  //   Serial.println();
  //   Serial.println("---------------updatesData.php");
  //   // Example : http.begin("http://192.168.0.0/ESP32_MySQL_Database/Final/updateDHT11data_and_recordtable.php");
  //   http.begin(client,"http://192.168.206.162/ServerDoAnTotNghiep/update_sensor.php");  //--> Specify request destination
  //   http.addHeader("Content-Type", "application/x-www-form-urlencoded");  //--> Specify content-type header
   
  //   httpCode = http.POST(postData); //--> Send the request
  //   payload = http.getString();  //--> Get the response payload
  
  //   Serial.print("httpCode : ");
  //   Serial.println(httpCode); //--> Print HTTP return code
  //   Serial.print("payload  : ");
  //   Serial.println(payload);  //--> Print request response payload
    
  //   http.end();  //Close connection
  //   Serial.println("---------------");
  //   digitalWrite(ON_Board_LED, LOW);
  //   //........................................   
  // }
}
