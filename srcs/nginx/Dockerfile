FROM nginx:alpine

ARG USERNAME=webuser
ARG UID
ARG GID

# Create user matching host UID (same as PHP container)
RUN addgroup -g $GID $USERNAME && \
    adduser -D -u $UID -G $USERNAME $USERNAME

COPY conf/nginx.conf /etc/nginx/nginx.conf
COPY www /var/www/html
    
# Set ownership of web root (won't matter with bind mount, but good default)
RUN chown -R $USERNAME:$USERNAME /var/www/html

# Run Nginx as the new user
RUN sed -i "s/user nginx;/user $USERNAME;/" /etc/nginx/nginx.conf

EXPOSE 80

CMD ["nginx", "-g", "daemon off;"]
