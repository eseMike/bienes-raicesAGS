<?php

require 'includes/app.php';

$db = conectadDB();

$errores = [];

// Autenticar el Usuario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'];

    if (!$email) {
        $errores[] = "El email es obligatorio o no es válido";
    }

    if (!$password) {
        $errores[] = "El password es obligatorio";
    }

    if (empty($errores)) {
        // Importar la conexión
        $query = "SELECT * FROM usuarios WHERE email = :email";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$resultado) {
            $errores[] = "Usuario no existe";
        } else {
            // Revisar si el password es correcto
            $auth = password_verify($password, $resultado['password']);

            if ($auth) {
                // Autenticar el usuario
                session_start();

                // Llenar el arreglo de la sesión
                $_SESSION['usuario'] = $resultado['email'];
                $_SESSION['login'] = true;

                // Redirigir al área de administración
                header('Location: /admin');
                exit;
            } else {
                $errores[] = "Password incorrecto";
            }
        }
    }
}

// Incluye el header
incluirTemplate('header');
?>

<main class="contenedor seccion contenido-centrado">
    <h1>Iniciar Sesión</h1>

    <!-- Mostrar errores -->
    <?php foreach ($errores as $error) : ?>
        <div class="alerta error">
            <?php echo $error; ?>
        </div>
    <?php endforeach; ?>

    <form class="form" action="login.php" method="POST">
        <fieldset>
            <legend>Email y Password</legend>

            <label for="email">E-mail</label>
            <input type="email" name="email" placeholder="Tu Email" id="email">

            <label for="password">Password</label>
            <input type="password" name="password" placeholder="Tu Password" id="password">

            <input type="submit" value="Iniciar Sesión" class="boton boton-verde">
        </fieldset>
    </form>
</main>

<?php
include 'includes/templates/footer.php';
?>