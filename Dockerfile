FROM php:7.4-apache

# Install PostgreSQL apt repository for newer libpq
RUN apt-get update && apt-get install -y \
    gnupg \
    lsb-release \
    wget \
    && wget --quiet -O - https://www.postgresql.org/media/keys/ACCC4CF8.asc > /usr/share/keyrings/postgresql-archive-keyring.gpg \
    && echo "deb [signed-by=/usr/share/keyrings/postgresql-archive-keyring.gpg] http://apt.postgresql.org/pub/repos/apt $(lsb_release -cs)-pgdg main" > /etc/apt/sources.list.d/pgdg.list

# Install dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    libonig-dev \
    libzip-dev \
    libpq-dev \
    postgresql-client-16 \
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