FROM php:7.4-fpm

# Copy code over to container (in future this should just be a link to your local copy)
#doubt this is needed now
COPY . /usr/src/my-garden

# Complete the necessary update before installing anything
RUN apt-get update

# Copy composer.lock and composer.json
#doubt this is needed, and it's ugly
COPY api/composer.lock api/composer.json /var/www/

# Set working directory
WORKDIR /var/www

# Install dependencies (slim this down in future, many are unnecessary)
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

# Set working directory
WORKDIR /var

# Change ownership of app to allow php-fpm to read and write
RUN chown -R www-data:www-data www

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]