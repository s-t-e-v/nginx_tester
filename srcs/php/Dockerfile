FROM php:7.4-fpm

# Create a non-root user and group with fixed UID:GID
ENV USERNAME=webuser
ARG UID
ARG GID

RUN groupadd -g $GID $USERNAME \
    && useradd -m -u $UID -g $GID -s /bin/bash $USERNAME

# Set working directory
WORKDIR /var/www/html

# Set permissions for the web root
RUN chown -R $USERNAME:$USERNAME /var/www/html

# Switch to the non-root user
USER $USERNAME

# php-fpm still runs as www-data by default,
# but file operations will now use this user.
