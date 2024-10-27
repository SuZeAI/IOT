#include "MQ135.h"
#include <Wire.h>
#include <BH1750.h>
#include <Servo.h>
#include <LiquidCrystal_I2C.h>

// Sử dụng UART2 (HardwareSerial) trên ESP32
#define RXD2 16  // RX
#define TXD2 17  // TX
HardwareSerial mySerial(2);  // UART2

LiquidCrystal_I2C lcd(0x3F, 16, 2);
BH1750 lightMeter;
Servo myservoR;
Servo myservoL;
MQ135 mq135_sensor = MQ135(A3);

#define M0 32
#define M1 33
#define SENSOR_PIN 10

int address = 2;
float lux, ppm;
int moc[10];
String data[10];

void setup() {
    Serial.begin(115200);
    mySerial.begin(115200, SERIAL_8N1, RXD2, TXD2);  // UART2 cho giao tiếp LoRa

    Wire.begin();
    lcd.init();
    lcd.backlight();
    lightMeter.begin();

    myservoR.attach(2);  
    myservoL.attach(4);  // Đảm bảo các chân này hỗ trợ PWM trên ESP32

    pinMode(M0, OUTPUT);
    pinMode(M1, OUTPUT);
    pinMode(13, OUTPUT);  // Đèn báo hiệu
    pinMode(12, OUTPUT);
    pinMode(11, OUTPUT);
    pinMode(SENSOR_PIN, INPUT);  // Cảm biến bật/tắt

    digitalWrite(M0, LOW);  // Chế độ bình thường cho LoRa
    digitalWrite(M1, LOW);
}

void guidulieu() {
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
                moc[j] = i;
                j++;
            }
        }

        if (input[0] == '0') {
            for (int t = 0; t < j; t++) {
                if (t < j - 1)
                    data[t] = input.substring(moc[t] + 1, moc[t + 1]);
                else
                    data[t] = input.substring(moc[t] + 1, end);
                Serial.println(data[t]);
            }
        }
    }
}

void light() {
    lux = lightMeter.readLightLevel();
    Serial.print("Light: ");
    Serial.print(lux);
    Serial.println(" lx");
}

void air() {
    ppm = mq135_sensor.getPPM();
    Serial.print("PPM: ");
    Serial.println(ppm);
}

void momai() {
    myservoR.write(90);
    myservoL.write(90);
    delay(2000);
}

void dongmai() {
    myservoR.write(0);
    myservoL.write(180);
    delay(2000);
}

void automode() {
    if (lux > 2000 || digitalRead(SENSOR_PIN) == 0) 
        dongmai();
    else 
        momai();

    if (lux < 10) 
        digitalWrite(13, HIGH);
    else if (lux > 50) 
        digitalWrite(13, LOW);
}

void display() {
    lcd.clear();
    lcd.setCursor(0, 0);
    lcd.print("A/s:");
    lcd.setCursor(5, 0);
    lcd.print(lux);
    lcd.setCursor(13, 0);
    lcd.print("lux");

    lcd.setCursor(0, 1);
    lcd.print("ppm:");
    lcd.setCursor(5, 1);
    lcd.print(ppm);
}

void loop() {
    light();
    air();
    display();

    mySerial.print(address);
    mySerial.print(";");
    mySerial.print(lux);
    mySerial.print(";");
    mySerial.print(ppm);
    mySerial.println("*");

    Serial.println("-------------------------------------------------------");

    guidulieu();
    automode();

    digitalWrite(11, HIGH);
    delay(2000);
    digitalWrite(11, LOW);
    delay(2000);
}
