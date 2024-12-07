# Use the official PHP 7.4 Apache image
FROM php:7.4-apache

# Set working directory inside the container
WORKDIR /var/www/html

# Copy application files
COPY . /var/www/html

# Install PHP extensions and Composer
RUN apt-get update && apt-get install -y \
    zip \
    unzip \
    libzip-dev \
    && docker-php-ext-install zip mysqli \
    && docker-php-ext-enable mysqli

# Copy custom Apache configuration
COPY apache.conf /etc/apache2/sites-available/000-default.conf

# Enable Apache modules
RUN a2enmod rewrite

# Expose the port Apache will run on
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]
