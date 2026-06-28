FROM php:8.2-apache

# Ativa o módulo de reescrita do Apache (importante para rotas MVC)
RUN a2enmod rewrite

# Instala as extensões PDO e MySQL necessárias para conectar ao Aiven
RUN docker-php-ext-install pdo pdo_mysql

# Copia os arquivos do seu projeto para a pasta do servidor
COPY . /var/www/html/

# Ajusta as permissões para o Apache ler os arquivos
RUN chown -R www-data:www-data /var/www/html

# Alinha a porta padrão do Apache com o que o Render espera
EXPOSE 80