<?php
require 'includes/app.php';

session_start(); // Asegurar que la sesión está iniciada

$db = conectadDB();

$errores = [];

// Generar token CSRF si no existe
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Protección contra fuerza bruta: bloquear tras 5 intentos fallidos en 15 minutos
$intentosMaximos = 5;
$tiempoBloqueo = 900; // 15 minutos en segundos

if (!isset($_SESSION['intentos_fallidos'])) {
    $_SESSION['intentos_fallidos'] = 0;
    $_SESSION['ultimo_intento'] = time();
}

// Si hay demasiados intentos, verificar el tiempo de bloqueo
if ($_SESSION['intentos_fallidos'] >= $intentosMaximos) {
    $tiempoPasado = time() - $_SESSION['ultimo_intento'];
    if ($tiempoPasado < $tiempoBloqueo) {
        $errores[] = "Has intentado muchas veces. Intenta de nuevo en " . ceil(($tiempoBloqueo - $tiempoPasado) / 60) . " minutos.";
    } else {
        $_SESSION['intentos_fallidos'] = 0; // Reiniciar intentos después del tiempo de bloqueo
    }
}

// Autenticar el Usuario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validar CSRF
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $errores[] = "Token CSRF no válido.";
    }

    // Validar email y contraseña
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
    $password = trim($_POST['password'] ?? '');

    if (!$email) {
        $errores[] = "El email es obligatorio o no es válido.";
    }

    if (!$password) {
        $errores[] = "El password es obligatorio.";
    }

    if (empty($errores)) {
        // Importar la conexión
        $query = "SELECT * FROM usuarios WHERE email = :email LIMIT 1";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$resultado) {
            $errores[] = "Usuario no existe.";
            $_SESSION['intentos_fallidos']++;
            $_SESSION['ultimo_intento'] = time();
        } else {
            // Revisar si el password es correcto
            if (password_verify($password, $resultado['password'])) {
                // Autenticar el usuario
                $_SESSION['usuario'] = $resultado['email'];
                $_SESSION['login'] = true;
                $_SESSION['intentos_fallidos'] = 0; // Reiniciar intentos fallidos

                // Redirigir al área de administración
                header('Location: /admin');
                exit;
            } else {
                $errores[] = "Password incorrecto.";
                $_SESSION['intentos_fallidos']++;
                $_SESSION['ultimo_intento'] = time();
            }
        }
    }
}

// Incluir el header
incluirTemplate('header');
?>

<main class="contenedor seccion contenido-centrado">
    <h1>Iniciar Sesión</h1>

    <!-- Mostrar errores -->
    <?php foreach ($errores as $error) : ?>
        <div class="alerta error">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endforeach; ?>

    <form class="form" action="login.php" method="POST">
        <!-- Token CSRF para seguridad -->
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

        <fieldset>
            <legend>Email y Password</legend>

            <label for="email">E-mail</label>
            <input type="email" name="email" placeholder="Tu Email" id="email" required>

            <label for="password">Password</label>
            <input type="password" name="password" placeholder="Tu Password" id="password" required>

            <input type="submit" value="Iniciar Sesión" class="boton boton-verde">
        </fieldset>
    </form>
</main>

<?php
include 'includes/templates/footer.php';
?>