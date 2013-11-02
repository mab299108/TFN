import serial
import datetime

ser=serial.Serial('/dev/tty.usbmodem1411',9600)
count = 0
myData={}
nameList=[]
typeList=[]

def addData( vName, vValue ):
	global myData
	try:
		myData[vName]=myData[vName]+float(vValue)
		myData[vName+"count"]=myData[vName+"count"]+1
	except KeyError:
		# Key is not present
		myData[vName]=float(vValue)
		myData[vName+"count"]=1
	pass
def formatOutput(i):
	global myData
	global nameList
	myKey = nameList[i]
	vOut = myData[myKey]/myData[myKey+"count"]
	myData[myKey] = 0
	myData[myKey+"count"] = 0
	return str(vOut)

def getTime():
	return str(datetime.datetime.now().time())

def buildLists(vName, vItem):
	global nameList
	global typeList
	if vName not in nameList:
		nameList.append(vName)
		typeList.append(vItem)


print("Starting:"+getTime())
while 1:

	#print(count)

	SerIn = ser.readline()
	vItem,vName,vValue = SerIn.split(":")
	
	if vItem == "T":
		addData( vName, vValue )
		buildLists(vName, vItem)

	elif vItem == "F":
		addData( vName, vValue )
		buildLists(vName, vItem)
		
	else:
		print("other"+str(SerIn)+ " at "+getTime())	

	count=count+1

	if count == 5:
		print("averages  at "+getTime())
		for i in xrange(len(nameList)):
			print(typeList[i]+": "+nameList[i]+" - "+formatOutput(i))

		#print(nameList)
		count = 0
