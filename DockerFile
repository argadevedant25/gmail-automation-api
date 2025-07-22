FROM php:7.4-apache
RUN apt-get update && apt-get install -y \
    libonig-dev \
    libzip-dev \
    libpq-dev \
    unzip
RUN docker-php-ext-install pdo_mysql mbstring zip pdo_pgsql
COPY . /var/www/html
RUN chown -R www-data:www-data /var/www/html
EXPOSE 8080
CMD ["apache2-foreground"]