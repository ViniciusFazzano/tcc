# Use a imagem oficial do PHP
FROM php:8.3-apache

# EXPOSE 80:80

# ServerName para evitar o aviso
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Instale as dependências necessárias para o PDO_PGSQL
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pdo_pgsql

# Instale as dependências necessárias para o Composer
RUN apt-get update && apt-get install -y \
    git \
    zip \
    unzip

# Instalar um editor de texto pra nao precisar ficar installando toda hora
RUN apt-get install nano

# Baixe e instale o Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer


ENV APACHE_DOCUMENT_ROOT /var/www/tcc

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
RUN ln -s /etc/apache2/mods-available/rewrite.load /etc/apache2/mods-enabled/rewrite.load
# RUN ln -s /etc/apache2/mods-available/rewrite.load /etc/apache2/mods-enabled/rewrite.load
RUN ln -s /etc/apache2/mods-available/headers.load /etc/apache2/mods-enabled/headers.load


# Defina o diretório de trabalho dentro do contêiner
WORKDIR /var/www/tcc



