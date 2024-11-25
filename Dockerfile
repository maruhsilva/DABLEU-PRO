# Use uma imagem oficial do PHP com Apache
FROM php:8.1-apache

# Copie os arquivos do projeto para o diretório padrão do Apache
COPY . /var/www/html/

# Configure permissões adequadas
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Instale extensões PHP (adicione outras se necessário)
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Exponha a porta 80 para o servidor
EXPOSE 80

# Inicie o Apache
CMD ["apache2-foreground"]
