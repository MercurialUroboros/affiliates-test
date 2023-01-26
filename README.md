## Without sail

PHP 8 and composer required

Run `composer install`,  `composer dump-autoload` and `php artisan serve` to run the project.

Server running on localhost:8000

To run tests

`php artisan test`

## With sail

https://laravel.com/docs/9.x/sail#installing-composer-dependencies-for-existing-projects


navigate to root of the repo then

docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php82-composer:latest \
    composer install --ignore-platform-reqs

./vendor/bin/sail up