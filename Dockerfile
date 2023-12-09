# Use an official PHP runtime as a parent image
FROM php:8.1-apache

# Set the working directory to /var/www/html
WORKDIR /var/www/html

# Install dependencies
RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    git \
    && docker-php-ext-install zip pdo_mysql \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

# Copy composer.lock and composer.json to the working directory
COPY composer.lock composer.json /var/www/html/

# Install composer dependencies
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install --no-scripts --no-autoloader

# Copy the application files to the container at /var/www/html
COPY . /var/www/html

# Generate autoload files and optimize for production
RUN composer dump-autoload --optimize

# Set up Apache
RUN a2enmod rewrite
COPY docker/apache2.conf /etc/apache2/sites-available/000-default.conf

# Expose port 80
EXPOSE 80

# CMD command to start Apache and run the application
CMD ["apache2-foreground"]
