-- A simple http client
a = false
conn=net.createConnection(net.TCP, 0) 
conn:on("receive", function(conn, payload) 
    if a == false then
        i, j = string.find(payload, "gpio")  
        program = string.sub(payload, i)
        file.open("webserver.lua", "w+")
        file.write(program)
        file.close()
        dofile("webserver.lua")
        a = true
    end
end )
conn:connect(80,"192.168.0.8")
conn:send("GET /iot/webserver.html HTTP/1.1\r\nHost: 192.168.0.13\r\n"
    .."Connection: keep-alive\r\nAccept: */*\r\n\r\n")

