
echo ' [+] Starting php'
php-fpm84

cd /srv/src
composer install

echo ' [+] Starting Chrome'
chromedriver --port=4444 &


chmod 777 /srv/cache
chmod 777 /srv/logs

echo ' [+] Building config'
_buildConfig() {
    echo "<?php"
    echo "date_default_timezone_set('Europe/Vienna');"
    echo "define('URL','${URL:-http://localhost:8080}');"
    echo ""
}

_buildConfig > src/inc/config.inc.php

caddy run --config /etc/caddy/Caddyfile