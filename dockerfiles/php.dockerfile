# php.dockerfile
FROM php:8.2-fpm-alpine

ARG UID
ARG GID

ENV UID=${UID}
ENV GID=${GID}

RUN mkdir -p /var/www/html

WORKDIR /var/www/html

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Configurações adicionais para usuários e grupos
RUN delgroup dialout
RUN addgroup -g ${GID} --system laravel
RUN adduser -G laravel --system -D -s /bin/sh -u ${UID} laravel


RUN sed -i "s/user = www-data/user = laravel/g" /usr/local/etc/php-fpm.d/www.conf
RUN sed -i "s/group = www-data/group = laravel/g" /usr/local/etc/php-fpm.d/www.conf
RUN echo "php_admin_flag[log_errors] = on" >> /usr/local/etc/php-fpm.d/www.conf

# Instalar extensões PHP necessárias
RUN docker-php-ext-install pdo pdo_mysql

#comandos jorge
# Atualizar os repositórios e instalar as dependências necessárias
RUN apk update && apk add --no-cache libexif-dev
# Instalar a extensão exif
RUN docker-php-ext-install exif
# Certifique-se de que a extensão exif seja carregada
RUN echo "extension=exif" > /usr/local/etc/php/conf.d/exif.ini
RUN apk update && \
    apk add --no-cache \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install gd

# Instalar Redis
RUN mkdir -p /usr/src/php/ext/redis \
    && curl -L https://github.com/phpredis/phpredis/archive/5.3.4.tar.gz | tar xvz -C /usr/src/php/ext/redis --strip 1 \
    && echo 'redis' >> /usr/src/php-available-exts \
    && docker-php-ext-install redis

USER laravel


CMD ["php-fpm", "-y", "/usr/local/etc/php-fpm.conf", "-R"]