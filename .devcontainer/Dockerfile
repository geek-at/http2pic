FROM alpine:3.21


RUN apk add --no-cache git curl php83 php83-fpm php83-opcache caddy php83-curl php83-xdebug chromium-chromedriver

ADD .devcontainer/start.sh /start.sh
RUN chmod +x /start.sh

RUN apk add --no-cache php83-ctype php83-dom php83-fileinfo php83-gd php83-iconv php83-simplexml php83-xml php83-xmlreader php83-xmlwriter php83-zip php83-phar php83-openssl
RUN curl -sS https://getcomposer.org/installer | /usr/bin/php83 -- --install-dir=/usr/bin --filename=composer

# caddy stuff
ADD .devcontainer/Caddyfile /etc/caddy/Caddyfile
RUN sed -i 's/nobody/caddy/g' /etc/php83/php-fpm.d/www.conf
RUN sed -i 's/E_ALL \& ~E_DEPRECATED \& ~E_STRICT/E_ALL \& ~E_DEPRECATED \& ~E_STRICT \& ~E_NOTICE \& ~E_WARNING/g' /etc/php83/php.ini

# configure xdebug
RUN echo "zend_extension=xdebug.so" > /etc/php83/conf.d/xdebug.ini
RUN echo "xdebug.mode=debug" >> /etc/php83/conf.d/xdebug.ini    
RUN echo "xdebug.start_with_request=yes" >> /etc/php83/conf.d/xdebug.ini
RUN echo "xdebug.client_host=127.0.0.1" >> /etc/php83/conf.d/xdebug.ini
RUN echo "xdebug.client_port=9003" >> /etc/php83/conf.d/xdebug.ini
RUN echo "xdebug.idekey=VSCODE" >> /etc/php83/conf.d/xdebug.ini
