FROM ghcr.io/surnet/alpine-wkhtmltopdf:3.20.3-0.12.6-full

# Install PHP and necessary extensions
RUN apk add --no-cache php83 php83-fpm php83-opcache caddy php83-curl

RUN sed -i 's/nobody/caddy/g' /etc/php83/php-fpm.d/www.conf
RUN sed -i 's/E_ALL \& ~E_DEPRECATED \& ~E_STRICT/E_ALL \& ~E_DEPRECATED \& ~E_STRICT \& ~E_NOTICE \& ~E_WARNING/g' /etc/php83/php.ini

# Copy the contents of the web/ directory to /srv
COPY web/ /srv

# Copy the Caddyfile to the container
COPY docker/Caddyfile /etc/caddy/Caddyfile

# Set the default command to start PHP-FPM and Caddy
CMD ["sh", "-c", "php-fpm83 && caddy run --config /etc/caddy/Caddyfile"]
