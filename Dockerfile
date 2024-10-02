# Dockerfile
FROM php:8.1-apache

# Install the required extensions for MySQL
RUN docker-php-ext-install pdo pdo_mysql

# Copy the PHP application code to the container
COPY ./src /var/www/html

# Expose the web server port
EXPOSE 80

