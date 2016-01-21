file.open("first_setup.lua", "w")
file.write([[

file.open("config.lua", "r")
ssid = file.read('\r')
pass = file.read('\r')
name = file.read('\r')
file.close()

file.open("init.lua", "w")
file.writeline('wifi.setmode(wifi.STATION) wifi.sta.config('..ssid..','..pass..')dofile("conf_server.lua")')
file.close()
node.restart()
]])
file.close()