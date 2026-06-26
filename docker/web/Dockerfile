# Unified PHP 8.3 runtime for the modernised stack.
# Serves Laravel (front controller) and, during the transition, the legacy
# HoloCMS in-process via the LegacyRunner. See /docs.
FROM php:8.3-apache

# --- PHP extensions -----------------------------------------------------------
# mysqli : backs the legacy mysql_* compat shim (ext/mysql was removed in PHP 7)
# pdo_mysql : Laravel / Eloquent
# sockets : legacy MUS socket to the emulator (includes/mus.php)
# gd : avatar / badge / captcha image generation
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        libpng-dev libjpeg-dev libfreetype-dev libzip-dev unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" mysqli pdo_mysql sockets gd zip \
    && rm -rf /var/lib/apt/lists/*

RUN a2enmod rewrite

# Let .htaccess rewrite rules take effect (legacy housekeeping + Laravel public).
RUN printf '<Directory /var/www/html>\n    AllowOverride All\n    Require all granted\n</Directory>\n' \
        > /etc/apache2/conf-available/habbo.conf \
    && a2enconf habbo

# Document root is overridable at build time:
#   - Phase 1 (legacy isolated test): /var/www/html        (CMS mounted directly)
#   - Phase 2+ (Laravel front controller): /var/www/html/public
ARG APACHE_DOCROOT=/var/www/html
ENV APACHE_DOCUMENT_ROOT=${APACHE_DOCROOT}
RUN sed -ri "s!DocumentRoot /var/www/html!DocumentRoot ${APACHE_DOCUMENT_ROOT}!g" \
        /etc/apache2/sites-available/000-default.conf \
    && printf '<Directory %s>\n    Options FollowSymLinks\n    AllowOverride All\n    Require all granted\n</Directory>\n' "${APACHE_DOCUMENT_ROOT}" \
        > /etc/apache2/conf-available/docroot.conf \
    && a2enconf docroot

COPY php.ini /usr/local/etc/php/conf.d/zzz-habbo.ini

EXPOSE 80
