# WEB_Final_Project

A volleyball team management system developed as a web application.

[README 中文版](./README_tw.md)

[README English](./README.md)

## Getting Started

This section explains how to set up and run the project using Docker.

### Requirements

Before running this project, make sure you have installed:

- [Git](https://git-scm.com/)
- [Docker Desktop](https://www.docker.com/products/docker-desktop/)

**Note:** You do not need to install PHP, Composer, Node.js, or MySQL on your local machine. Docker will provide and manage these environments through containers.

Make sure Docker Desktop is running before executing the following commands.

---

## Installation

### 1. Clone the Repository

Clone the project repository:

```powershell
git clone https://github.com/kapiso-TW/WEB_Final_Project.git
```

### 2. Enter the Project Directory

Navigate to the `main` directory:

```powershell
cd WEB_Final_Project\main
```

**Important:** All following commands should be executed in the `main` directory, where the `compose.yaml` file is located.

### 3. Configure Environment Variables

Laravel uses environment variables to configure the application, database connection, and other services.

Create your own `.env` file by copying the example configuration:

```powershell
Copy-Item .env.example .env
```

Open the `.env` file and make sure the following settings are configured:

```env
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=volleyball
DB_USERNAME=volleyball_app
DB_PASSWORD=volleyball_password

SESSION_DRIVER=file
CACHE_STORE=file
QUEUE_CONNECTION=sync
```

**Note:**
- `DB_HOST=db` refers to the MySQL service name defined in `compose.yaml`.
- `DB_PORT=3306` is the MySQL port used for communication between Docker containers.
- These database settings must match the environment variables configured in `compose.yaml`.
- Do not commit your `.env` file to Git because it may contain sensitive configuration values.

### 4. Build and Start Docker Services

Build the required Docker images and start all services:

```powershell
docker compose up -d --build
```

During the first startup, Docker will:

1. Download the required base images, such as PHP, MySQL, Nginx, and Node.js.
2. Build the PHP application image according to the `Dockerfile`.
3. Install the PHP extensions required by Laravel.
4. Create and start the containers defined in `compose.yaml`.
5. Set up the network that allows the containers to communicate with each other.

The project uses the following services:

| Service | Description |
|---|---|
| Laravel / PHP-FPM | Runs the Laravel application and handles PHP requests. |
| Nginx | Acts as the web server and forwards PHP requests to PHP-FPM. |
| MySQL | Stores the application's data. |
| Node.js / Vite | Installs frontend dependencies and compiles frontend assets such as SCSS and JavaScript. |

### 5. Install PHP Dependencies

After the containers have started, install the PHP packages required by Laravel:

```powershell
docker compose exec app composer install
```

Composer reads `composer.json` and `composer.lock` and installs the required PHP dependencies into the `vendor` directory.

### 6. Configure Laravel Permissions

Laravel needs write permissions for its storage and cache directories.

Run the following command to configure the permissions:

```powershell
docker compose exec -u root app sh -lc "chown -R www-data:www-data storage bootstrap/cache"
```

This command assigns ownership of the required directories to `www-data`, which is the user running PHP-FPM.

### 7. Generate the Application Key

Generate a unique application key for Laravel:

```powershell
docker compose exec -u www-data app php artisan key:generate
```

Laravel uses this key for encryption and other security-related operations.

**Note:** This command should normally only be executed once when setting up a new local environment. Keep the generated `APP_KEY` in your local `.env` file.

### 8. Run Database Migrations

Create the database tables required by the application:

```powershell
docker compose exec -u www-data app php artisan migrate
```

Laravel will execute the migration files in the `database/migrations` directory and create the corresponding tables in MySQL.

If new migrations are added during development, run this command again to update your database structure.

### 9. Check Docker Services

Check whether all containers are running:

```powershell
docker compose ps
```

Make sure the required services are running before accessing the application.

If a service is not running properly, check its logs:

```powershell
docker compose logs -f
```

Press `Ctrl + C` to stop viewing the logs. This does not stop the containers.

### 10. Open the Application

Once all services are running, open your browser and visit:

[http://localhost:8000](http://localhost:8000)

The Laravel application should now be accessible.

---

## Docker Commands

The following commands are useful during development.

### Start the System

Start all services:

```powershell
docker compose up -d
```

Docker will reuse existing images and containers whenever possible.

If you have modified the `Dockerfile` or need to rebuild the application image, use:

```powershell
docker compose up -d --build
```

### Stop the System

Stop and remove the containers and network created by Docker Compose:

```powershell
docker compose down
```

**Important:** This command does not delete your project files or the database data stored in the Docker volume.

### Temporarily Stop the System

If you only want to stop the running containers without removing them, use:

```powershell
docker compose stop
```

### Restart Stopped Services

Start the previously stopped containers:

```powershell
docker compose start
```

### View Logs

View logs from all services:

```powershell
docker compose logs -f
```

View logs from a specific service:

```powershell
docker compose logs -f app
```

You can replace `app` with `nginx`, `db`, or `node` to inspect another service.

### Check Service Status

```powershell
docker compose ps
```

### Execute Commands Inside a Container

You can execute commands inside a running container using:

```powershell
docker compose exec app <command>
```

For example, to check the PHP version inside the application container:

```powershell
docker compose exec app php -v
```

---

## Important Notes

- Each developer should create their own local `.env` file.
- Do not commit `.env` to Git. Only commit `.env.example`.
- Do not run `docker compose down -v` unless you intentionally want to delete the database volume and its stored data.
- The first-time setup requires building the Docker images, installing Composer dependencies, generating the application key, and running database migrations.
- When pulling updates from Git, check whether new dependencies or database migrations have been added.
- If `composer.json` or `composer.lock` changes, run `docker compose exec app composer install`.
- If new database migrations are added, run `docker compose exec -u www-data app php artisan migrate`.
- If the `Dockerfile` changes, rebuild the application image using `docker compose up -d --build`.

---

## Project Information

**Author:** kapiso  
**Date:** 2026/11/25