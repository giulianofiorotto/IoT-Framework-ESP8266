wifi.sta.config("SSID","Pass")
wifi.sta.connect()
tmr.delay(1000000)   -- wait 1,000,000 us = 1 second
print(wifi.sta.status())
print(wifi.sta.getip())
token = XCuMY8YQak989GdKJNHu 
dofile("config_server.lua")
