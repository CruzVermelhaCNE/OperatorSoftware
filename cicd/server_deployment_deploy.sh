#!/bin/sh
set -e

echo "Deploying application ..."

(php artisan down --message 'The app is being (quickly!) updated. Please try again in a minute.') || true
    git fetch origin deploy
    git reset --hard origin/deploy

    composer install --no-interaction --prefer-dist --optimize-autoloader

    npm install
    npm run production

    php artisan migrate --force

    php artisan queue:restart
    php artisan config:clear
    php artisan route:clear
    php artisan cache:clear
    php artisan view:clear

php artisan up

echo "Application deployed!"