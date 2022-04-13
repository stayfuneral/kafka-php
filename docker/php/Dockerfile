FROM php:8.1-fpm-alpine

RUN apk add tzdata && \
    cp /usr/share/zoneinfo/Asia/Novosibirsk /etc/localtime && \
    echo "Asia/Novosibirsk" >  /etc/timezone

# Install packages
RUN apk add --no-cache curl git build-base zlib-dev oniguruma-dev autoconf bash

RUN apk add g++ && \
    apk add make && \
    apk add --no-cache --virtual .phpize-deps $PHPIZE_DEPS && \
    apk add ldb-dev libldap openldap-dev

RUN cd /tmp \
    && git clone https://github.com/edenhill/librdkafka.git \
    && cd librdkafka && \
    ./configure \
        && make \
        && make install \
        && pecl install rdkafka \
        && docker-php-ext-enable rdkafka \
        && rm -rf /tmp/librdkafka

RUN echo "extension=rdkafka.so" > /usr/local/etc/php/conf.d/rdkafka.ini

RUN docker-php-ext-install mysqli && \
    docker-php-ext-enable mysqli && \
    mkdir -p /var/local/php/sessions &&\
    chown -R www-data:www-data /var/local/php/sessions



# Configure non-root user.
ARG PUID=1000
ARG PGID=1000
RUN apk --no-cache add shadow && \
    groupmod -o -g ${PGID} www-data && \
    usermod -o -u ${PUID} -g www-data www-data

RUN  mkdir /data && \
     chown www-data:www-data /data
WORKDIR /data

USER www-data



# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

CMD php-fpm

EXPOSE 9000