# MVP BlockChain App With Simple API

### Run project
```shell
git clone https://github.com/OlegMarko/php-simple-blockchain.git
cd php-simple-blockchain
cp .env.example .env
composer install
php artisan generate:blockchain_with_block
php -S localhost:8888 -t public
```

### Run project via Docker.
```shell
git clone https://github.com/OlegMarko/php-simple-blockchain.git
cd php-simple-blockchain
docker-compose up -d --build
docker exec php-fpm-blockchain php artisan generate:blockchain_with_block
```

### API methods

 - Add new block
```shell
curl --location 'http://localhost:8888/blockchain/create' --form 'data="data for new block"'
```

- Get blockchain
```shell
curl --location 'http://localhost:8888/blockchain/show'
```
