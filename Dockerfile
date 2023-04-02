# Set base image
FROM php:8.1.17-apache

# Set working directory
WORKDIR /var/www/html

# Install system dependencies
RUN apt-get update && apt-get install -y \
        libpng-dev \
        zlib1g-dev \
        libxml2-dev \
        libzip-dev \
        libonig-dev \
        zip \
        curl \
        unzip \
    && docker-php-ext-configure gd \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-install mysqli \
    && docker-php-ext-install zip \
    && docker-php-source delete

# Install nodejs v18 LTS
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash -
RUN apt-get update && apt-get install nodejs -y

# Install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Copy vhost config
COPY docker/apache/config/000-default.conf /etc/apache2/sites-enabled/000-default.conf

# Enable apache module
RUN a2enmod rewrite

# Copy project
COPY . .

# Update dependency
RUN composer update
RUN npm install

# Setup permission
RUN chown -R www-data:www-data /var/www
RUN chmod -R g+w /var/www
RUN chmod -R 775 bootstrap/cache/ storage/

# Storage link
RUN rm -R public/storage
RUN php artisan storage:link

# Set default user
USER www-data

# Expose port
EXPOSE 80
