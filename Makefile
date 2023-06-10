build:
	docker-compose up -d --build
	docker exec php-fpm-blockchain php artisan generate:blockchain_with_block

run:
	docker-compose up -d

clean:
	docker-compose down
	docker-compose rm
	rm -rf ./data/pg/*
	docker rm -f $(shell docker ps -a -q)
	docker rmi -f $(shell docker images -q)
	docker volume rm $(shell docker volume ls -q)
