start-containers:
	docker compose -f docker/docker-compose.yml -p general --env-file .env up --build -d

stop-containers:
	docker compose -f docker/docker-compose.yml -p general --env-file .env down