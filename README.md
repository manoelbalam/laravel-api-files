configure database on .env
php artisan install:api
php artisan storage:link
php artisan migrate
php atisan serve

curl -X 'GET'   'http://127.0.0.1:8000/api/uploads'   -H 'accept: application/json'   -H 'Content-Type: application/json'
