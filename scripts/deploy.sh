#!/bin/sh
set -e

echo "Deploying application ..."

# Rebuilding containers
docker compose --file docker-compose-prod.yaml up -d
sleep 10

# Enter maintenance mode
docker exec -u 1001 app php artisan down

# Install dependencies based on lock file
docker exec -u 1001 app composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

# Migrate database
docker exec -u 1001 app php artisan migrate --force

# Clear cache
docker exec -u 1001 app php artisan optimize

# Update Node.js and run the build
docker exec -u 0 app npm install
docker exec -u 0 app npm run build

# Exit maintenance mode
docker exec -u 1001 app php artisan up

echo "Application deployed!"