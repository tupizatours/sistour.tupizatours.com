#!/bin/bash

#!/bin/bash

echo "🧹 Limpiando entorno Laravel (servidor embebido)..."

# 1. Limpiar vistas compiladas
php artisan view:clear

# 2. Limpiar rutas cacheadas
php artisan route:clear

# 3. Limpiar configuración cacheada
php artisan config:clear

# 4. Limpiar caché general de la app
php artisan cache:clear

# 5. Borrar vistas compiladas directamente
rm -rf storage/framework/views/*

# 6. Borrar sesiones activas (porque estás en desarrollo, no hay riesgo)
rm -rf storage/framework/sessions/*

# 7. Confirmación
echo "✅ Limpieza completada. Reinicia el navegador o usa modo incógnito."

# 8. Opcional: volver a compilar cache (solo si es necesario)
# php artisan config:cache
# php artisan view:cache
