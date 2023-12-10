# Use the official PHP image as the base image
FROM php:8.1-apache

# Set the working directory in the container
WORKDIR /var/www/html

# Install dependencies
RUN apt-get update && \
    apt-get install -y \
    git \
    libzip-dev \
    zlib1g-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libexif-dev

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql zip pcntl gd exif

# Install Composer globally
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy the composer files and install dependencies
COPY composer.json composer.lock /var/www/html/
RUN composer install --no-scripts --no-autoloader

# Copy the application files to the container
COPY . /var/www/html/

# Generate the autoload files
RUN composer dump-autoload --optimize

# Set the appropriate permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Expose port 80 for the web server
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]
