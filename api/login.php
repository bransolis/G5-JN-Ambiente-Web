<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/session_check.php';

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'error' => 'Método no permitido']);
    exit;
}

$email    = trim(strtolower($_POST['email'] ?? ''));
$password = $_POST['password'] ?? '';

if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $password === '') {
    http_response_code(422);
    echo json_encode(['ok' => false, 'error' => 'Ingresa un correo y una contraseña válidos.']);
    exit;
}

$pdo = conectarDB();

$stmt = $pdo->prepare(
    'SELECT id, username, password_hash, activo FROM usuarios WHERE email = :email'
);
$stmt->execute(['email' => $email]);
$usuario = $stmt->fetch();

$credencialesInvalidas = ['ok' => false, 'error' => 'Correo o contraseña incorrectos.'];

if (!$usuario || !password_verify($password, $usuario['password_hash'])) {
    http_response_code(401);
    echo json_encode($credencialesInvalidas);
    exit;
}

if (!$usuario['activo']) {
    http_response_code(403);
    echo json_encode(['ok' => false, 'error' => 'Esta cuenta está desactivada.']);
    exit;
}

session_regenerate_id(true);
$_SESSION['usuario_id'] = $usuario['id'];
$_SESSION['username']   = $usuario['username'];

$update = $pdo->prepare('UPDATE usuarios SET ultima_conexion = NOW() WHERE id = :id');
$update->execute(['id' => $usuario['id']]);

echo json_encode(['ok' => true, 'redirect' => 'panel.php']);
