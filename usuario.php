<?php

// Importar la conexión
require 'includes/app.php';

$db = conectadDB(); // Asegúrate de que esta función retorne un objeto PDO válido

// Crear un email y password
$email = 'correo@correo.com';
$password = '123456';

// Validar la contraseña
if (strlen($password) < 6) {
    echo "La contraseña debe tener al menos 6 caracteres.";
    exit; // Termina la ejecución si no cumple con los requisitos
}

// Hashear la contraseña
$passwordHash = password_hash($password, PASSWORD_BCRYPT);

// Query para crear el usuario (consulta preparada)
$query = "INSERT INTO usuarios (email, password) VALUES (:email, :password)";

try {
    // Preparar la consulta
    $stmt = $db->prepare($query);


    // Ejecutar con parámetros seguros
    $stmt->execute([
        ':email' => $email,

        ':password' => $passwordHash, // Aquí usas el password hasheado
    ]);

    echo "Usuario insertado correctamente.";
} catch (PDOException $e) {
    echo "Error al insertar el usuario: " . $e->getMessage();
}
