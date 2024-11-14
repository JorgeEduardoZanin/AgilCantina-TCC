FROM nginx:stable-alpine

ARG UID=1000
ARG GID=1000

ENV UID=${UID}
ENV GID=${GID}

RUN delgroup dialout
RUN addgroup -g ${GID} --system laravel
RUN adduser -G laravel --system -D -s /bin/sh -u ${UID} laravel
RUN sed -i "s/user  nginx/user laravel/g" /etc/nginx/nginx.conf

# Instalar PHP-FPM e dependências necessárias
RUN apk add --no-cache \
    php81-fpm \
    php81-opcache \
    php81-gd \
    php81-mbstring \
    php81-pdo \
    php81-mysqli \
    php81-json \
    php81-phar \
    php81-xml \
    php81-curl \
    php81-zip \
    php81-soap \
    php81-intl \
    && apk add --no-cache --virtual .build-deps gcc libffi-dev make autoconf \
    && pecl install xdebug \
    && apk del .build-deps

# Copiar arquivo de configuração nginx
ADD ./nginx/default.conf /etc/nginx/conf.d/

RUN mkdir -p /var/www/html

# Copiar os arquivos do projeto
COPY ./ /var/www/html

# Expor as portas necessárias
EXPOSE 80 9000

CMD ["nginx", "-g", "daemon off;"]
