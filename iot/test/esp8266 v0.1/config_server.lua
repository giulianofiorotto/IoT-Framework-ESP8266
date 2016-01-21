file.open("config_server.lua", "w+")
file.write([[
-- config_server.lua server per la configurazione degli IO del modulo

srv=net.createServer(net.TCP)
srv:listen(80,function(conn)
     conn:on("receive",function(conn,payload)
          print(payload)
          conn:send("<h1> Hello, NodeMcu.</h1>")
     end)
     conn:on("sent",function(conn) 
          conn:close() 
     end)
end)]])
file.close()