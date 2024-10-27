#include <WiFi.h>
#include <WiFiClient.h> 
#include <WebServer.h> 
#include <HTTPClient.h>
#include <EEPROM.h>
#include <DNSServer.h>
#include <WiFiManager.h> 
#include <ArduinoJson.h>
#include <Wire.h>
#include <U8g2lib.h>

// Khởi tạo OLED với ESP32 (SW I2C)
U8G2_SSD1306_128X64_NONAME_F_SW_I2C u8g2(U8G2_R0, 21, 22, U8X8_PIN_NONE); // SCL=21, SDA=22

// ESP32 hỗ trợ HardwareSerial
HardwareSerial mySerial(1);  // UART1: TX=GPIO17, RX=GPIO16

#define ON_Board_LED 2  // LED on ESP32 (GPIO2)
#define bt 15           // Nút bấm trên GPIO15

char auth[] = "Your token";
WiFiManager wifiManager;

float hum, temp, lux, ppm;
int soil_humi;
int moc[10];
String data1[10], data2[10];

void shownode() {
  char tempstr[5], humstr[5], Soil_humstr[5], lux_str[5], ppmstr[5];

  u8g2.clearBuffer();
  sprintf(tempstr, "%.1f", temp);
  u8g2.setFont(u8g2_font_ncenB08_tr);
  u8g2.drawStr(2, 10, "HIEN THI:");
  u8g2.drawStr(2, 20, "Nhiet do: ");
  u8g2.drawStr(55, 20, tempstr);
  u8g2.drawStr(78, 20, "*C");

  sprintf(humstr, "%.1f", hum);
  u8g2.drawStr(2, 30, "Do am khong khi: ");
  u8g2.drawStr(97, 30, humstr);
  u8g2.drawStr(118, 30, "%");

  sprintf(Soil_humstr, "%d", soil_humi);
  u8g2.drawStr(2, 41, "Do am dat: ");
  u8g2.drawStr(61, 41, Soil_humstr);
  u8g2.drawStr(73, 41, "%");

  sprintf(lux_str, "%.1f", lux);
  u8g2.drawStr(2, 51, "Cuong Do AS: ");
  u8g2.drawStr(81, 51, lux_str);

  sprintf(ppmstr, "%.1f", ppm);
  u8g2.drawStr(2, 61, "Chi so p.p.m: ");
  u8g2.drawStr(76, 61, ppmstr);

  u8g2.sendBuffer();
}

void nhan_du_lieu() {
  int j = 1, end;
  moc[0] = -1;
  
  if (mySerial.available()) {
    String input = mySerial.readString();
    Serial.println(input);
    
    for (int i = 0; i < input.length(); i++) {
      if (input[i] == '*') {
        end = i;
        break;
      }
      if (input[i] == ';') {
        moc[j++] = i;
      }
    }

    String *data = (input[0] == '1') ? data1 : data2;
    for (int t = 0; t < j; t++) {
      data[t] = (t < j - 1) ? input.substring(moc[t] + 1, moc[t + 1]) : input.substring(moc[t] + 1, end);
      Serial.println(data[t]);
    }
  }
}

void setup() {
  u8g2.begin();
  Serial.begin(115200);
  mySerial.begin(115200, SERIAL_8N1, 16, 17);  // TX=17, RX=16

  pinMode(bt, INPUT);
  pinMode(ON_Board_LED, OUTPUT);
  digitalWrite(ON_Board_LED, LOW);

  EEPROM.begin(512);
  u8g2.clearBuffer();
  u8g2.setFont(u8g2_font_ncenB08_tr);
  u8g2.drawStr(2, 20, "Wifi.......");
  u8g2.sendBuffer();
  delay(3000);

  if (digitalRead(bt) == HIGH) {
    u8g2.clearBuffer();
    u8g2.drawStr(2, 20, "Wifi Config:");
    u8g2.drawStr(2, 30, "192.168.4.1");
    u8g2.sendBuffer();
    wifiManager.resetSettings();
    delay(1000);
  }

  wifiManager.autoConnect("ESP32");
  Serial.println("Connected to WiFi");

  WiFi.mode(WIFI_STA);
  Serial.print("IP address: ");
  Serial.println(WiFi.localIP());
  
  u8g2.clearBuffer();
  u8g2.drawStr(2, 20, "CONNECTED WIFI..OK");
  u8g2.sendBuffer();
  digitalWrite(ON_Board_LED, LOW);
  delay(2000);
}

void loop() {
  WiFiClient client;
  nhan_du_lieu();

  hum = data1[1].toFloat();
  temp = data1[2].toFloat();
  soil_humi = data1[3].toInt();
  lux = data2[1].toFloat();
  ppm = data2[2].toFloat();

  Serial.printf("NODE 1: Humidity: %.1f, Temperature: %.1f, Soil Humidity: %d\n", hum, temp, soil_humi);
  Serial.printf("NODE 2: Light: %.1f, PPM: %.1f\n", lux, ppm);
  
  shownode();

  if (WiFi.status() == WL_CONNECTED) {
    HTTPClient http;
    String postData = "id=esp32_01";
    postData += "&temperature=" + String(temp);
    postData += "&humidity=" + String(hum);
    postData += "&soil=" + String(soil_humi);
    postData += "&light=" + String(lux);
    postData += "&concentration=" + String(ppm);

    http.begin(client, "http://192.168.206.162/ServerDoAnTotNghiep/update_sensor.php");
    http.addHeader("Content-Type", "application/x-www-form-urlencoded");

    int httpCode = http.POST(postData);
    String payload = http.getString();

    Serial.printf("HTTP Code: %d\n", httpCode);
    Serial.printf("Payload: %s\n", payload.c_str());

    http.end();
  }
}
