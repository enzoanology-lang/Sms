# Use official PHP Apache image
FROM php:8.2-apache

# Enable mod_rewrite for Apache
RUN a2enmod rewrite

# Install mysqli extension (if you need database later)
RUN docker-php-ext-install mysqli

# Set working directory
WORKDIR /var/www/html

# Copy all your files to the container
COPY . /var/www/html/

# Set proper permissions
RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 755 /var/www/html

# Configure Apache to serve files from /var/www/html
# Your files are already there!

# Expose port 80
EXPOSE 80
