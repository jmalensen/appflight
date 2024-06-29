FROM node:20.15

# Set working directory
WORKDIR /var/www/html

# Copy existing application directory contents to the working directory
COPY . /var/www/html

# Copy existing application directory permissions
COPY --chown=www-data:www-data . /var/www

RUN chown -R node:node /var/www/html && \
    chmod -R 755 /var/www/html

# Change current user to node
USER node

# Expose the default Vite development server port
EXPOSE 3000