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

$usuarioId    = $_SESSION['usuario_id'];
$actual       = $_POST['password_actual'] ?? '';
$nueva        = $_POST['password_nueva'] ?? '';
$confirmacion = $_POST['password_confirmacion'] ?? '';

if (strlen($nueva) < 8) {
    http_response_code(422);
    echo json_encode(['ok' => false, 'error' => 'La nueva contraseña debe tener al menos 8 caracteres.']);
    exit;
}
if ($nueva !== $confirmacion) {
    http_response_code(422);
    echo json_encode(['ok' => false, 'error' => 'La confirmación no coincide con la nueva contraseña.']);
    exit;
}

$pdo = conectarDB();

$stmt = $pdo->prepare('SELECT password_hash FROM usuarios WHERE id = :id');
$stmt->execute(['id' => $usuarioId]);
$usuario = $stmt->fetch();

if (!$usuario || !password_verify($actual, $usuario['password_hash'])) {
    http_response_code(401);
    echo json_encode(['ok' => false, 'error' => 'La contraseña actual es incorrecta.']);
    exit;
}

$nuevoHash = password_hash($nueva, PASSWORD_BCRYPT);
$stmt = $pdo->prepare('UPDATE usuarios SET password_hash = :hash WHERE id = :id');
$stmt->execute(['hash' => $nuevoHash, 'id' => $usuarioId]);

echo json_encode(['ok' => true, 'mensaje' => 'Contraseña actualizada correctamente.']);
