FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    nodejs \
    npm

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy application
COPY . .

# Install dependencies
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Set permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# Configure PHP-FPM
RUN echo "[www]" >> /usr/local/etc/php-fpm.d/zz-docker.conf \
    && echo "php_admin_value[sys_temp_dir] = /var/www/html/storage/tmp" >> /usr/local/etc/php-fpm.d/zz-docker.conf \
    && echo "php_admin_value[upload_tmp_dir] = /var/www/html/storage/tmp" >> /usr/local/etc/php-fpm.d/zz-docker.conf \
    && echo "php_admin_value[open_basedir] = /var/www/html" >> /usr/local/etc/php-fpm.d/zz-docker.conf

# Create temp directory in storage
RUN mkdir -p /var/www/html/storage/tmp \
    && chmod 777 /var/www/html/storage/tmp

# Expose port 9000 for PHP-FPM
EXPOSE 9000

# Start PHP-FPM
CMD ["php-fpm"]
