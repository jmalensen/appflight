FROM php:8.2-fpm

# Set working directory
WORKDIR /var/www/html

# Install dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    libbz2-dev \
    libc-client-dev \
    libcurl4-gnutls-dev \
    libedit-dev \
    libicu-dev \
    libkrb5-dev \
    libldap2-dev \
    libldb-dev \
    libmemcached-dev \
    libsqlite3-dev \
    memcached \
    libxml2-dev \
    zip \
    vim \
    libzip-dev \
    unzip \
    git \
    libonig-dev

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install extensions
RUN docker-php-ext-install pdo_mysql mbstring zip exif pcntl bcmath opcache

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN usermod -u 1000 www-data

# Copy existing application directory contents to the working directory
COPY . /var/www/html

# Copy existing application directory permissions
COPY --chown=www-data:www-data . /var/www

# Required for artisan tinker to work
RUN mkdir /var/www/.config
RUN mkdir /var/www/.config/psysh
RUN chown -R www-data:www-data /var/www/.config/psysh

# Change current user to www-data
USER www-data

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]