<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$logFile = __DIR__ . '/../storage/logs/laravel.log';

file_put_contents($logFile, date("Y-m-d H:i:s") . " - Test de escritura en logs.\n", FILE_APPEND);

if (file_exists($logFile)) {
    echo "✅ Laravel puede escribir en logs. Revisa el archivo: storage/logs/laravel.log";
} else {
    echo "❌ Laravel NO puede escribir en logs. Posible problema de permisos.";
}
?>
