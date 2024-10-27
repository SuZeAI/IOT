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

// Khai báo các loại màu và giá trị trong trò chơi UNO
const char* colors[] = {"Red", "Green", "Blue", "Yellow"};
const char* values[] = {"0", "1", "2", "3", "4", "5", "6", "7", "8", "9", 
                        "Skip", "Reverse", "Draw Two"};
const char* ssid = "UyenneZerosKyeneSuzeai";
const char* password = "Squart2024";
WiFiManager wifiManager;

void setup() {
  Serial.begin(115200);  // Khởi động Serial với baudrate 115200
  randomSeed(analogRead(A0));  // Tạo seed ngẫu nhiên từ chân analog A0
  Serial.println("Starting UNO Game...");
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
}

void loop() {
  // Chọn màu và giá trị ngẫu nhiên
  const char* color = colors[random(0, 4)];
  const char* value = values[random(0, 13)];

  // In ra thông tin lá bài
  Serial.print("You drew: ");
  Serial.print(color);
  Serial.print(" ");
  Serial.println(value);

  // Chờ 2 giây trước khi rút lá bài tiếp theo
  delay(1000);
}
