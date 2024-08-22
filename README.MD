Adminer:
Server: db
Username: user
Password: user_password
Database: mydb

Если класс mysqli летит в\*\*\*\*:
docker exec -it parser bash
apt-get update
docker-php-ext-install mysqli
docker compose restart
