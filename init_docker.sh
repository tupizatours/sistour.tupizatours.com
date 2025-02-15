#!/bin/bash

echo "🔧 Conectando phpMyAdmin con MySQL..."

# Verificar si phpMyAdmin está corriendo
if [ "$(sudo docker ps -q -f name=phpmyadmin)" ]; then
    echo "✅ phpMyAdmin está corriendo, aplicando configuraciones..."

    # Ejecutar los comandos necesarios dentro del contenedor
    sudo docker exec -it phpmyadmin sh -c "
        apt update && apt install -y iputils-ping;
        apt update && apt install -y netcat-openbsd;
        nc -zv mysql 3306
    "

    # Conectar phpMyAdmin a la red de Docker
    sudo docker network connect sistourtupizatourscom_tupiza_network phpmyadmin

    echo "✅ Configuración aplicada con éxito."
else
    echo "❌ phpMyAdmin no está corriendo. Verifica si el servicio está levantado."
fi