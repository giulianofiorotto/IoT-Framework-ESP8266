file.open("config.lua", "w+")
file.write([[
{
    "wifi": {
        "ssid": "test",
        "pass": "mele",
        "ip": "192.168.0.11",
        "netmask": "255.255.255.0",
        "gateway": "192.168.0.1"
    },
    "module": {
        "name": "Camera_mia",
        "token": "XCuMY8YQak987GdKJNHu"
    },
    "luce": {
        "pin": 12,
        "state": false
    },
    "temp": {
        "pin": 11,
        "temp": 22.5,
        "humidity": 60
     }
}]])
file.close()