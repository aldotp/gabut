# Step 1: Use an official PHP base image with required extensions for Laravel
FROM php:8.2-fpm

# Step 2: Install system dependencies and PHP extensions required for Laravel
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl \
    libonig-dev \
    libzip-dev \
    nodejs \
    npm \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo mbstring gd zip

# Step 3: Install Composer globally
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Step 4: Set working directory
WORKDIR /var/www/html

# Step 5: Copy existing application directory contents to the container working directory
COPY . /var/www/html

# Step 6: Install PHP dependencies using Composer
RUN composer install --no-dev --optimize-autoloader

# Step 7: Install Node.js dependencies and build assets (e.g., with Laravel Mix)
RUN npm install && npm run prod

# Step 8: Set proper permissions for Laravel (especially for storage and bootstrap cache)
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache

# Step 9: Expose port 9000 and start PHP-FPM process
EXPOSE 9000
CMD ["php-fpm"]
