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

$pais     = trim($_POST['pais'] ?? '');
$provincia = trim($_POST['estado'] ?? '');
$tipo     = trim($_POST['tipo'] ?? '');
$fecha    = $_POST['fecha'] ?? '';

if ($pais === '' || $provincia === '' || $tipo === '' || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha)) {
    http_response_code(422);
    echo json_encode(['status' => 'error', 'message' => 'Faltan datos']);
    exit;
}

$nivelesPorTipo = [
    'Apartamento'         => 'Bajo',
    'Vivienda'            => 'Medio',
    'Casa independiente'  => 'Alto',
    'Oficina'             => 'Medio',
    'Local comercial'     => 'Alto',
];
$nivel = $nivelesPorTipo[$tipo] ?? 'Medio';

$co2PorNivel = ['Bajo' => 1.2, 'Medio' => 2.8, 'Alto' => 4.5];
$pctPorNivel = ['Bajo' => 25, 'Medio' => 60, 'Alto' => 90];

$pdo = conectarDB();

try {
    $pdo->beginTransaction();

    $stmt = $pdo->prepare(
        'INSERT INTO huella_calculos (usuario_id, pais, provincia, tipo_vivienda, nivel_huella, fecha_calculo)
         VALUES (:usuario_id, :pais, :provincia, :tipo, :nivel, :fecha)'
    );
    $stmt->execute([
        'usuario_id' => $usuarioId,
        'pais'       => $pais,
        'provincia'  => $provincia,
        'tipo'       => $tipo,
        'nivel'      => $nivel,
        'fecha'      => $fecha,
    ]);

    $stmt = $pdo->prepare('UPDATE usuarios SET nivel_huella = :nivel WHERE id = :id');
    $stmt->execute(['nivel' => 'Nivel ' . $nivel, 'id' => $usuarioId]);

    $pdo->commit();
} catch (PDOException $e) {
    $pdo->rollBack();
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'No se pudo guardar la huella de carbono.']);
    exit;
}

echo json_encode([
    'status' => 'success',
    'message' => 'Huella registrada con éxito',
    'nivel' => $nivel,
    'co2' => $co2PorNivel[$nivel],
    'porcentaje' => $pctPorNivel[$nivel],
]);
