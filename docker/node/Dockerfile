FROM node:18-slim

WORKDIR /var/www

COPY init-node.sh /usr/local/bin/init-node.sh
RUN chmod +x /usr/local/bin/init-node.sh

# Mantener root si necesitas manipular permisos
USER root

CMD ["/usr/local/bin/init-node.sh"]
