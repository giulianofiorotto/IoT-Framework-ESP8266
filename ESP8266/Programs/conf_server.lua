file.open("conf_server.lua", "w")
file.write([[
--GPIO0
led1 = 3 
--GPIO2
led2 = 4
gpio.mode(led1, gpio.OUTPUT)
gpio.mode(led2, gpio.OUTPUT)
srv=net.createServer(net.TCP)
srv:listen(80, function(conn)
     conn:on("receive", function(conn,payload)
          local htmlCode = ""
          local buf = "";
          local _, _, method, path, vars = string.find(payload, "([A-Z]+) (.+)?(.+) HTTP");
          if(method == nil)then
               _, _, method, path = string.find(payload, "([A-Z]+) (.+) HTTP");
          end
          local _GET = {}
          if (vars ~= nil)then
               for k, v in string.gmatch(vars, "(%w+)=(%w+)&*") do
                    _GET[k] = v
               end
          end

          if(_GET.luce ~= nil and _GET.luce ~= "") then
               -- bisogna aggiungere all'init il tipo di pin in base alle richieste per poi salvare i vari stati
               if(lightState == 0) then
                    gpio.write(led, gpio.HIGH)
                    lightState = 1
               else
                    gpio.write(led, gpio.LOW)
                    lightState = 0
               end
               htmlCode = "<h1> ESP8266 Web Server </h1>"

          end
          conn:send(htmlCode)
     end)
     conn:on("sent", function(conn) 
          conn:close() 
     end)
end)
]])
file.close()