# Use PHP 8.2 CLI as the base image
FROM php:8.3-cli

# Define an argument for the version
ARG VERSION

# Label the image with the version
LABEL version=${VERSION}

# Install Supervisor and necessary extensions
RUN apt-get update && apt-get install -y \
    supervisor \
    libxml2-dev \
    librabbitmq-dev \ 
    && docker-php-ext-install dom \
    && docker-php-ext-install mysqli pdo pdo_mysql \
    && pecl install amqp \  
    && docker-php-ext-enable amqp \ 
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && rm -rf /var/lib/apt/lists/*

# Create log directory for Supervisor
RUN mkdir -p /var/log/supervisor

# Ensure the logs directory exists, then change ownership
RUN mkdir -p /var/log && chown -R www-data:www-data /var/log

# Copy Supervisor configuration file
COPY ./infrastructure/supervisor/supervisord.conf /etc/supervisor/supervisord.conf

# Set the working directory
WORKDIR /var/www

# Default command to run Supervisor
CMD ["/usr/bin/supervisord", "-n"]

