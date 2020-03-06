FROM php:7.3-fpm

RUN apt-get update && apt-get install -y \
    openssl \
    git \
    unzip libpng-dev \
    libfontconfig1 libxrender1 \
    libpq-dev \
    libzip-dev \
    zlib1g-dev libicu-dev g++ \
    wget

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer --version

# Type docker-php-ext-install to see available extensions
RUN docker-php-ext-install pdo gd pdo_mysql opcache zip

# Install Postgre PDO
RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo_pgsql

# install xdebug
RUN pecl install xdebug
RUN docker-php-ext-enable xdebug

RUN sed -i -e 's@zend_extension@;zend_extension@g' /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
