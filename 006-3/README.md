# 006 III
Level - Medium

Description:
```
We've finally got a foothold in Janus' network, and we're ready to take them down. This time we've recovered a small batch of passwords that seem to belong to various henchmen in his organisation. We'll need all of them cracked so we can do as much damage as possible this time around. Are you up to the task?

Flag format - `byuctf{password1_password2_password3_password4}` (same order as given in the file below)

[006_3.txt]
```

## Writeup
The four passwords provided all follow the same format, "goldeneye" + three random digits (ex: goldeneye123). This is a pretty standard (and unfortunately insecure) corporate policy put in place by IT/Cyber teams who want to make password management easy. Two of the passwords can be found on the rockyou list, which will give challengers an idea of what they're working with. It's up to them to create a custom wordlist with all possible three digit combonations. The hashes are SHA512, which take longer to crack but are pretty recognizable.

**Flag** - `byuctf{goldeneye007_goldeneye641_goldeneye069_goldeneye159}`