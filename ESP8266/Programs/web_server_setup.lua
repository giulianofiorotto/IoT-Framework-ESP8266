-- A simple http server
--GPIO2
print('Server in esecuzione')
led = 4 
lightState = 0
function makeString(l)
    if l < 1 then return nil end -- Check for l < 1
    local s = "" -- Start string
    for i = 1, l do
        n = math.random(32, 126) -- Generate random number from 32 to 126
        if n == 96 then n = math.random(32, 95) end
            s = s .. string.char(n) -- turn it into character and add to string
    end
    return s -- Return string
end

gpio.mode(led, gpio.OUTPUT)
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

          if(_GET.config ~= "" and _GET.SSID ~= nil and _GET.nome ~= "" and _GET.nome ~= nil)then
               file.open("init.lua", "w")
               file.writeline('module_name = "'.._GET.nome..'"')
               file.writeline('wifi.setmode(wifi.STATION)')
               file.writeline('wifi.sta.config("SSID", "Pass")')
               file.writeline('dofile("web_server_setup.lua")');
               file.close()
               conn:close() 
               conn=net.createConnection(net.TCP, 0)  
               conn:on("receive", function(conn, payload) print(payload) end) 
               conn:connect(443,'192.168.0.8')  
               conn:send("GET /public_api.php?task=moduleReg&name=".._GET.nome.."&IP="..wifi.ap.getip().."&token="..makeString(20).." HTTP/1.1\r\n") 
               conn:send("Host: prowl.weks.net\r\n")  
               conn:send("Accept: */*\r\n") 
               conn:send("User-Agent: Mozilla/4.0 (compatible; esp8266 Lua; Windows NT 5.1)\r\n") 
               conn:send("\r\n")  
               node.restart()
          elseif(_GET.luce ~= nil and _GET.luce ~= "") then
               -- bisogna aggiungere all'init il tipo di pin in base alle richieste per poi salvare i vari stati
               if(lightState == 0) then
                    gpio.write(led, gpio.HIGH)
                    lightState = 1
               else
                    gpio.write(led, gpio.LOW)
                    lightState = 0
               end
               htmlCode = "<h1> ESP8266 Web Server </h1>"
          else
               file.open("configPage.lua", "r")
               htmlCode = file.read()
               file.close()
          end
          conn:send(htmlCode)
     end)
     conn:on("sent", function(conn) 
          conn:close() 
     end)
end)



