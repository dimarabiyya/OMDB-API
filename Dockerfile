FROM php:7.4-apache

# Install extension yang dibutuhkan Laravel
RUN docker-php-ext-install pdo pdo_mysql

# Set folder kerja utama di dalam container
WORKDIR /var/www/html

# Copy semua file dari GitHub ke dalam folder kerja di atas
COPY . .

# Berikan izin akses (PENTING)
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod +x /var/www/html/artisan

# Aktifkan rewrite engine untuk .htaccess Laravel
RUN a2enmod rewrite
