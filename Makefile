start:
	docker compose -f docker-compose.yml -p general --env-file .env up --build -d

stop:
	docker compose -f docker-compose.yml -p general --env-file .env down