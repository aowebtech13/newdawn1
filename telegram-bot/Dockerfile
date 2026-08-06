FROM php:8.3-cli

# Install system dependencies required by Laravel and the Telegram SDK
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pdo_sqlite sqlite3 zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy project files
COPY . .

# Install PHP dependencies (production)
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Cache configuration (includes the new config/cors.php)
RUN php artisan config:cache

# Create SQLite database and run migrations
RUN touch database/database.sqlite && chmod 666 database/database.sqlite
RUN php artisan migrate --force

# Set permissions for storage and cache
RUN chmod -R 775 storage bootstrap/cache

# Expose the port (Render sets $PORT at runtime)
EXPOSE 8000

# Start the Laravel development server
# Render overrides this with the startCommand from render.yaml
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
