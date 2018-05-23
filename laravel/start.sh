#!/bin/bash
# ----------------------------------------------------------------------
# Create the .env file if it does not exist.
# ----------------------------------------------------------------------

if [[ ! -f "/var/www/.env" ]] && [[ -f "/var/www/.env.example" ]];
then
cp /var/www/.env.example /var/www/.env
fi

# ----------------------------------------------------------------------
# Run Composer & migrate & seed database
# ----------------------------------------------------------------------

if [[ ! -d "/var/www/vendor" ]];
then
cd /var/www
composer update
composer dump-autoload -o
fi

# ----------------------------------------------------------------------
# Migrate & seed database
# ----------------------------------------------------------------------
php artisan migrate --seed

# ----------------------------------------------------------------------
# Start supervisord
# ----------------------------------------------------------------------

exec /usr/bin/supervisord -n -c /etc/supervisord.conf
