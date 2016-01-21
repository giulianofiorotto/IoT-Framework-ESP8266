local moduleName = ...
local M = {}
_G[moduleName] = M


-- Wifi static IP config 
cfg = {
    ip="192.168.0.11",
    netmask="255.255.255.0",
    gateway="192.168.0.1"
}

function readConfig()
    file.open("config.lua", "r")
    value = file.read()
    file.close()
    value = cjson.decode(value)
    for k,v in pairs(value) do 
        print(k)
        for key, value in pairs(v) do
            print("\t"..key..": ", value)
        end
    end
end

function setConfig()
    print("setConfig()")
    wifi.sta.setip(cfg)
    print(wifi.sta.status())
    --tmr.alarm(0, 2000, 0, function() print(wifi.sta.status()) sendRequest() end )
end

function M.configServer()

end

function M.connect(ssid, password)
    print("M.connect()")
    wifi.sta.config(ssid, password, 0)
    setConfig() 
    wifi.sta.connect()
    tmr.alarm(0, 1000, 0, function() 
        print(wifi.sta.status()) 
        if( reg == true ) then
            sendRequest()
        end
    end)
end

function sendRequest()
    print("sendRequest()")
     sk=net.createConnection(net.TCP, 0)
     sk:on("receive", function(sck, c) print(c) end )
     sk:on("connection", function(sck) 
        sck:send("GET /iot/public_api.php?task=moduleReg&name="..name.."&IP="..ip.."&token="..token.." HTTP/1.1\r\nHost: ".. wifi.sta.getip() .."\r\nConnection: keep-alive\r\nAccept: */*\r\n\r\n")
     end)
     sk:connect(80,"192.168.0.8")
end

function M.register(ssid, password, name, token)
    reg = true
    M.connect(ssid, password)
    print("stato: "..wifi.sta.status())
    ip = wifi.sta.getip()
    print("Creo il soket")
    print("IP: "..ip)
    tmr.alarm(0, 2000, 0, sendRequest() )
    print("Invio richiesta")
end

return M
