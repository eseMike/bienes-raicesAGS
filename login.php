<?php
require 'includes/app.php';
session_start();

$db = conectarDB(); // Ya devuelve PDO

$errores = [];

// Generar token CSRF si no existe
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Protección fuerza bruta
$intentosMaximos = 5;
$tiempoBloqueo = 900;

if (!isset($_SESSION['intentos_fallidos'])) {
    $_SESSION['intentos_fallidos'] = 0;
    $_SESSION['ultimo_intento'] = time();
}

if ($_SESSION['intentos_fallidos'] >= $intentosMaximos) {
    $tiempoPasado = time() - $_SESSION['ultimo_intento'];
    if ($tiempoPasado < $tiempoBloqueo) {
        $errores[] = "Has intentado muchas veces. Intenta de nuevo en " . ceil(($tiempoBloqueo - $tiempoPasado) / 60) . " minutos.";
    } else {
        $_SESSION['intentos_fallidos'] = 0;
    }
}

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validar CSRF
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $errores[] = "Token CSRF no válido.";
    }

    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
    $password = trim($_POST['password'] ?? '');

    if (!$email) {
        $errores[] = "El email es obligatorio o no es válido.";
    }

    if (!$password) {
        $errores[] = "El password es obligatorio.";
    }

    if (empty($errores)) {
        try {
            $query = "SELECT * FROM usuarios WHERE email = :email LIMIT 1";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$usuario) {
                $errores[] = "Usuario no existe.";
                $_SESSION['intentos_fallidos']++;
                $_SESSION['ultimo_intento'] = time();
            } else {
                if (password_verify($password, $usuario['password'])) {
                    $_SESSION['usuario'] = $usuario['email'];
                    $_SESSION['login'] = true;
                    $_SESSION['intentos_fallidos'] = 0;
                    header('Location: /admin');
                    exit;
                } else {
                    $errores[] = "Password incorrecto.";
                    $_SESSION['intentos_fallidos']++;
                    $_SESSION['ultimo_intento'] = time();
                }
            }
        } catch (PDOException $e) {
            $errores[] = "Error en la base de datos: " . $e->getMessage();
        }
    }
}
?>

<?php incluirTemplate('header'); ?>

<main class="contenedor seccion contenido-centrado">
    <h1>Iniciar Sesión</h1>

    <?php foreach ($errores as $error) : ?>
        <div class="alerta error"><?php echo htmlspecialchars($error); ?></div>
    <?php endforeach; ?>

    <form class="form" action="login.php" method="POST">
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

<?php incluirTemplate('footer'); ?>