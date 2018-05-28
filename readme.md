## About

Project is written on [Laravel framework (version 5.6)](https://laravel.com/docs/5.6)

Used libraries: [Bootstrap 4](https://getbootstrap.com/), [DataTables](https://datatables.net/)

## Deploy

1. Clone repository
2. Run "composer update" in the project directory.
3. Change database connection credentials in the .env file in the project directory:
   DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD
4. Run migrations "php artisan migrate" in the project directory.

## Routes
- **/** - clicks list
- **/link** - helper page for testing tracking link
- **/click** - tracking link
- **/success/[CLICK_ID]** - success url
- **/error/[CLICK_ID]** - success url
- **/bad-domains** - bad domains list

## Tests

Run "vendor/bin/phpunit" in the project directory.