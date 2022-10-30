FROM php:7.4-fpm

RUN apt-get update && apt-get install -y \
    openssl \
    git \
    unzip \
    zip \
    libzip-dev

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
&& composer --version \
&& curl -sS https://get.symfony.com/cli/installer | bash \
&& mv /root/.symfony5/bin/symfony /usr/local/bin/symfony

# Set timezone
RUN ln -snf /usr/share/zoneinfo/${TIMEZONE} /etc/localtime && echo ${TIMEZONE} > /etc/timezone \
&& printf '[PHP]\ndate.timezone = "%s"\n', ${TIMEZONE} > /usr/local/etc/php/conf.d/tzone.ini \
&& "date"

# Type docker-php-ext-install to see available extensions
RUN docker-php-ext-install pdo pdo_mysql zip

RUN pecl install -o -f redis \
&&  rm -rf /tmp/pear \
&&  docker-php-ext-enable redis

# install xdebug
RUN pecl install xdebug \
&& docker-php-ext-enable xdebug \
&& echo "zend_extension=/usr/local/lib/php/extensions/no-debug-non-zts-20190902/xdebug.so" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
&& echo "error_reporting = E_ALL" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
&& echo "display_startup_errors = On" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
&& echo "display_errors = On" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

RUN curl -1sLf 'https://dl.cloudsmith.io/public/symfony/stable/setup.deb.sh' | bash
RUN apt install symfony-cli

RUN echo 'alias sf="php bin/console"' >> ~/.bashrc

WORKDIR /var/www/html

# RUN cd project && composer install
