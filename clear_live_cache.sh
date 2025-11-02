#!/bin/bash

# Clear all Laravel caches on live server
# Server: root@72.60.78.167
# Path: /home/lumanglipa.com/public_html

echo "========================================="
echo "Clearing Laravel Caches on Live Server"
echo "========================================="

ssh root@72.60.78.167 << 'ENDSSH'
cd /home/lumanglipa.com/public_html

echo ""
echo "1. Clearing view cache..."
php artisan view:clear

echo ""
echo "2. Clearing application cache..."
php artisan cache:clear

echo ""
echo "3. Clearing config cache..."
php artisan config:clear

echo ""
echo "4. Clearing route cache..."
php artisan route:clear

echo ""
echo "5. Running migrations (if any pending)..."
php artisan migrate --force

echo ""
echo "========================================="
echo "All caches cleared successfully!"
echo "========================================="
ENDSSH

echo ""
echo "Cache clearing complete. Please test the live server."
