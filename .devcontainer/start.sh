WORKSPACE_PATH=$( pwd )
# Escape the path for safe use in sed
ESCAPED_PATH=$(echo "$WORKSPACE_PATH" | sed 's/\//\\\//g')

echo "[!] Folder is $WORKSPACE_PATH"

echo ' [+] Starting php'
php-fpm83

echo ' [+] Starting Caddy'
sed -i "s|xxxxxxxxxx|$ESCAPED_PATH\/web|g" /etc/caddy/Caddyfile

cd ${WORKSPACE_PATH}/src
composer install

echo ' [+] Starting Chrome'
chromedriver --port=4444 &


chmod 777 ${WORKSPACE_PATH}/cache
chmod 777 ${WORKSPACE_PATH}/logs

caddy run --config /etc/caddy/Caddyfile