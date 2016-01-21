ssid = "prova"
pass = "mele"
token = "XCuMY8YQak987GdKJNHu"
name = "Camera_mia"

function connessione()
	conn_mod=require("connModule")
	conn_mod.connect(ssid, pass)
	print("connesso")
	conn_mod=nil
	package.loaded["connModule"]=nil
	print("end")
	-- body
end

function registrazione()
     print("registrazione")
     conn_mod=require("connModule")
     conn_mod.register(ssid, pass, name, token)
     conn_mod=nil
     package.loaded["connModule"]=nil
     print("registrato")
end
print(node.heap())
connessione()
collectgarbage()
print(node.heap())

print("fine programma")
