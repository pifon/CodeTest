# Game
TSN coding challenge

##Building local Laravel application with Bitnami image 
(https://hub.docker.com/r/bitnami/laravel/)

Pull ready image and spin container

`docker network create laravel-network`

`docker volume create --name mariadb_data`

```
docker run -d --name mariadb \
  --env ALLOW_EMPTY_PASSWORD=yes \
  --env MARIADB_USER=bn_myapp \
  --env MARIADB_DATABASE=bitnami_myapp \
  --network laravel-network \
  --volume mariadb_data:/bitnami/mariadb \
  bitnami/mariadb:latest
```

```
docker run -d --name laravel \
  -p 80:8000 \
  --env DB_HOST=mariadb \
  --env DB_PORT=3306 \
  --env DB_USERNAME=bn_myapp \
  --env DB_DATABASE=bitnami_myapp \
  --network laravel-network \
  --volume ${PWD}/my-project:/app \
  bitnami/laravel:latest
```

.env needs:

```
#DB_CONNECTION=sqlite
DB_CONNECTION=mariadb
DB_HOST=mariadb
DB_PORT=3306
DB_DATABASE=bitnami_myapp
DB_USERNAME=bn_myapp
```

At this point http://localhost should display laravel 11 welcome page

Enter container:

  `docker exec -it laravel bash`

Add needed parts:

  `composer require laravel/ui`
  
  `php artisan ui:auth`

  `npm install`

  `composer require laravel/sanctum`

  `php artisan install:api`

Move files included in this repo into the 'my-project' folder.

Migration needed to create games table

  `php artisan migrate`

Seed tables

  `php artisan db:seed --class=GamesTableSeeder`
  
  `php artisan db:seed --class=UsersTableSeeder`


Tests assumes that DB is seeded

  `php artisan test`


Display Game is available: 

http://localhost/api/game/1
