Инфа по бд
Adminer: порт 8081
Server: db
Username: user
Password: user_password
Database: mydb

Для начала нужно импортнуть дамп бд mydb.sql в админер

Если класс mysqli летит в\*\*\*\*:
docker exec -it parser bash
apt-get update
docker-php-ext-install mysqli
docker compose restart
