<?php
function conectarDB()
{
    try {
        $db = new PDO('mysql:host=127.0.0.1;dbname=bienesraices_vibo', 'root', '');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Manejo de errores
        return $db;
    } catch (PDOException $e) {
        echo "Error en la conexión: " . $e->getMessage();
        exit;
    }
}
