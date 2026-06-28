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

# Copia o projeto
COPY . /var/www/html/

# FORÇA O LINUX A RENOMEAR TODAS AS PASTAS DO MVC (Mata definitivamente os problemas do Windows)
RUN mv /var/www/html/core /var/www/html/Core || true
RUN mv /var/www/html/controllers /var/www/html/Controllers || true
RUN mv /var/www/html/models /var/www/html/Models || true
RUN mv /var/www/html/services /var/www/html/Services || true
RUN mv /var/www/html/repositories /var/www/html/Repositories || true
RUN mv /var/www/html/config /var/www/html/Config || true

# Otimiza e reconstrói o mapa absoluto de classes do Composer
RUN composer dump-autoload --optimize

RUN chown -R www-data:www-data /var/www/html

EXPOSE 80