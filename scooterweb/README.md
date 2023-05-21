# ScooterWeb
Level - Easy

Description:
```
There are 8 secret messages embedded somewhere in this site, combine all but one to get the flag. Only true scooter aficionados will be able to get it!

http://byuctf.xyz:40013/
```

## Writeup
All the images in the carousel have a comment iTxt/zTxt field set to a 192-hex char string (hence the hidden messages). This can be found using `exiftool` or `identify -verbose *png | grep comment:` (utilizing ImageMagick). If you XOR all but the 5th PNG together (this will have to be brute forced), you'll get the flag! This is automated in `solve.py`.

A not-really-easter-egg is that images 1,2,3 are my images, of my and my friend's scooters while I was an undergrad at BYU in the 90s - that is kiwanis park on 9th east in group photo.

**Flag** - `byuctf{ThisAintNoKickOrMobilityScooter}`

## Hosting
This challenge should be a Docker container that runs Apache with static HTML files on port 4000x. All the proper files are included in here. The command to build the docker container is (when located inside of this directory):

```bash
sudo docker build -t scooterweb .
sudo docker network create -d bridge scooterweb
```

The command to start the challenge is:

```bash
sudo docker run -p 40013:80 --detach --name scooterweb --network scooterweb scooterweb:latest
```

The command to stop the challenge (since CTRL+C won't work) is:

```bash
sudo docker stop scooterweb
```