FROM php:8.4-cli
COPY --from=ghcr.io/mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/
RUN install-php-extensions pdo_pgsql pgsql zip mbstring bcmath intl
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
WORKDIR /var/www