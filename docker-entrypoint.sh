#!/bin/bash

echo "Instalando dependencias de Composer..."
composer install

echo "Optimizando la aplicación..."
php artisan optimize

echo "Iniciando servidor..."
php artisan serve --host=0.0.0.0 --port=${APP_PORT:-80}
