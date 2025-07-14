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
If you're getting errors with vendor file, you can run
```bash
docker run --rm -v $(pwd):/app --user 1000:1000 composer update && composer install
```
This command  will do the following: 
* make a temporary docker container 
* mount current working directory to /app directory in container
* use 1000 user id and group id (default uid and gid for your user on linux) so there is no permission denied errors
* run composer update and composer install commands
 - Enter container
```bash
docker compose exec -it laravel.test bash
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

If errors persist, check if Vite is running for frontend.

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
