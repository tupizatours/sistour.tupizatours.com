<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

function fixPermissions($path) {
    if (!file_exists($path)) {
        echo "❌ No existe: $path<br>";
        return;
    }
    
    echo "✅ Corrigiendo permisos: $path<br>";
    chmod($path, 0777);
}

$paths = [
    __DIR__ . '/../storage',
    __DIR__ . '/../storage/logs',
    __DIR__ . '/../storage/framework',
    __DIR__ . '/../storage/framework/cache',
    __DIR__ . '/../storage/framework/sessions',
    __DIR__ . '/../storage/framework/views'
];

foreach ($paths as $path) {
    fixPermissions($path);
}

echo "<br>🚀 Permisos corregidos. Intenta ejecutar nuevamente cache:clear.";
?>
