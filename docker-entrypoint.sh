#!/bin/bash
set -e

# Create database directory if it doesn't exist
mkdir -p /var/www/database

# Ensure proper ownership and permissions
chown -R www-data:www-data /var/www/database
chmod -R 755 /var/www/database

# Touch database file if it doesn't exist and set permissions
if [ ! -f "$SQLITE_DATABASE_PATH" ]; then
    touch "$SQLITE_DATABASE_PATH"
    chown www-data:www-data "$SQLITE_DATABASE_PATH"
    chmod 664 "$SQLITE_DATABASE_PATH"
fi

# First arg is `-f` or `--some-option`
if [ "${1#-}" != "$1" ]; then
    set -- php-fpm "$@"
fi

exec "$@" 