String TempOut;
String FlowOut;

int FlowCount = 0;

void setup(){
  Serial.begin(9600);

}

short getTemp(String sensorID){
 short baseTemp = 0;
 short randTemp = random(65,75);
 short vOut = baseTemp + randTemp;
  
 return vOut; 
}

short getFlow(String sensorID){
 
 short vOut = random(0,100);
 //short vOut = baseTemp + randTemp;
  
 return vOut; 
}

void loop(){
  delay(5000);
  TempOut = "T:alpha:"+String(getTemp("alpha"));
//  Serial.print("hello\n");
  Serial.println(TempOut);
  delay(1000);
  FlowOut = "F:zeta:"+String(getFlow("zeta"));
  Serial.println(FlowOut);
}
