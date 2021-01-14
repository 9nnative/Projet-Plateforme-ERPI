FROM php:7.4-apache

ENV COMPOSER_ALLOW_SUPERUSER=1

EXPOSE 80
WORKDIR /app

RUN apt-get update -qq && \
    apt-get install -qy \
    git \
    wget \
    gnupg \
    libicu-dev \
    libzip-dev \
    unzip \
    zip \
    zlib1g-dev && \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
    apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Symfony test 
RUN wget https://get.symfony.com/cli/installer -O - | bash 
RUN mv /root/.symfony/bin/symfony /usr/local/bin/symfony

# PHP Extensions
RUN docker-php-ext-configure zip && \
    docker-php-ext-install -j$(nproc) intl opcache pdo_mysql zip
COPY conf/php.ini /usr/local/etc/php/conf.d/app.ini

# Apache
COPY conf/vhost.conf /etc/apache2/sites-available/000-default.conf
COPY conf/apache.conf /etc/apache2/conf-available/z-app.conf

COPY conf/symfony.ini    /etc/php7/conf.d/
COPY conf/symfony.ini    /etc/php7/cli/conf.d/
COPY conf/others.ini      /etc/php7/conf.d/

RUN a2enmod rewrite remoteip && \
    a2enconf z-app