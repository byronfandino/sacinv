#!/bin/bash
echo "Ejecutando Composer Install..."
composer install

# Mantener Apache en primer plano
exec apache2-foreground
