# FROM node:18.0

# ARG UID=1000
# ARG GID=1000

# RUN groupmod -g $GID node && \
#     usermod -u $UID -g $GID node

# WORKDIR /var/www

# COPY init-node.sh /usr/local/bin/init-node.sh
# RUN chmod +x /usr/local/bin/init-node.sh  # <-- Esto lo hacemos con root

# USER node

# CMD ["/usr/local/bin/init-node.sh"]

# --------------------------

FROM node:18.0

# Carpeta de trabajo
WORKDIR /var/www

# Copiar el script y dar permisos de ejecución
COPY init-node.sh /usr/local/bin/init-node.sh
RUN chmod +x /usr/local/bin/init-node.sh

# Usar usuario root (igual que Apache) para evitar problemas de permisos
USER root

# Comando por defecto
CMD ["/usr/local/bin/init-node.sh"]
