<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

use Illuminate\Support\Facades\Artisan;

// Cargar Laravel
require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    if (isset($_GET['cmd'])) {
        $command = $_GET['cmd'];
        echo "<strong>Ejecutando:</strong> php artisan $command<br>";
        Artisan::call($command);
        echo "<pre>" . Artisan::output() . "</pre><br>";
    } else {
        echo "No se especificó un comando.";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
