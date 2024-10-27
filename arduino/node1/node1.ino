#include <SoftwareSerial.h>
#include <DHT.h>
#include <string.h>
#include <LiquidCrystal_I2C.h>
#include <Wire.h>
LiquidCrystal_I2C lcd(0x27, 16, 2);
SoftwareSerial mySerial(6, 7);  //TX, RX
#define M0 8
#define M1 9
#define DHTPIN 5
#define maybom 2
#define quat 13
DHT dht(DHTPIN, DHT11);
float temp;
float hum;
int soil_humi, address = 1;
int moc[10];
String data[10];
byte degree[8] = {
  0B01110,
  0B01010,
  0B01110,
  0B00000,
  0B00000,
  0B00000,
  0B00000,
  0B00000
};
void setup() {
  Serial.begin(115200);
  mySerial.begin(115200);
  Wire.begin();
  lcd.init();
  lcd.backlight();
  dht.begin();
  /*set up cac chan che do cua lora */
  pinMode(M0, OUTPUT);
  pinMode(M1, OUTPUT);
  digitalWrite(M0, LOW);  // Set 2 chân M0 và M1 xuống LOW
  digitalWrite(M1, LOW);  // để hoạt động ở chế độ Normal


  /* set up cac chan cua cam bien*/
  pinMode(5, OUTPUT);  // cam bien DHT11
  pinMode(A0, INPUT);  // Cam bien do am dat
  pinMode(A1, INPUT);
  /* set up cac chan thiet bi chap hanh*/
  pinMode(2, OUTPUT);   // relay dieu khien may bom
  pinMode(3, OUTPUT);   // den bao hieu do am dat
  pinMode(10, OUTPUT);  // relay dieu khien quat
  pinMode(11, OUTPUT);  // đèn báo hiệu
  pinMode(12, OUTPUT);
  pinMode(13, OUTPUT);
  delay(1000);
}
void dht11_func() {
  hum = dht.readHumidity();
  temp = dht.readTemperature();
  Serial.print("Humidity: ");
  Serial.println(hum);
  Serial.print("Temperature: ");
  Serial.println(temp);
}
void Do_do_am_dat() {
  int value0 = analogRead(A0);
  int value1 = analogRead(A1);
  soil_humi = (map(value0, 200, 1023, 100, 0) + map(value1, 200, 1023, 100, 0)) / 2;
  Serial.print("Soil humidity: ");
  Serial.println(soil_humi);
}


void automode() {
  if (soil_humi < 60) {
    digitalWrite(maybom, LOW);
  } else if (soil_humi > 80) {
    digitalWrite(maybom, HIGH);
  }
  if (temp > 30) digitalWrite(quat, LOW);
  else digitalWrite(quat, HIGH);
}
void display() {
  lcd.clear();
  lcd.createChar(1, degree);
  lcd.setCursor(0, 1);
  lcd.print("t:");
  lcd.setCursor(2, 1);
  lcd.print(temp);
  lcd.setCursor(7, 1);
  lcd.write(1);
  lcd.setCursor(8, 1);
  lcd.print("C");
  lcd.setCursor(0, 0);
  lcd.print("h: ");
  lcd.setCursor(2, 0);
  lcd.print(hum);
  lcd.setCursor(7, 0);
  lcd.print("%");
  lcd.setCursor(10, 0);
  lcd.print("Sh:");
  lcd.setCursor(13, 0);
  lcd.print(soil_humi);
  lcd.setCursor(15, 0);
  lcd.print("%");
}
void guidulieu() {
  int j = 1;
  int end;
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
        moc[j] = i;
        j++;
      }
    }
    if (input[0] == '0') {
      for (int t = 0; t < j; t++) {
        if (t < j - 1) data[t] = input.substring(moc[t] + 1, moc[t + 1]);
        else data[t] = input.substring(moc[t] + 1, end);
        Serial.println(data[t]);
      }
    }
  }
}
void loop() {


  dht11_func();
  Do_do_am_dat();
  mySerial.print(address);
  mySerial.print(";");
  mySerial.print(hum);
  mySerial.print(";");
  mySerial.print(temp);
  mySerial.print(";");
  mySerial.print(soil_humi);
  mySerial.println("*");
  Serial.println("-------------------------------------------------------");
  guidulieu();
  display();
  int a = data[0].toInt();
  int che_do = data[1].toInt();
  int mb = data[2].toInt();
  int ps = data[3].toInt();
  automode();
  digitalWrite(11,HIGH);
  delay(2000);
  digitalWrite(11,LOW);
  delay(2000);
}
