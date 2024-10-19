#!/bin/sh
set -e

echo "Deploying application ..."

# Rebuilding containers
docker compose --file docker-compose-prod.yaml up -d
sleep 5

# Enter maintenance mode
docker exec -u 1001 app php artisan down

# Install dependencies based on lock file
docker exec -u 1001 app composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

# Migrate database
docker exec -u 1001 app php artisan migrate --force

# Clear caches
docker exec -u 1001 app php artisan route:cache
sleep 1
docker exec -u 1001 app php artisan config:cache

# Clear node_modules before running npm install
docker exec -u 0 app rm -rf node_modules
docker exec -u 0 app npm cache clean --force

# Install Node.js dependencies and run the build
docker exec -u 0 app npm install
docker exec -u 0 app npm run build

# Publish filament assets
docker exec -u 1001 app php artisan filament:assets

# Exit maintenance mode
docker exec -u 1001 app php artisan up

echo "Application deployed!"