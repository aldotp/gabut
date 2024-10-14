# Use the official PHP 8.2 image with Apache
FROM php:8.2-apache

# Update package list and install dependencies
RUN apt-get update --fix-missing \
    && apt-get install -y \
    zip \
    unzip \
    libpq-dev \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Install Composer globally
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy application source code to the container
COPY . /var/www/html/
WORKDIR /var/www/html/

# Copy Apache configuration file
COPY default.conf /etc/apache2/sites-available/000-default.conf

# Copy example environment file and set it as the active .env
COPY .env.example .env

# Set environment variable to allow Composer to run as superuser
ENV COMPOSER_ALLOW_SUPERUSER=1

# Install PHP dependencies using Composer
RUN composer install --prefer-dist --no-dev --optimize-autoloader --no-interaction

# Set the correct ownership and permissions for Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Generate the Laravel application key
RUN php artisan key:generate

# # Expose port 80
# EXPOSE 80

# # Start Apache in the foreground
# CMD ["apache2-foreground"]
