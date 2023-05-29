<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

## Setup

- cp .env.example .env
- configure db
- composer install
- php artisan key:generate
- php artisan migrate --seed
- npm i
- npm run dev


## Testing

- cp .env .env.testing
- configure testing db
- vendor/bin/phpunit
