FROM php:7.4-apache

# Install PDO and MySQL PDO extensions
RUN docker-php-ext-install pdo pdo_mysql

# Enable mod_rewrite (if needed)
RUN a2enmod rewrite

# Copy the project files into the container
COPY . /var/www/html/