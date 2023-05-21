# Sluethr
Level - Medium

Description:
```markdown
Welcome agents, one and all! We have an incredibly important mission for you, should you choose to accept it. One of our intelligence agents, at great risk to her own life, stole information about a flag hidden in a secret website from an enemy base. The website is [sluethr.byuctf.xyz](http://sluethr.byuctf.xyz/). However, one can only retrieve the flag if they access that website from a certain virtual machine, which our agent also stole. Apparently these agents are noobs, because they didn't change the default credentials on it (username and password are both `vagrant`). Can you set up the VM and retrieve the flag?

https://byu.box.com/s/mbf597jfqpxix7wtjj8oxioo2coocge8
```

## Writeup
Because of the instructions, the first time challengers access the website will most likely be from the virtual machine. When they do this, the DNS name will not resolve. The fact that their web browser has the correct DNS name is their first clue that something in the DNS protocol is messed up. Step one when troubleshooting DNS issues is to try accessing reaching the domain name from multiple computers. If they try to go to the website from their personal laptop, they will reach the correct site. Once they realize it resolves for one machine and not the other, it will prompt them to look at DNS resolution on the VM, eventually reaching the `hosts` file. Challengers have the whole flag as soon as they find the file, as the last 5 digits are located there in a comment.

**Flag** - `byuctf{e8e823dbeac61d61b26565c518907549}`