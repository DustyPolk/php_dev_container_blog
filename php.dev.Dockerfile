FROM php:8.2-fpm

# Install dependencies
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    unzip \
    sqlite3 \
    libsqlite3-dev \
    git \
    vim \
    nano \
    # Add more development tools here
    iputils-ping \
    procps

# Install PHP extensions
RUN docker-php-ext-install \
    pdo \
    pdo_sqlite \
    zip

# Install Xdebug for debugging (optional)
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Create database directory with proper permissions
RUN mkdir -p /var/www/database && \
    chmod -R 777 /var/www/database

# Configure PHP for development
RUN { \
        echo 'error_reporting = E_ALL'; \
        echo 'display_errors = On'; \
        echo 'display_startup_errors = On'; \
        echo 'log_errors = On'; \
        echo 'error_log = /dev/stderr'; \
        echo 'memory_limit = 256M'; \
        echo 'max_execution_time = 120'; \
        echo 'session.save_path = "/var/lib/php/sessions"'; \
    } > /usr/local/etc/php/conf.d/dev.ini

# Configure PHP session handling
RUN mkdir -p /var/lib/php/sessions && \
    chmod -R 777 /var/lib/php/sessions

# Copy entrypoint script and set permissions
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["php-fpm"] 