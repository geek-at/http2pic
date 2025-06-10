FROM alpine:3.21


RUN apk add --no-cache git curl php84 php84-fpm php84-opcache caddy php84-curl php84-xdebug chromium-chromedriver

ADD .devcontainer/start.sh /start.sh
RUN chmod +x /start.sh

RUN apk add --no-cache php84-ctype php84-dom php84-fileinfo php84-gd php84-iconv php84-simplexml php84-xml php84-xmlreader php84-xmlwriter php84-zip php84-phar php84-openssl
RUN curl -sS https://getcomposer.org/installer | /usr/bin/php84 -- --install-dir=/usr/bin --filename=composer

# caddy stuff
ADD .devcontainer/Caddyfile /etc/caddy/Caddyfile
RUN sed -i 's/nobody/caddy/g' /etc/php84/php-fpm.d/www.conf
RUN sed -i 's/E_ALL \& ~E_DEPRECATED \& ~E_STRICT/E_ALL \& ~E_DEPRECATED \& ~E_STRICT \& ~E_NOTICE \& ~E_WARNING/g' /etc/php84/php.ini

# configure xdebug
RUN echo "zend_extension=xdebug.so" > /etc/php84/conf.d/xdebug.ini
RUN echo "xdebug.mode=debug" >> /etc/php84/conf.d/xdebug.ini    
RUN echo "xdebug.start_with_request=yes" >> /etc/php84/conf.d/xdebug.ini
RUN echo "xdebug.client_host=127.0.0.1" >> /etc/php84/conf.d/xdebug.ini
RUN echo "xdebug.client_port=9003" >> /etc/php84/conf.d/xdebug.ini
RUN echo "xdebug.idekey=VSCODE" >> /etc/php84/conf.d/xdebug.ini
