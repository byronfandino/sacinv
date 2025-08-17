#!/bin/bash

# Detectar si estamos en Linux o Windows (Docker Desktop)
if grep -qi microsoft /proc/version; then
    echo "Se detectò sistema operativo Windows, omitiendo ajustes de permisos..."
else
    echo "Se detectó sistema operativo linux, ajustando permisos..."
    LOCAL_UID=${LOCAL_UID:-1000}
    LOCAL_GID=${LOCAL_GID:-1000}
    chown -R $LOCAL_UID:$LOCAL_GID /var/www/src
    chmod -R ug+rwX /var/www/src
fi

echo "Instalando dependencias NPM"
npm install

echo "Ejecutando Gulp en modo dev"
npx gulp dev

# Mantener contenedor corriendo
echo "Contenedor en ejecución, manteniendo activo..."
tail -f /dev/null
