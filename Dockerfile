FROM php:7.4-apache
# Install dependencies and PHP extensions
RUN apt-get install -y \
    libonig-dev \
    libzip-dev \
    libpq-dev \
    postgresql-client-14 \
    unzip \
    && docker-php-ext-install pdo_mysql mbstring zip pdo_pgsql pgsql

# Enable Apache rewrite module
RUN a2enmod rewrite

# Copy application code
COPY . /var/www/html/
RUN chown -R www-data:www-data /var/www/html

# Expose port (Render uses a dynamic port)
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]
