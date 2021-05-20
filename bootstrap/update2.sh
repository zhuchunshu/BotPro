git reset --hard
git pull
composer install
php artisan migrate --force
