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