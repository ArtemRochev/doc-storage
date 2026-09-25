#!/bin/sh
set -e

# Start php-fpm in the background
php-fpm -D

# Run nginx in the foreground so it becomes the container's main process
exec nginx -g "daemon off;"
