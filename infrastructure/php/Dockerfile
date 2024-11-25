FROM php:8.3-fpm-alpine

# Define an argument for the version
ARG VERSION

# Label the image with the version
LABEL version=${VERSION}

# Install necessary packages and PHP extensions
RUN apk add --no-cache \
    curl \
    git \
    build-base \
    zlib-dev \
    oniguruma-dev \
    autoconf \
    bash \
    linux-headers \
    shadow \
    rabbitmq-c-dev \
    supervisor \
    libzip-dev \
    gd-dev \
    icu-dev  

# Install PHP extensions
RUN pecl install redis amqp xdebug && \
    docker-php-ext-enable redis amqp xdebug && \
    docker-php-ext-install sockets pdo_mysql zip gd intl 

# Set the working directory for the application
WORKDIR /var/www

# Copy the application code and set permissions
COPY ./api /var/www
RUN chown -R www-data:www-data /var/www

# Install Composer for PHP dependency management
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer

# Change to www-data user before installing Composer dependencies
USER www-data

# Install Composer dependencies
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Change back to root for the entrypoint script
USER root

# Expose the port the server will use
EXPOSE 9000

# Command to start the PHP server
CMD ["php-fpm"]

