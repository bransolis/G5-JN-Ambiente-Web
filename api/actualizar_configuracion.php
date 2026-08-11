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

$usuarioId           = $_SESSION['usuario_id'];
$notificacionesEmail = isset($_POST['notificaciones_email']) ? 1 : 0;
$notificacionesPush  = isset($_POST['notificaciones_push']) ? 1 : 0;
$perfilPublico        = isset($_POST['perfil_publico']) ? 1 : 0;
$idioma               = in_array($_POST['idioma'] ?? 'es', ['es', 'en'], true) ? $_POST['idioma'] : 'es';

$pdo = conectarDB();

$stmt = $pdo->prepare(
    'UPDATE configuracion_usuario
     SET notificaciones_email = :ne, notificaciones_push = :np, perfil_publico = :pp, idioma = :idioma
     WHERE usuario_id = :id'
);
$stmt->execute([
    'ne'     => $notificacionesEmail,
    'np'     => $notificacionesPush,
    'pp'     => $perfilPublico,
    'idioma' => $idioma,
    'id'     => $usuarioId,
]);

echo json_encode(['ok' => true, 'mensaje' => 'Preferencias guardadas correctamente.']);
