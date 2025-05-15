#!/bin/bash

echo "🔄 Clearing Laravel caches and compiled files..."

php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan event:clear
php artisan clear-compiled

echo "✅ Cleared."

echo "⚡ Rebuilding caches for performance..."

php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

echo "✅ Rebuild complete."
