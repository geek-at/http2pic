FROM alpine:3.21

# Install PHP and necessary extensions
RUN apk add --no-cache curl php83 php83-fpm php83-opcache caddy php83-curl php83-xdebug chromium-chromedriver

RUN sed -i 's/nobody/caddy/g' /etc/php83/php-fpm.d/www.conf
RUN sed -i 's/E_ALL \& ~E_DEPRECATED \& ~E_STRICT/E_ALL \& ~E_DEPRECATED \& ~E_STRICT \& ~E_NOTICE \& ~E_WARNING/g' /etc/php83/php.ini

# Install additional PHP extensions
RUN apk add --no-cache php83-ctype php83-dom php83-fileinfo php83-gd php83-iconv php83-simplexml php83-xml php83-xmlreader php83-xmlwriter php83-zip php83-phar php83-openssl
RUN curl -sS https://getcomposer.org/installer | /usr/bin/php83 -- --install-dir=/usr/bin --filename=composer

ADD docker/start.sh /start.sh
RUN chmod +x /start.sh

# Copy the contents of the web/ directory to /srv
ADD . /srv

# Cleanup
RUN rm -rf /srv/.git
RUN rm -rf /srv/.github
RUN rm -rf /srv/.vscode


# Copy the Caddyfile to the container
COPY docker/Caddyfile /etc/caddy/Caddyfile

# Run start script
CMD ["sh", "-c", "/start.sh"]
