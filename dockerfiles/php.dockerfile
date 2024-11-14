FROM php:8.2-fpm-alpine

ARG UID
ARG GID

ENV UID=${UID}
ENV GID=${GID}

RUN mkdir -p /var/www/html

WORKDIR /var/www/html

# Copiar o Composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Configurações adicionais para usuários e grupos
RUN delgroup dialout
RUN addgroup -g ${GID} --system laravel
RUN adduser -G laravel --system -D -s /bin/sh -u ${UID} laravel

# Configurar php-fpm
RUN sed -i "s/user = www-data/user = laravel/g" /usr/local/etc/php-fpm.d/www.conf
RUN sed -i "s/group = www-data/group = laravel/g" /usr/local/etc/php-fpm.d/www.conf
RUN echo "php_admin_flag[log_errors] = on" >> /usr/local/etc/php-fpm.d/www.conf

# Instalar extensões PHP necessárias
RUN docker-php-ext-install pdo pdo_mysql

# Instalar extensões adicionais como exif e GD
RUN apk update && apk add --no-cache libexif-dev
RUN docker-php-ext-install exif
RUN echo "extension=exif" > /usr/local/etc/php/conf.d/exif.ini
RUN apk update && \
    apk add --no-cache libpng-dev libjpeg-turbo-dev freetype-dev && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install gd

# Instalar Redis
RUN mkdir -p /usr/src/php/ext/redis \
    && curl -L https://github.com/phpredis/phpredis/archive/5.3.4.tar.gz | tar xvz -C /usr/src/php/ext/redis --strip 1 \
    && docker-php-ext-install redis

# Instalar Apache
RUN apk add --no-cache apache2

# Configurar Apache para rodar com PHP-FPM
RUN echo "ServerName localhost" >> /etc/apache2/httpd.conf
RUN sed -i 's/AllowOverride None/AllowOverride All/' /etc/apache2/httpd.conf
RUN ln -s /etc/apache2/mod_php.conf /etc/apache2/conf.d/php.conf

# Copiar os arquivos do projeto
COPY . /var/www/html

# Definir o usuário do Laravel
USER laravel

# Rodar Apache em primeiro plano
CMD ["apache2", "-D", "FOREGROUND"]
