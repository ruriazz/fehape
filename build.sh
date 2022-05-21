#!/bin/bash
set -e

docker-compose up -d --build --always-recreate-deps &&
docker-compose exec fehape composer install

exit 1