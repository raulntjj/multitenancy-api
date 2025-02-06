FROM php:8.2-fpm

ARG user=admin
ARG uid=1000

RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libpq-dev \
    iputils-ping \
    zip \
    unzip

RUN apt-get clean && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd sockets

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user
RUN git config --global --add safe.directory /var/www


WORKDIR /var/www

RUN chmod -R 777 /var/www
RUN mkdir -p /var/www
RUN chown -R www-data:www-data /var/www

CMD composer update && php-fpm