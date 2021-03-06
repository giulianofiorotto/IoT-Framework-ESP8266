print("init")
dofile("test.lua")
collectgarbage()
print(node.heap())
sv=net.createServer(net.TCP, 10)    -- 30s time out for a inactive client
 -- server listen on 80, if data received, print data to console, and send "hello world" to remote.
sv:listen(80,function(c)
  c:on("receive", function(c, pl) 
  print("pl: " .. pl) 
  i, j = string.find(pl, "=")
  fine, n = string.find(pl, " HTTP")
  print("PIN: \t")
  print(string.sub(pl, i+1, fine)) 
  c:close() end)
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
