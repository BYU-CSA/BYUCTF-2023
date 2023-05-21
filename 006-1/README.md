# 006 I
Level - Easy

Description:
```
In this James Bond themed CTF challenge, you're tasked with cracking the password of Janus, and evil crime lord, to access his encrypted files containing crucial information about the organization's plans for a devastating attack. Time is of the essence, and the fate of millions rests on your ability to crack the password and stop the impending disaster.

Flag format - `byuctf{cracked_password}`

[006_1.txt]
```

## Writeup
This password is one found in the rockyou list, and the hashing algorithm is MD5. Challengers can use Google to recognize the hash format and either hashcat or John the ripper to crack it.

**Flag** - `byuctf{brittishhottie}`