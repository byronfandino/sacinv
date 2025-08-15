# #!/bin/bash
# echo "Ajustando permisos y propietario..."

# # Usar variables o valores por defecto
# LOCAL_UID=${LOCAL_UID:-1000}
# LOCAL_GID=${LOCAL_GID:-1000}

# chown -R $LOCAL_UID:$LOCAL_GID /var/www/src
# chmod -R ug+rwX /var/www/src

# echo "Instalando dependencias NPM..."
# npm install

# echo "Ejecutando Gulp en modo dev..."
# npx gulp dev

# # Reajustar permisos después de npm/gulp
# chown -R $LOCAL_UID:$LOCAL_GID /var/www/src
# chmod -R ug+rwX /var/www/src

# # Mantener el contenedor vivo
# tail -f /dev/null

#!/bin/bash

# Detectar si el usuario actual es root
if [ "$(id -u)" -eq 0 ]; then
    echo "Ejecutando como root: no es necesario ajustar propietario."
else
    echo "Ajustando permisos y propietario..."
    LOCAL_UID=${LOCAL_UID:-1000}
    LOCAL_GID=${LOCAL_GID:-1000}
    chown -R $LOCAL_UID:$LOCAL_GID /var/www/src
    chmod -R ug+rwX /var/www/src
fi

echo "Instalando dependencias NPM..."
npm install

echo "Ejecutando Gulp en modo dev..."
npx gulp dev

# Si NO es root, volver a ajustar permisos después de npm/gulp
if [ "$(id -u)" -ne 0 ]; then
    chown -R $LOCAL_UID:$LOCAL_GID /var/www/src
    chmod -R ug+rwX /var/www/src
fi

# Mantener el contenedor vivo
tail -f /dev/null
