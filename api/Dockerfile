FROM php:7.4-fpm

RUN apt-get update

COPY api/ /var/www/my-garden/api/

WORKDIR /var/www

RUN apt-get update && apt-get install -y \
    curl \
    libzip-dev \
    zip \
    && docker-php-ext-install zip \
    && docker-php-ext-install pdo_mysql

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var

# Change ownership of app to allow php-fpm to read and write
RUN chown -R www-data:www-data www

WORKDIR /var/www/my-garden/api/

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]