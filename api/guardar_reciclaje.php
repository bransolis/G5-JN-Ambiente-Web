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

$material = trim($_POST['material'] ?? '');
$kilos    = filter_var($_POST['kilos'] ?? '', FILTER_VALIDATE_FLOAT);
$centro   = trim($_POST['centro'] ?? '');
$puntos   = filter_var($_POST['puntos'] ?? '', FILTER_VALIDATE_INT);
$fecha    = $_POST['fecha'] ?? '';

if ($material === '' || $kilos === false || $kilos <= 0 || $centro === ''
    || $puntos === false || $puntos < 0
    || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha)) {
    http_response_code(422);
    echo json_encode(['status' => 'error', 'message' => 'Datos inválidos. Revisa el formulario.']);
    exit;
}

$pdo = conectarDB();

try {
    $pdo->beginTransaction();

    $stmt = $pdo->prepare(
        'INSERT INTO registro_reciclaje (usuario_id, material, kilos, centro_acopio, puntos_ganados, fecha_registro)
         VALUES (:usuario_id, :material, :kilos, :centro, :puntos, :fecha)'
    );
    $stmt->execute([
        'usuario_id' => $usuarioId,
        'material'   => $material,
        'kilos'      => $kilos,
        'centro'     => $centro,
        'puntos'     => $puntos,
        'fecha'      => $fecha,
    ]);

    $stmt = $pdo->prepare(
        'UPDATE usuarios SET puntos = puntos + :puntos, kg_reciclado = kg_reciclado + :kilos WHERE id = :id'
    );
    $stmt->execute(['puntos' => $puntos, 'kilos' => $kilos, 'id' => $usuarioId]);

    $pdo->commit();
} catch (PDOException $e) {
    $pdo->rollBack();
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'No se pudo guardar el registro.']);
    exit;
}

echo json_encode(['status' => 'success', 'message' => 'Reciclaje guardado en base de datos']);
