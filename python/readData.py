import serial
import datetime

ser=serial.Serial('/dev/tty.usbmodem1411',9600)
count = 0
bulkTemp = 0
bulkFlow = 0
myData={}

print("Starting:"+str(datetime.datetime.now().time()))
while 1:

	#print(count)

	SerIn = ser.readline()
	vItem,vName,vValue = SerIn.split(":")
	
	if vItem == "T":
		#print("temp")
		#print(vValue)
		bulkTemp=bulkTemp+float(vValue)
		myData[vName]=bulkTemp

	elif vItem == "F":
		bulkFlow = bulkFlow+float(vValue)
		#assumes count iterated by temp
		myData[vName]=bulkFlow    

	else:
		print("other"+str(SerIn)+ " at "+str(datetime.datetime.now().time()))	

	count=count+1

	if count == 5:
		divisor = count/2
		print("averages  at "+str(datetime.datetime.now().time()))
		print("TEMP: - "+str(bulkTemp/divisor))
		print("FLOW: - "+str(bulkFlow/divisor))
		count = 0
		bulkTemp = 0
		bulkFlow = 0