FROM php:8.1.27-apache-bullseye

# Install system dependencies
RUN apt-get update -y && apt-get install -y \
    libicu-dev \
    libmariadb-dev \
    unzip zip \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    ca-certificates \
    && rm -rf /var/lib/apt/lists/*

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Configure Apache to serve from /var/www/html/webroot
RUN sed -i 's|/var/www/html|/var/www/html/webroot|g' /etc/apache2/sites-available/000-default.conf
RUN echo '<Directory /var/www/html/webroot>\n    AllowOverride All\n    Require all granted\n</Directory>' >> /etc/apache2/apache2.conf

# Install PHP extensions
RUN docker-php-ext-configure gd --enable-gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
    gd \
    intl \
    pdo_mysql

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy app files
COPY app/ /var/www/html/

# Install PHP dependencies (production)
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Copy production config (app_local.php is gitignored, so use production version)
RUN cp /var/www/html/config/app_local.production.php /var/www/html/config/app_local.php

# Create required directories and set permissions
RUN mkdir -p /var/www/html/logs \
    /var/www/html/tmp/cache/models \
    /var/www/html/tmp/cache/persistent \
    /var/www/html/tmp/cache/views \
    /var/www/html/tmp/sessions \
    /var/www/html/tmp/tests \
    && chown -R www-data:www-data /var/www/html/logs /var/www/html/tmp

# Use PORT env var from Render (default 10000)
# Apache doesn't expand env vars in config, so we use a startup script
RUN echo '#!/bin/bash\nsed -i "s/Listen 80/Listen ${PORT:-10000}/g" /etc/apache2/ports.conf\nsed -i "s/:80/:${PORT:-10000}/g" /etc/apache2/sites-available/000-default.conf\napache2-foreground' > /usr/local/bin/start.sh \
    && chmod +x /usr/local/bin/start.sh

# Expose the port
EXPOSE 10000

# Start Apache with PORT substitution
CMD ["/usr/local/bin/start.sh"]
