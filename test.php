<?php
$carpeta = __DIR__ . '/build/img';
if (is_writable($carpeta)) {
    echo "✅ PHP tiene permisos de escritura en $carpeta";
} else {
    echo "❌ PHP NO tiene permisos de escritura en $carpeta";
}
