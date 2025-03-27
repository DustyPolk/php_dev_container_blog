# PHP Development Environment with SQLite

A Docker-based development environment for PHP with SQLite support.

## Features

- PHP 8.2 with FPM
- Nginx as web server
- SQLite database support
- Composer for dependency management
- Volume mapping for persistent development

## Setup Instructions

1. Make sure Docker and Docker Compose are installed on your system
2. Clone this repository or create the files as shown
3. Create the required directories:
   ```bash
   mkdir -p src database nginx
   ```
4. Start the environment:
   ```bash
   docker-compose up -d
   ```
5. Access your application at http://localhost:8080

## Directory Structure

- `src/`: Place your PHP code here
- `database/`: SQLite database files (will be created automatically)
- `nginx/`: Contains Nginx configuration

## Environment Variables

- `SQLITE_DATABASE_PATH`: Path to SQLite database file inside the container

## SQLite Access

The SQLite database file is located at `./database/database.sqlite` on your host
and at `/var/www/database/database.sqlite` inside the PHP container.

## Customization

- Modify `php.Dockerfile` to install additional PHP extensions or packages
- Adjust `docker-compose.yml` to change ports, volumes, or add more services
- Edit `nginx/default.conf` to customize the web server configuration 