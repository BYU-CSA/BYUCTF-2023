#!/usr/bin/env python3
import secrets
import subprocess
import os

# initialize
img_count = 8
msg_len=96
flag=b"byuctf{ThisAintNoKickOrMobilityScooter}" 
flag +=  b"\x00"*(msg_len - len(flag))


# generate secret dir for 5.png
secret_dir = secrets.token_hex(msg_len)
print("The secret directory name is", secret_dir)
os.system(f"mkdir html/{secret_dir}")
os.system(f"cp scooteradmin.c html/{secret_dir}/scooteradmin.c")
os.system(f"cp ScooterAdmin html/{secret_dir}/ScooterAdmin")


print(f"\nI will embed all of the secret messages as png ztxt chunks in {img_count} png images. We'll put the secret directory in 5.png\n")

# generate 6 random messages
strs = []
for i in range(img_count-2):
    strs.append(secrets.token_bytes(msg_len))

# using the flag, generate the last message needed
result=flag
for i in range(len(strs)):
    result = bytes(x ^ y for x, y in zip(result, strs[i]))

# combine all of the messages into a list
all = [x.hex() for x in strs[0:4]] + [secret_dir] + [x.hex() for x in strs[4:(img_count-1)]] + [result.hex()]


# embed the messages into the pngs
for i in range(1,img_count+1):
    r = subprocess.run(["convert", f"origpics/{i}.png", "-set", "comment", all[i-1], f"html/pics/{i}.png"]) 
    print(i,r)