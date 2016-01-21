--GPIO2
led = 4
gpio.mode(led, gpio.OUTPUT)
while 1 do
     gpio.write(led, gpio.HIGH)
     tmr.delay(1000000)
     gpio.write(led, gpio.LOW)
end