## Информация
Тестовое задание

## Стэк технологий
* Docker
* php 8
* mysql 8
* nginx

## Запуск проекта на docker

`cd docker`

`docker-compose build`

`docker-compose up -d`

`cd ../`

`docker exec -i testparser-mysql-1 mysql -uroot -proot testparserDB < lotsinfo.sql`

внести домен testparser.test в hosts 127.0.0.1 testparser.test




