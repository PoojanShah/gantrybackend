## How to star up an application locally
docker-compose -f docker-compose.dev.yaml up -d </br>
**Pay attention - port 80 and 3306 should not be busy!**
docker exec app composer install
docker exec app php artisan migrate
docker exec app php artisan db:seed
## Login to admin panel
Go to http://localhost/login </br>
Use following credentials 
**email** - k.makienko1990@gmail.com, **password** - 12345
## Connection to database 
Host - 0.0.0.0</br>
User - mysql-laravel</br>
Port  - 3306</br>
Database - laravel</br>
DB password - 9AYp9wiW7xoYPJWP