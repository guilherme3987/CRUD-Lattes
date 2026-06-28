FROM php:8.2-apache

# Instala as ferramentas necessárias (git, unzip) para o Composer funcionar, além do driver pdo_mysql
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    && docker-php-ext-install pdo pdo_mysql \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Ativa o módulo de reescrita do Apache (importante para rotas MVC)
RUN a2enmod rewrite

# Instala o Composer de forma oficial e global dentro do contêiner
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Define o diretório de trabalho
WORKDIR /var/www/html

# Copia os arquivos do seu projeto para dentro do contêiner
COPY . /var/www/html/

# Executa o Composer para criar a pasta 'vendor/' e gerar o arquivo 'autoload.php'
# O parâmetro --no-dev e --optimize-autoloader são boas práticas para ambiente de produção (nuvem)
RUN composer install --no-dev --optimize-autoloader

# Ajusta as permissões para o Apache ler e executar os arquivos corretamente
RUN chown -R www-data:www-data /var/www/html

# Expõe a porta padrão do Apache
EXPOSE 80
