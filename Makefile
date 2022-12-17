build-image:
	docker build -t api:latest .

start-container:
	docker run -v `pwd`/vendor:/root/vendor --env-file .env --name ddProjects -p 8000:8000 -d --restart unless-stopped api:latest

stop-container:
	docker stop $$(docker ps -a -q -f ancestor=api)

remove-exited-containers:
	docker rm $$(docker ps -a -q -f status=exited)

rebuild-with-remove:
	make stop-container
	make remove-exited-containers
	make build-image
	make start-container