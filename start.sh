#!/bin/bash

set -e

echo "Stopping old containers..."
#docker compose down

echo "Building images..."
sudo docker-compose build --no-cache

echo "Starting containers..."
sudo docker-compose up -d

echo "Waiting MySQL..."
sleep 15

echo "Installing Composer packages..."
sudo docker-compose exec php composer install

if [ ! -f ".env" ]; then
    cp .env.example .env
fi

echo "Generate key..."
sudo docker-compose exec php php artisan key:generate

echo "Run migration..."
sudo docker-compose exec php php artisan migrate --force

npm install
npm run build

echo "Cache config..."
sudo docker-compose exec php php artisan config:clear
sudo docker-compose exec php php artisan route:clear
sudo docker-compose exec php php artisan view:clear
sudo docker-compose exec php php artisan optimize

echo ""
echo "========================================="
echo "Laravel is ready!"
echo "http://localhost:8000"
echo "========================================="
