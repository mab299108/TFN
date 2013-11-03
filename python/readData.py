import serial
import datetime
import pymysql

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

def takeAverage(i):
	global myData
	global nameList
	myKey = nameList[i]
	vOut = myData[myKey]/myData[myKey+"count"]
	myData[myKey] = 0
	myData[myKey+"count"] = 0
	print(myKey+str(vOut))
	return str(vOut)

def returnSum(i):
	global myData
	global nameList
	myKey = nameList[i]
	vOut = myData[myKey]
	myData[myKey] = 0
	myData[myKey+"count"] = 0
	print(myKey+str(vOut))
	return str(vOut)	

def getTime():
	return str(datetime.datetime.now().time())

def buildLists(vName, vItem):
	global nameList
	global typeList
	if vName not in nameList:
		nameList.append(vName)
		typeList.append(vItem)

def dbaseConn():
	conn = pymysql.connect(host='localhost',  user='user1', passwd='password1', database='dataLogger')
	return conn	

def insertTemp(vTemp):
	conn = dbaseConn()
	cur = conn.cursor()
	cur.execute("INSERT into tempData (temp, time) VALUES ("+str(vTemp)+", NOW() )")
	cur.execute("COMMIT")

def insertFlow(vFlow):
	conn = dbaseConn()
	cur = conn.cursor()
	cur.execute("INSERT into flowData (flow, time) VALUES ("+str(vFlow)+", NOW() )")
	cur.execute("COMMIT")		


print("Starting:"+getTime())
while 1:

	SerIn = ser.readline()
	vItem,vName,vValue = SerIn.split(":")
	
	if vItem in ["T","F"]:
		addData( vName, vValue )
		buildLists(vName, vItem)		
	else:
		print("other"+str(SerIn)+ " at "+getTime())	

	count=count+1

	if count == 6:
		print("averages  at "+getTime())
		for i in xrange(len(nameList)):
			#print(typeList[i]+": "+nameList[i]+" - "+takeAverage(i))
			if typeList[i] == "T":
				insertTemp(takeAverage(i))
			elif typeList[i] == "F":
				insertFlow(returnSum(i))	

		#print(nameList)
		count = 0
