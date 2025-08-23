<?php
require 'funciones.php';
require 'config/database.php';
require __DIR__ . '/../vendor/autoload.php';

$db = conectarDB(); // 💥 Aquí creamos la conexión y la dejamos disponible

use App\Propiedad;
