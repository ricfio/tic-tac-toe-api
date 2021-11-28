ARG PHP_VERSION=8.0.13

## BASE image
FROM php:${PHP_VERSION}-fpm-alpine AS base

WORKDIR /app

## Install composer
RUN wget https://getcomposer.org/installer && \
    php installer --install-dir=/usr/local/bin/ --filename=composer && \
    rm installer

## Install symfony CLI
#RUN wget https://get.symfony.com/cli/installer -O - | bash && \
#    mv /root/.symfony/bin/symfony /usr/local/bin/symfony

## Install php extensions
#RUN docker-php-ext-install pdo_mysql

## DEV image
FROM base AS dev

## Install extra packages
RUN apk add bash make git

## Install php extensions
# RUN docker-php-ext-install pdo_mysql

## Install Xdebug
# ARG XDEBUG_MODE=off
# ARG XDEBUG_CLIENT_PORT=9003
# ARG XDEBUG_CLIENT_HOST=host.docker.internal
# COPY conf.d/docker-php-ext-xdebug.ini.template /usr/local/etc/php/conf.d/
# RUN apk --no-cache add $PHPIZE_DEPS && \
#     pecl install xdebug && \
#     docker-php-ext-enable xdebug && \
#     export XDEBUG_MODE=$XDEBUG_MODE && \
#     export XDEBUG_CLIENT_PORT=$XDEBUG_CLIENT_PORT && \
#     export XDEBUG_CLIENT_HOST=$XDEBUG_CLIENT_HOST && \
#     envsubst < /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini.template > /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
#     rm -f /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini.template

## Use the default development configuration
RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

## Customizations
RUN echo "export PS1='🐳 [\[\033[1;36m\]\u@\h\[\033[0m\] \[\033[1;34m\]\W\[\033[0m\]]\\$ '" >> ~/.bashrc

## PROD image
FROM base AS prod

## Copy project files to workdir
COPY . .

## Use the default production configuration
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

## Install application dependencies
RUN composer install --no-dev --no-interaction --optimize-autoloader

## Change files owner to php-fpm default user
RUN chown -R www-data:www-data .

VOLUME /app
