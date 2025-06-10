FROM alpine:3.21

# Install PHP and necessary extensions
RUN apk add --no-cache curl php84 php84-fpm php84-opcache caddy php84-curl php84-xdebug chromium-chromedriver

RUN sed -i 's/nobody/caddy/g' /etc/php84/php-fpm.d/www.conf
RUN sed -i 's/E_ALL \& ~E_DEPRECATED \& ~E_STRICT/E_ALL \& ~E_DEPRECATED \& ~E_STRICT \& ~E_NOTICE \& ~E_WARNING/g' /etc/php84/php.ini
# log php errors to /srv/logs/php_errors.log
RUN echo "error_log = /srv/logs/php_errors.log" >> /etc/php84/php.ini

# Install additional PHP extensions
RUN apk add --no-cache php84-ctype php84-dom php84-fileinfo php84-gd php84-iconv php84-simplexml php84-xml php84-xmlreader php84-xmlwriter php84-zip php84-phar php84-openssl
RUN curl -sS https://getcomposer.org/installer | /usr/bin/php84 -- --install-dir=/usr/bin --filename=composer

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
