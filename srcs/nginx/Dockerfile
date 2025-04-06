FROM nginx:alpine

# Copy the nginx configuration file
COPY conf/nginx.conf /etc/nginx/nginx.conf

# Copy the HTML files
COPY www /var/www/html

# Expose port 80
EXPOSE 80

# Start nginx
CMD ["nginx", "-g", "daemon off;"]