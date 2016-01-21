function makeString(l)
    if l < 1 then return nil end -- Check for l < 1
    local s = "" -- Start string
    for i = 1, l do
        n = math.random(32, 126) -- Generate random number from 32 to 126
        if n == 96 then n = math.random(32, 95) end
            s = s .. string.char(n) -- turn it into character and add to string
    end
    return s -- Return string
end

print(makeString(20));