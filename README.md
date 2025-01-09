```shell
cd ./.docker
docker composer -p imagegal up

docker exec imagegal-php-1 php artisan migrate --seed
```

The console command will output a token, this token should be set in .env file as APP_RUNNING_TOKEN value

Since we have no login page here, this bearer token for auth and async file upload has to be somehow issued

Tests can be ran with

```shell
docker exec imagegal-php-1 php artisan migrate --seed
```

Underneath there is a pcov installed, it also allows to generate a code coverage
