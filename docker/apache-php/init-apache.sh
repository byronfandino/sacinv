#!/bin/bash
echo "Ejecutando Composer Install..."
composer install

# Mantener Apache en primer plano
apache2-foreground
