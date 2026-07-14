FROM php:8.1-fpm

# Instalar dependencias del sistema y herramientas útiles
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# Limpiar caché
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar extensiones de PHP necesarias para Laravel y MySQL
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Instalar Composer desde la imagen oficial
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Definir el directorio de trabajo
WORKDIR /var/www