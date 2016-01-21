gpio.mode(6,gpio.INT, gpio.PULLUP)
function pin1cb(level)
  if level == 1 then print("Porta aperta") else print("Porta chiusa") end
end
gpio.trig(6, "up",pin1cb)