FROM php:8.2-apache

# 啟用 Apache Rewrite
RUN a2enmod rewrite

# 安裝必要的 PHP extension
RUN docker-php-ext-install pdo pdo_mysql mysqli

# 清除多餘的 Apache MPM 設定，避免載入多個 MPM（Railway 必需）
RUN rm -f /etc/apache2/mods-enabled/mpm_event.conf && \
    rm -f /etc/apache2/mods-enabled/mpm_event.load

# 設定網站根目錄
COPY . /var/www/html

# 設定權限
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
