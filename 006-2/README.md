# 006 II
Level - Easy

Description:
```
You did well recovering the first password, but unfortunately as our hackers were accessing their system the enemies IDS spotted them and they were blocked. We know that Janus reset his password, because we intercepted a different password hash on their network. We can expect that he made this one a bit trickier to guess. Can you crack it in time for us to stop him?

Flag format - `byuctf{cracked_password}`

[006_2.txt]
```

## Writeup
This password most likely won't be found on any standard lists, but it an important part of Alec's backstory in the movie. Challengers will need to create a wordlist based on information about Alec, most likely using a web crawler like cewl. Challengers might also find it on a massive list such as the Torrent file (https://crackstation.net/crackstation-wordlist-password-cracking-dictionary.htm), or on crackstation's online database. The hash is a SHA1, which is different than the previous challenge.

**Flag** - `byuctf{Arkhangelsk}`