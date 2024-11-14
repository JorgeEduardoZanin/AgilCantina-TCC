FROM nginx:stable-alpine

# Definindo UID e GID
ARG UID=1000
ARG GID=1000

ENV UID=${UID}
ENV GID=${GID}

# Remover o grupo dialout (não utilizado)
RUN delgroup dialout

# Criar o grupo e o usuário 'laravel' com UID e GID passados como argumentos
RUN addgroup -g ${GID} --system laravel
RUN adduser -G laravel --system -D -s /bin/sh -u ${UID} laravel

# Alterar a configuração do Nginx para rodar como o usuário 'laravel'
RUN sed -i "s/user  nginx/user laravel/g" /etc/nginx/nginx.conf

# Adicionar configuração personalizada do Nginx
ADD ./nginx/default.conf /etc/nginx/conf.d/

# Criar diretório para armazenar o código da aplicação
RUN mkdir -p /var/www/html

# Expor a porta 80 para acesso externo
EXPOSE 80

# Iniciar o Nginx em modo foreground
CMD ["nginx", "-g", "daemon off;"]
