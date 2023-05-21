# National Park
Level - Easy/Medium

Description:
```
I heard if you get into a wild Pokemon battle something weird happens. Why not try opening the game yourself and getting into a battle?

[National Park.zip]
```

## Writeup
There is a weird audio file when you get into a wild Pokemon battle. This can either be discovered by looking at the most recently-modified file, or when you actually play the game. The file is in `Audio/BGM/Battle wild.ogg`, and opening this up in Audacity shows the dots/dashes of Morse Code. Stick it into a translator and you'll get `BYUCTFR0M_HACXS_RUL3`!

**Flag** - `BYUCTF{R0M_HACXS_RUL3}`