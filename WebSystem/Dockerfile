FROM php:8.2-apache

# Instala ferramentas e extensões
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    && docker-php-ext-install pdo pdo_mysql \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

RUN a2enmod rewrite

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copia apenas o diretório WebSystem para a raiz do servidor
COPY ./WebSystem /var/www/html/

RUN composer dump-autoload --optimize

RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
