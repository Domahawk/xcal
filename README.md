<h1 align="center">Xcal</h1>
<h2 align="center">Currency converter to Euro using HNB data for conversion rates</h2>

## Running locally

### Requirements
 - Docker compose

### First run

 - Build and run the project
```bash
docker compose up -d
```
 - Enter container
```bash
docker compose exec -it laravel.test
```
 - Install dependencies
```bash
composer install
npm install
```
 - Get .env file
 - Run migrations
   - from within the container 
    ```bash
    php artisan migrate
    ```
    - outside of container using sail
    ```bash
    sail atisan migrate
    ```

Now you are ready to run project locally!

### Running after setup
Since `sail` is a project dependency you can use it to run the project instead of `docker compose`.

`sail` is an alias for `vendor/bin/sail`, which is basically an alias for `docker compose`. 

Start project
```bash
sail up -d
```
Kill project
```bash
sail down
```
