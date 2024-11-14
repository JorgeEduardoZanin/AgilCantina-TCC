FROM nginx:stable-alpine

ARG UID
ARG GID

ENV UID=${UID}
ENV GID=${GID}

# Remover grupo dialout, não utilizado
RUN delgroup dialout

# Criar o grupo e usuário laravel
RUN addgroup -g ${GID} --system laravel
RUN adduser -G laravel --system -D -s /bin/sh -u ${UID} laravel

# Alterar o usuário do Nginx para laravel
RUN sed -i "s/user  nginx/user laravel/g" /etc/nginx/nginx.conf

# Adicionar a configuração do Nginx
ADD ./nginx/default.conf /etc/nginx/conf.d/

# Criar diretório e configurar permissões
RUN mkdir -p /var/www/html && chown -R laravel:laravel /var/www/html

# Copiar código da aplicação Laravel
COPY . /var/www/html

# Instalar PHP e Composer (se necessário)
RUN apk add --no-cache \
    php7 \
    php7-fpm \
    php7-mbstring \
    php7-opcache \
    php7-xml \
    php7-curl \
    php7-json \
    php7-mysqli \
    php7-bcmath \
    && curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer

# Iniciar o Nginx
CMD ["nginx", "-g", "daemon off;"]
