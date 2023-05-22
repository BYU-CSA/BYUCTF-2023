# VMception
Level - Medium

Description:
```markdown
A flag is hidden deep inside this vm. Log in with `cosmo:password` to get started.

https://byu.box.com/shared/static/y73dexzq53bajeqant0ypk16ifinu1m1.vdi
```

## Writeup
After getting the VM set up, you are able to log in with the provided credentials (user - `cosmo`, password - `password`). Upon login, you are presented with a basically empty home directory.

If you look around, you find that there is another user (`notcosmo`) also on the system. If you look at their files, you can see they have a `.ssh` folder with modified permissions. This allows you to copy their `.ssh keys` into your home directory, secure them, and connect over ssh to `localhost` as `notcosmo`. A sudo misconfig allows `notcosmo` to run any commands as root without a password. Next, you have to start a root shell (easiest is through `sudo su` as `notcosmo` because `root` and `notcosmo` both have passwords). Any starting of a root shell will decrypt the `not_your_vm.vdi` file to `your_vm.vdi` inside `/root`.

After getting to this point, any method can be used to extract the decrypted vm to the host system. Upon running it, you find that the boot process has been corrupted. It should also be noted that both vm disks have the same uuid, requiring some creativity to attach it to virtualbox. It is intended at this point that you in one way or another mount the second vm. The command I used was `vboximg-mount -i 33b3a5f7-69b3-4c46-9788-e22038f38900 -o allow_root ./temp`. The flag is in the `/root` directory.

Commands (after login):
```bash
cp ../notcosmo/.ssh ./
chmod -R 700 ./.ssh
ssh notcosmo@localhost
sudo su
# (power off machine)

# (on host machine)
mkdir drive
mkdir partition
vboximg-mount -i 33b3a5f7-69b3-4c46-9788-e22038f38900 -o allow_root ./drive # may require some virtual box config to work right
cd drive
sudo mount vol0 ../partition
cd ../partition
sudo su
cp root/your_vm.vdi ../
exit
cd ..
sudo umount partition
sudo umount drive
mv your_vm.vdi byuctf_1.vdi
vboximg-mount -i 33b3a5f7-69b3-4c46-9788-e22038f38900 -o allow_root ./drive
cd drive
sudo mount vol0 ../partition
cd ../partition
sudo su
cat ./root/flag.txt
```

## Alternate solutions/Cheese prevention
If you mount the first vm and try to extract the 2nd vm without starting a root shell inside the 1st vm, the you will find the 2nd vm hidden in `/var/cache/apt` as `dpkg.bin`. It will also still be encrypted. The second vm can be decrypted on the host os if you discover the command used to decrypt it in root's `.bashrc` and copy out both the vm and the `ls` binary. But at this point, the solver is going to enough work to qualify this as an alternate solution.

The second vm is intended to be mounted. I guess someone could grep for a file with the word "flag" in it, but it isn't exactly hidden. The second flag is just in the `/root` directory.

**Flag** - `byuctf{vmsarecool}`