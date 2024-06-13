FROM php:8.2-apache
WORKDIR /var/www/html

# linux library install
RUN apt-get update -y && apt-get install -y \
    libzip-dev \
    libicu-dev \
    unzip zip \
    zlib1g-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libonig-dev \
    libpq-dev \
    libxml2-dev \
    libcurl4-openssl-dev 

# enable mod rewrite
RUN a2enmod rewrite

# php extention
RUN docker-php-ext-install gettext intl mbstring pgsql pdo pdo_pgsql zip xml curl gd exif bcmath

COPY .docker/000-default.apache.conf /etc/apache2/sites-enabled/000-default.conf

#copy aplication code
COPY . /var/www/html

WORKDIR /var/www/html

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN composer install

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
