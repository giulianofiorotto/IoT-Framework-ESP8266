-- rele sample 
gpio.mode(0, gpio.OUTPUT) --GPIO 16
gpio.mode(5, gpio.OUTPUT) --GPIO 14
gpio.mode(6, gpio.OUTPUT) --GPIO 12
gpio.mode(7, gpio.OUTPUT) --GPIO 13

on = false

tmr.alarm(0, 3000, 1, function() 
    if on == false then
        gpio.write(0, gpio.HIGH)
        gpio.write(5, gpio.HIGH)
        gpio.write(6, gpio.HIGH)
        gpio.write(7, gpio.HIGH)
        on = true
    else
        gpio.write(0, gpio.LOW)
        gpio.write(5, gpio.LOW)
        gpio.write(6, gpio.LOW)
        gpio.write(7, gpio.LOW)
        on = false
    end
end )