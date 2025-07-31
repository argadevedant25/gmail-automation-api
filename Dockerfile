FROM php:7.4-apache

# Clean and update package lists thoroughly
RUN rm -rf /var/lib/apt/lists/* && \
    apt-get clean && \
    apt-get update -y && \
    apt-get install -y \
        libonig-dev \
        libzip-dev \
        libpq-dev \
        unzip \
    && rm -rf /var/lib/apt/lists/* \
    && docker-php-ext-install pdo_mysql mbstring zip pdo_pgsql pgsql

# Enable Apache rewrite module
RUN a2enmod rewrite

# Copy application code
COPY . /var/www/html/
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
CMD ["apache2-foreground"]

