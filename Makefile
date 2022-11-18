build-image:
	docker build -t api:latest .

start-container:
	docker run -p 8000:8000 -d api:latest --env-file=.dev.env

stop-containers:
	docker stop $(docker ps -a -q -f ancestor=api)

remove-exited-containers:
	docker rm $(docker ps -a -q -f status=exited)