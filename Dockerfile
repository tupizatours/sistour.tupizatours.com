# Imagen base de PHP con Alpine
FROM php:8.2-fpm-alpine

# Configurar repositorios y actualizar paquetes
RUN apk update && apk add --no-cache \
    linux-headers \
    bash git sudo openssh curl wget \
    libxml2-dev oniguruma-dev autoconf gcc g++ make \
    freetype-dev libjpeg-turbo-dev libpng-dev \
    libzip-dev libwebp-dev icu-dev nasm file pkgconfig \
    sqlite sqlite-dev  # ✅ Agregamos SQLite solo si es necesario

# 🔹 Instalar Node.js y npm desde Alpine sin SQLite (versión estable)
RUN apk add --no-cache nodejs npm

# Verificar instalación correcta de Node.js y npm
RUN node -v && npm -v

# Instalar extensiones de PHP necesarias para Laravel
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install \
        mbstring xml pcntl gd zip sockets \
        pdo pdo_mysql bcmath intl soap \
    && docker-php-ext-enable \
        mbstring xml gd zip pcntl sockets bcmath \
        pdo pdo_mysql intl soap

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configurar el directorio de trabajo
WORKDIR /var/www/html

# Copiar archivos del proyecto al contenedor
COPY . .


# Construcción de assets con Vite
# RUN npm run build

# Configurar permisos de almacenamiento para Laravel
RUN chmod -R 775 storage bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

# Exponer el puerto 8000
EXPOSE 8000

# Comando de inicio del contenedor
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]