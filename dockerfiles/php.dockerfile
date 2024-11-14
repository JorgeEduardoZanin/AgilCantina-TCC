FROM php:8.2-fpm-alpine

# Definindo UID e GID
ARG UID
ARG GID

ENV UID=${UID}
ENV GID=${GID}

# Criar o diretório para a aplicação
RUN mkdir -p /var/www/html

WORKDIR /var/www/html

# Copiar o Composer para o contêiner
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Remover o grupo dialout (não utilizado)
RUN delgroup dialout

# Criar o grupo e o usuário 'laravel' com UID e GID passados como argumentos
RUN addgroup -g ${GID} --system laravel
RUN adduser -G laravel --system -D -s /bin/sh -u ${UID} laravel

# Alterar as configurações do PHP-FPM para usar o usuário 'laravel'
RUN sed -i "s/user = www-data/user = laravel/g" /usr/local/etc/php-fpm.d/www.conf
RUN sed -i "s/group = www-data/group = laravel/g" /usr/local/etc/php-fpm.d/www.conf
RUN echo "php_admin_flag[log_errors] = on" >> /usr/local/etc/php-fpm.d/www.conf

# Instalar extensões PHP necessárias
RUN docker-php-ext-install pdo pdo_mysql

# Instalar a extensão EXIF
RUN apk update && apk add --no-cache libexif-dev
RUN docker-php-ext-install exif
RUN echo "extension=exif" > /usr/local/etc/php/conf.d/exif.ini

# Instalar a extensão GD para manipulação de imagens
RUN apk update && \
    apk add --no-cache libpng-dev libjpeg-turbo-dev freetype-dev && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install gd

# Instalar Redis
RUN mkdir -p /usr/src/php/ext/redis && \
    curl -L https://github.com/phpredis/phpredis/archive/5.3.4.tar.gz | tar xvz -C /usr/src/php/ext/redis --strip 1 && \
    docker-php-ext-install redis

# Usar o usuário 'laravel' para rodar os processos no contêiner
USER laravel

# Iniciar o PHP-FPM
CMD ["php-fpm", "-y", "/usr/local/etc/php-fpm.conf", "-R"]
