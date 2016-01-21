file.open("init.lua", "w+")
file.write([[
     wifi.sta.config("ssid","pass")
     wifi.sta.connect()
     print(wifi.sta.status())
     print(wifi.sta.getip())

     tmr.alarm(0, 1000, 1, function()
      if wifi.sta.status() == 5 then
          tmr.stop(0)
          -- Script per la registrazione del modulo sul server
          dofile("registrazione.lua")
      end
     end)
]]) file.close()