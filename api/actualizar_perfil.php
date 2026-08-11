<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/session_check.php';

header('Content-Type: application/json; charset=utf-8');

if (!usuarioAutenticado()) {
    http_response_code(401);
    echo json_encode(['ok' => false, 'error' => 'Debes iniciar sesión.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'error' => 'Método no permitido']);
    exit;
}

$usuarioId = $_SESSION['usuario_id'];

$nombre    = trim($_POST['nombre'] ?? '');
$apellidos = trim($_POST['apellidos'] ?? '');
$email     = trim(strtolower($_POST['email'] ?? ''));
$username  = trim($_POST['username'] ?? '');
$username  = ltrim($username, '@');

$errores = [];
if ($nombre === '' || mb_strlen($nombre) > 80) {
    $errores[] = 'El nombre es obligatorio.';
}
if ($apellidos === '' || mb_strlen($apellidos) > 120) {
    $errores[] = 'Los apellidos son obligatorios.';
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errores[] = 'El correo electrónico no es válido.';
}
if ($username === '' || !preg_match('/^[a-zA-Z0-9_.]{3,50}$/', $username)) {
    $errores[] = 'El nombre de usuario debe tener entre 3 y 50 caracteres (letras, números, "_" o ".").';
}

if ($errores) {
    http_response_code(422);
    echo json_encode(['ok' => false, 'error' => implode(' ', $errores)]);
    exit;
}

$pdo = conectarDB();

$stmt = $pdo->prepare('SELECT id FROM usuarios WHERE (email = :email OR username = :username) AND id != :id');
$stmt->execute(['email' => $email, 'username' => $username, 'id' => $usuarioId]);
if ($stmt->fetch()) {
    http_response_code(409);
    echo json_encode(['ok' => false, 'error' => 'Ese correo o nombre de usuario ya está en uso.']);
    exit;
}

$stmt = $pdo->prepare(
    'UPDATE usuarios SET nombre = :nombre, apellidos = :apellidos, email = :email, username = :username WHERE id = :id'
);
$stmt->execute([
    'nombre'    => $nombre,
    'apellidos' => $apellidos,
    'email'     => $email,
    'username'  => $username,
    'id'        => $usuarioId,
]);

$_SESSION['username'] = $username;

echo json_encode(['ok' => true, 'mensaje' => 'Perfil actualizado correctamente.']);
