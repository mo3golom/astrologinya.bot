echo "exec migrate"
php artisan migrate --force
echo "config clear"
php artisan config:clear