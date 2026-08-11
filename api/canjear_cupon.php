<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/session_check.php';

header('Content-Type: application/json; charset=utf-8');

if (!usuarioAutenticado()) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'Debes iniciar sesión.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Método no permitido']);
    exit;
}

$usuarioId = $_SESSION['usuario_id'];

$recompensa = trim($_POST['recompensa'] ?? '');
$puntos     = filter_var($_POST['puntos'] ?? '', FILTER_VALIDATE_INT);

if ($recompensa === '' || $puntos === false || $puntos <= 0) {
    http_response_code(422);
    echo json_encode(['status' => 'error', 'message' => 'Datos inválidos.']);
    exit;
}

$pdo = conectarDB();

try {
    $pdo->beginTransaction();

    // Bloquea la fila del usuario para evitar canjes dobles en simultáneo.
    $stmt = $pdo->prepare('SELECT puntos FROM usuarios WHERE id = :id FOR UPDATE');
    $stmt->execute(['id' => $usuarioId]);
    $usuario = $stmt->fetch();

    if (!$usuario || $usuario['puntos'] < $puntos) {
        $pdo->rollBack();
        http_response_code(422);
        echo json_encode(['status' => 'error', 'message' => 'No tienes suficientes puntos para este cupón.']);
        exit;
    }


    $codigo = 'REUSE-' . random_int(100000, 999999);

    $stmt = $pdo->prepare(
        'INSERT INTO historial_canjes (usuario_id, recompensa, puntos_usados, codigo_cupon)
         VALUES (:usuario_id, :recompensa, :puntos, :codigo)'
    );
    $stmt->execute([
        'usuario_id' => $usuarioId,
        'recompensa' => $recompensa,
        'puntos'     => $puntos,
        'codigo'     => $codigo,
    ]);

    $stmt = $pdo->prepare('UPDATE usuarios SET puntos = puntos - :puntos WHERE id = :id');
    $stmt->execute(['puntos' => $puntos, 'id' => $usuarioId]);

    $pdo->commit();
} catch (PDOException $e) {
    $pdo->rollBack();
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'No se pudo registrar el canje.']);
    exit;
}

echo json_encode([
    'status'  => 'success',
    'mensaje' => 'Canje registrado correctamente',
    'codigo'  => $codigo,
]);
