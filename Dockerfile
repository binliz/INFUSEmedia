FROM php:8.2-fpm-alpine as phplayer

ARG UID
ARG GID
ARG USER

ENV COMPOSER_ALLOW_SUPERUSER 1
ENV UID=${UID}
ENV GID=${GID}
ENV USER=${USER}

RUN delgroup dialout

# Creating user and group
RUN addgroup -g ${GID} --system ${USER}
RUN adduser -G ${USER} --system -D -s /bin/sh -u ${UID} ${USER}

RUN apk add libzip-dev && \
    docker-php-ext-install pdo pdo_mysql zip && \
    apk add --no-cache pcre-dev $PHPIZE_DEPS \
        && pecl install redis \
        && docker-php-ext-enable redis.so && \
    apk add --update --no-cache libmemcached-dev && \
    pecl install memcached && docker-php-ext-enable memcached

RUN cp $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini && \
sed -i 's/session.save_handler = files/session.save_handler = memcached/g' $PHP_INI_DIR/php.ini && \
sed -i 's/;session.save_path = "\/tmp"/session.save_path = "memcached:11211"/g' $PHP_INI_DIR/php.ini

RUN sed -i "s/user = www-data/user = '${USER}'/g" $PHP_INI_DIR/../php-fpm.d/www.conf
RUN sed -i "s/group = www-data/group = '${USER}'/g" $PHP_INI_DIR/../php-fpm.d/www.conf

WORKDIR /app

USER ${USER}
