<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/session_check.php';

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'error' => 'Método no permitido']);
    exit;
}

$nombre    = trim($_POST['nombre'] ?? '');
$apellidos = trim($_POST['apellidos'] ?? '');
$email     = trim(strtolower($_POST['email'] ?? ''));
$password  = $_POST['password'] ?? '';
$terminos  = isset($_POST['terminos']);

$errores = [];

if ($nombre === '' || mb_strlen($nombre) > 80) {
    $errores[] = 'El nombre es obligatorio (máx. 80 caracteres).';
}
if ($apellidos === '' || mb_strlen($apellidos) > 120) {
    $errores[] = 'Los apellidos son obligatorios (máx. 120 caracteres).';
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errores[] = 'El correo electrónico no es válido.';
}
if (strlen($password) < 8) {
    $errores[] = 'La contraseña debe tener al menos 8 caracteres.';
}
if (!$terminos) {
    $errores[] = 'Debes aceptar los Términos de servicio y la Política de privacidad.';
}

if ($errores) {
    http_response_code(422);
    echo json_encode(['ok' => false, 'error' => implode(' ', $errores)]);
    exit;
}

$pdo = conectarDB();

// Correo duplicado
$stmt = $pdo->prepare('SELECT id FROM usuarios WHERE email = :email');
$stmt->execute(['email' => $email]);
if ($stmt->fetch()) {
    http_response_code(409);
    echo json_encode(['ok' => false, 'error' => 'Ya existe una cuenta con ese correo electrónico.']);
    exit;
}

$base = preg_replace('/[^a-z0-9]/', '', explode('@', $email)[0]);
$base = $base !== '' ? $base : 'usuario';
$username = $base;
$sufijo = 1;
$stmtCheck = $pdo->prepare('SELECT id FROM usuarios WHERE username = :u');
while (true) {
    $stmtCheck->execute(['u' => $username]);
    if (!$stmtCheck->fetch()) {
        break;
    }
    $username = $base . $sufijo;
    $sufijo++;
}

$hash = password_hash($password, PASSWORD_BCRYPT);

try {
    $pdo->beginTransaction();

    $stmt = $pdo->prepare(
        'INSERT INTO usuarios (nombre, apellidos, username, email, password_hash)
         VALUES (:nombre, :apellidos, :username, :email, :hash)'
    );
    $stmt->execute([
        'nombre'    => $nombre,
        'apellidos' => $apellidos,
        'username'  => $username,
        'email'     => $email,
        'hash'      => $hash,
    ]);

    $usuarioId = (int) $pdo->lastInsertId();

    $stmt = $pdo->prepare(
        'INSERT INTO configuracion_usuario (usuario_id) VALUES (:id)'
    );
    $stmt->execute(['id' => $usuarioId]);

    $pdo->commit();
} catch (PDOException $e) {
    $pdo->rollBack();
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => 'No se pudo crear la cuenta. Intenta de nuevo.']);
    exit;
}

session_regenerate_id(true);
$_SESSION['usuario_id'] = $usuarioId;
$_SESSION['username']   = $username;

echo json_encode(['ok' => true, 'redirect' => 'panel.php']);
