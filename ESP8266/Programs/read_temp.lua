sensorType="dht11"          -- set sensor type dht11 or dht22
PIN = 7 --  data pin, GPIO13PIN = 7 --  data pin, GPIO13
humi=0
temp=0
--load DHT module for read sensor
function ReadDHT()
	dht=require("testdht")
	dht.read(PIN)
	chck=1
	h=dht.getHumidity()
	t=dht.getTemperature()
	if h==nil then h=0 chck=0 end
	if sensorType=="dht11"then
	humi=h/256
	temp=t/256
	else
	humi=h/10
	temp=t/10
	end
	fare=(temp*9/5+32)
	print("Humidity:    "..humi.."%")
	print("Temperature: "..temp.." deg C")
	-- release module
	dht=nil
	package.loaded["testdht"]=nil
end
ReadDHT()
