#!/bin/bash

HOST_VOLUME=./data-postgres
LOCAL_USER=$(whoami)  # Usuario actual de Ubuntu

# UID y GID fijos del usuario postgres en la imagen oficial
POSTGRES_UID=999
POSTGRES_GID=999

# Crear el directorio si no existe
if [ ! -d "$HOST_VOLUME" ]; then
  echo "El directorio $HOST_VOLUME no existe. Creándolo..."
  mkdir -p "$HOST_VOLUME"
fi

echo "Ajustando propietario y permisos del directorio $HOST_VOLUME..."

# Cambiar propietario al usuario postgres de la imagen
sudo chown -R $POSTGRES_UID:$POSTGRES_GID $HOST_VOLUME

# Permisos para que propietario y grupo puedan leer, escribir y ejecutar
sudo chmod -R 770 $HOST_VOLUME

# Agregar usuario Ubuntu al grupo del directorio
DIR_GROUP=$(stat -c "%G" $HOST_VOLUME)
sudo usermod -aG $DIR_GROUP $LOCAL_USER

echo "Permisos ajustados correctamente."
echo "Tu usuario ($LOCAL_USER) puede acceder al directorio sin sudo."
echo "Nota: es posible que necesites cerrar sesión y volver a entrar para que los cambios de grupo tengan efecto."
