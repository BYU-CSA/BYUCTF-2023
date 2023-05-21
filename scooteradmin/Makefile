
ScooterAdmin:	scooteradmin.c
	gcc -o ScooterAdmin -fstack-protector scooteradmin.c
	cp ScooterAdmin src


Docker:	ScooterAdmin Dockerfile ctf.xinetd src/flag.txt src/start.sh
	docker build -t scooteradmin_container .

start:
	docker run -p 40012:40001 --detach --name scooteradmin_container scooteradmin_container:latest

stop:
	docker stop scooteradmin_container

clean: stop
	docker rm scooteradmin_container; docker image rm scooteradmin_container
