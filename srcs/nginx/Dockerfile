FROM nginx:alpine

ENV USERNAME=webuser
ARG UID
ARG GID

RUN apk add --no-cache rsync

# Create user matching host UID (same as PHP container)
RUN addgroup -g $GID $USERNAME && \
    adduser -D -u $UID -G $USERNAME $USERNAME

COPY conf/nginx.conf /etc/nginx/nginx.conf
COPY www /defaults
COPY tools/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh
    
# Set ownership of web root (won't matter with bind mount, but good default)
RUN mkdir -p /var/www/html

# Run Nginx as the new user
RUN sed -i "s/user nginx;/user $USERNAME;/" /etc/nginx/nginx.conf

EXPOSE 80

ENTRYPOINT ["/entrypoint.sh"]
