#!/bin/sh
set -e

echo "Syncing default files to /var/www/html (only if needed)..."
rsync -a --update /defaults/ /var/www/html/


chown -R $USERNAME:$USERNAME /var/www/html

exec nginx -g 'daemon off;'