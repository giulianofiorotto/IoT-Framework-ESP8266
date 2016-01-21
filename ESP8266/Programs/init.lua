file.open("init.lua", "w")
file.writeline([[
     wifi.setmode(wifi.SOFTAP)
     dofile("web_server_setup.lua")
]])
file.close()

