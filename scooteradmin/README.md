# ScooterAdmin1
Level - Easy

Description:
```
Nobody can mess with my beauties because I wrote the authentication routines myself!

The source and binary is hidden in a directory from ScooterWeb, with the directory name equal to unused secret message :)

`nc byuctf.xyz 40012`
```

## Writeup
0x32 vs 32 (dev typo?) and unfortunately placed stack variables mean you can overflow the password prompt buffer. This lets you provide both halves of the password being compared. You can write past the end of the user-provided-password buffer and it will happen to nuke the file descriptor int to the already-open-but-not-yet-read file containing the password we're comparing against. There are a couple of ways to abuse this but the easy way is to write a bunch of `\x00` in the buffer and over the file descriptor - this means that both passwords get read from stdin, and they are both `\x00` - so they compare as equal. Alternatively, you can supply a password for both halves with the right amount of padding - taking care to use `\x00` for just the file descriptor.

This is also automated in `solve1.py`.

```bash
$ python3 -c 'print("\x00" * 60, end="")' | nc byuctf.xyz 40012
ScooterAdminPortal - Please enter your password: ScooterAdmin: Access Granted. Welcome Admin.
byuctf{XXXXXXXXXXXXXXXXXXXXXXXXXXXXXX}
```

**Flag** - `byuctf{agree_with_yourself_on_the_password!}`

# ScooterAdmin2
Level - Medium

Description:
```
You've got access to a pre-production system! What bugs lurk? Find the bugs, (ab)use them, and read flag2.txt!

`nc byuctf.xyz 40012`
```

## Writeup
Flag 2 is on the stack somewhere, saved in the frame from the original call to `main`, and can be retrieved either by brute force or by testing locally and finding the offset. The solution is automated in `solve2.py`.

**Flag** - `byuctf{OhNoYouCanReadButCanYouWrite?}`


# ScooterAdmin3
Level - Hard

Description:
```markdown
Nice! Now that you've been able to READ flag2, can you WRITE to be able to see `./flag3.txt` on the filesystem?

`nc byuctf.xyz 40012`
```

## Writeup
Since all binary measures are in place, the arbitrary write you have with `printf` + `%n` has to be used carefully. It can't overwrite a GOT address, but it can be used to overwrite the frame pointer when returning from `mainloop()` to `main()`! The intended solution is to leak the libc base address, leak the stack frame pointer, then overwrite the frame pointer to an address obtained by libc + one_gadget to start up a shell. To write the 64-bit address, it's written in 6 1-byte increments.

It may also be possible by making a custom ROP chain 1 byte at a time, or a ret2system. The solution is automated in `solve3.py`.

# Hosting
`ScooterAdmin` can be built by `gcc -o ScooterAdmin -fstack-protector scooteradmin.c`, or with `make`.

This challenge should be a Docker container that runs the executable `make` every time someone connects on port 40012. All the proper files are included in here. The command to build the docker container is (when located inside of this directory):

```bash
sudo docker build -t scooteradmin_container .
sudo docker network create -d bridge scooteradmin
```

The command to start the challenge is:

```bash
sudo docker run -p 40012:40001 --detach --name scooteradmin_container --network scooteradmin scooteradmin_container:latest
```

The command to stop the challenge (since CTRL+C won't work) is:

```bash
sudo docker stop scooteradmin_container
```