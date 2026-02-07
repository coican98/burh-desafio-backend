# Usa imagem oficial do PHP 8.2 com FPM
FROM php:8.2-fpm

# Instala dependências do sistema
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    zip \
    unzip \
    && docker-php-ext-install pdo pdo_pgsql pgsql mbstring exif pcntl bcmath gd

# Instala Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Define diretório de trabalho
WORKDIR /var/www

# Define permissões
RUN chown -R www-data:www-data /var/www

# Expõe porta 9000 para PHP-FPM
EXPOSE 9000

CMD ["php-fpm"]
