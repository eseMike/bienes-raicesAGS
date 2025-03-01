<?php
function conectadDB() {
    try {
        $db = new PDO('mysql:host=localhost;dbname=bienesraices_crud', 'root', '');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Manejo de errores
        return $db;
    } catch (PDOException $e) {
        echo "Error en la conexión: " . $e->getMessage();
        exit;
    }
}

