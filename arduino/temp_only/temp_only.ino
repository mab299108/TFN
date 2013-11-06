#include <OneWire.h>
#include <DallasTemperature.h>

// Data wire is plugged into pin 3 on the Arduino
#define ONE_WIRE_BUS 3

// Setup a oneWire instance to communicate with any OneWire devices
OneWire oneWire(ONE_WIRE_BUS);

// Pass our oneWire reference to Dallas Temperature. 
DallasTemperature sensors(&oneWire);

DeviceAddress primary = { 0x28, 0xB2, 0xA1, 0x11, 0x04, 0x00, 0x00, 0xA0 };
DeviceAddress ambient = { 0x28, 0x5A, 0xB2, 0x11, 0x04, 0x00, 0x00, 0x1A };
int led = 13;
String TempOut;
char TOut[10];


void setup(void)
{
  // start serial port
  Serial.begin(9600);
  // Start up the library
  sensors.begin();
  // set the resolution to 10 bit (good enough?)
  sensors.setResolution(primary, 10);
  sensors.setResolution(ambient, 10);
  pinMode(led, OUTPUT);
}

float getTemp(DeviceAddress deviceAddress)
{
	float tempC = sensors.getTempC(deviceAddress);
	if (tempC == -127.00) {
		Serial.print("Error getting temperature");
	} else {
		float tempF = (1.8*tempC)+32;
	return tempF;
	}
}

void loop()
{ 
	digitalWrite(led, LOW);
	delay(5000);
	digitalWrite(led, HIGH); 
	sensors.requestTemperatures();

        TempOut = dtostrf(getTemp(primary),3,2,TOut);
	TempOut = "T:primary:"+TempOut;
	Serial.println(TempOut);

	delay(500);

        TempOut = dtostrf(getTemp(ambient),3,2,TOut);
	TempOut = "T:ambient:"+TempOut;
	Serial.println(TempOut);
}


