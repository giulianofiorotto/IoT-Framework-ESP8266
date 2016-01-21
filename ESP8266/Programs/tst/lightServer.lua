gpio.mode(0, gpio.OUTPUT) --GPIO 16
gpio.mode(5, gpio.OUTPUT) --GPIO 14
gpio.mode(6, gpio.OUTPUT) --GPIO 12
gpio.mode(7, gpio.OUTPUT) --GPIO 13

function inverti(stato)
    if stato == 1 then
        return 0
    else
        return 1
    end
end

sv=net.createServer(net.TCP, 10)    -- 30s time out for a inactive client
 -- server listen on 80, if data received, print data to console, and send "hello world" to remote.
sv:listen(80,function(c)
  c:on("receive", function(c, pl) 
    print("pl: " .. pl) 
    i, j = string.find(pl, "=")  
    k, f = string.find(pl, "HTTP")        
    pin = tonumber(string.sub(pl, i+1, k-1))
    print("PIN: "..pin) 
    
    if pin == 0 then
        gpio.write(0, inverti(gpio.read(0)))
    end
    
    c:close() 
  end)
  c:send("<p>ESP8266 ESP-12</p>")
  c:send("HTTP/1.1 200 OK\r\n") 
  c:send("Server: ESP8266 Lua\r\n") 
  c:send("Access-Control-Allow-Origin: http://PublicIP\r\n") 
  c:send("Access-Control-Allow-Methods: GET\r\n") 
  c:send("Keep-Alive: *\r\n") 
  c:send("Connection: Keep-Alive\r\n")  
  c:send("Accept: */*\r\n") 
  c:send("User-Agent: Mozilla/4.0\r\n") 
  c:send("\r\n") 
end) 
