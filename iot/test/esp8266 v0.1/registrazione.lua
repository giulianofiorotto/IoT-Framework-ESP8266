file.open("registrazione.lua", "w+")
file.write([[
-- Registrazione sul server del modulo 
function reg() 
     sk=net.createConnection(net.TCP, 0)
     sk:on("receive", function(sck, c) print(c) end )
     sk:on("connection", function() 
          sk:send("GET /iot/public_api.php?task=moduleReg&name="..name.."&IP="..ip.."&token="..token.." ")
          sk:send("HTTP/1.1\r\nHost: ESP8266-12\r\nConnection: keep-alive\r\nAccept: */*\r\n\r\n")
     end)
     sk:connect(80,"192.168.0.8")
end

name = "Camera_Giuliano" -- nome del modulo 
token = "XCuMY8YQak989GdKJNHu" -- token per la comunicazione
-- Attendo di aver un IP
while wifi.sta.status() < 5 do
     print(wifi.sta.status())
end
ip = wifi.sta.getip()
print(ip)
-- Registro il modulo sul DB
reg()
-- Modifico il file init.lua per i successivi reboot
file.open("init.lua", "w+")
file.writeline('wifi.sta.config("ssid","pass")')
file.writeline('wifi.sta.connect()')
file.writeline('tmr.delay(1000000)   -- wait 1,000,000 us = 1 second')
file.writeline('print(wifi.sta.status())')
file.writeline('print(wifi.sta.getip())')
file.writeline('token = '..token..' ')
file.writeline('dofile("config_server.lua")')
file.close()
collectgarbage() 
-- Faccio partire il server per la configurazione dal server
dofile("config_server.lua")]])
file.close()
