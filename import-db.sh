#!/bin/bash

# Script para importar la base de datos en Railway
echo "Importando base de datos..."

# Esperar a que MySQL esté listo
sleep 5

# Importar el dump SQL
mysql -h $DB_HOST -P $DB_PORT -u $DB_USER -p$DB_PASSWORD $DB_NAME < futmatch_db.sql

echo "Base de datos importada exitosamente"
