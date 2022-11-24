build-image:
	docker build -t api:latest .

start-container:
	docker run --name ddProjects -p 8000:8000 -d --restart unless-stopped api:latest

stop-containers:
	docker stop $$(docker ps -a -q -f ancestor=api)

remove-exited-containers:
	docker rm $$(docker ps -a -q -f status=exited)