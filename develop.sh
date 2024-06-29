#!/usr/bin/env bash

# Change current folder for the one where develop.sh is
cd $(dirname "$0")

read_var() {
    VAR=$(grep $1 $2 | xargs)
    IFS="=" read -ra VAR <<< "$VAR"

    if [ -n "$VAR" ]; then
      echo ${VAR[1]}
      else
      echo "$3"
		fi
}

# Retrieve needed env variables
export APP_ENV=$(read_var APP_ENV .env local)
export APP_PORT=$(read_var APP_PORT .env 8080)
export EXPOSE_DB_PORT=$(read_var EXPOSE_DB_PORT .env 3306)
export DB_ROOT_PASS=$(read_var DB_ROOT_PASS .env secret)
export DB_DATABASE=$(read_var DB_DATABASE .env laravel)
export DB_USERNAME=$(read_var DB_USERNAME .env laravel)
export DB_PASSWORD=$(read_var DB_PASSWORD .env laravel)
export VIRTUAL_HOST=$(read_var VIRTUAL_HOST .env laravel.localhost)

USERID=$(id -u)

COMPOSE="docker-compose "

if [ $# -gt 0 ];then
    # Ex: update application cache: artisan optimize
    # Ex: clear routes: artisan route:clear
    # Ex: clear route cache: artisan route:cache
    # Ex: clear config: artisan config:clear
    # Ex: clear cache config: artisan config:cache
    # Ex: clear views: artisan view:clear
    # Ex: clear cache views: artisan view:cache
    if [ "$1" == "art" ] || [ "$1" == "artisan" ]; then
        shift 1
        $COMPOSE run -u $USERID:$USERID --rm $TTY \
            -w /var/www/html \
            applarav \
            php artisan "$@"

    elif [ "$1" == "tinker" ]; then
        shift 1
        $COMPOSE run --rm $TTY \
            -w /var/www/html \
            -e "HOME=/var/www/html/storage/framework" \
            applarav \
            php artisan tinker "$@"

    # Needed ? docker run --rm -v ${pwd}:/app composer install
    elif [ "$1" == "composer" ]; then
        shift 1
        $COMPOSE run --rm $TTY \
            -w /var/www/html \
            applarav \
            composer "$@"

    elif [ "$1" == "mysqlroot" ]; then
        shift 1
        $COMPOSE exec dblarav mysql -u root -p$DB_PASSWORD

    elif [ "$1" == "mysql" ]; then
        shift 1
        $COMPOSE exec dblarav mysql -u $DB_USERNAME -p$DB_PASSWORD -D $DB_DATABASE "$@"

    # Ex: docker-compose exec php bash
    elif [ "$1" == "bash" ]; then
        shift 1
        $COMPOSE exec "$@" bash

    elif [ "$1" == "destroy" ]; then
        shift 1
        $COMPOSE down --volumes

    elif [ "$1" == "dev" ]; then
        shift 1
        $COMPOSE run -u $USERID:$USERID --rm $TTY \
            -w /var/www/html \
            appnod \
            npm run dev "$@"

    elif [ "$1" == "prod" ]; then
        shift 1
        $COMPOSE run -u $USERID:$USERID --rm $TTY \
            -w /var/www/html \
            appnod \
            npm run prod "$@"

    elif [ "$1" == "watch" ]; then
        shift 1
        $COMPOSE run -u $USERID:$USERID --rm $TTY \
            -w /var/www/html \
            appnod \
            npm run watch "$@"

    elif [ "$1" == "npm" ]; then
        shift 1
        $COMPOSE run -u $USERID:$USERID --rm $TTY \
            -w /var/www/html \
            appnod \
            npm "$@"

    # Ex: docker logs nginx
    elif [ "$1" == "logs" ]; then
        shift 1
        docker logs "$@"

    # Initial command: docker rm -fv $(docker ps -aq)
    elif [ "$1" == "clear-bind" ]; then
        shift 1
        docker rm -fv "$(docker ps -aq)"

    # /!\ Use with caution
    # This will remove:
    #   - all stopped containers
    #   - all networks not used by at least one container
    #   - all images without at least one container associated to them
    #   - all build cache
    elif [ "$1" == "prune-system" ]; then
        shift 1
        docker system prune -a

    # Ex: docker-compose up -d
    # Ex: docker-compose stop
    # Ex: docker-compose ps
    else
        $COMPOSE "$@"
    fi
else
    $COMPOSE ps
fi
