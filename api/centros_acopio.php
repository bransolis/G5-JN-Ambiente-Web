<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/session_check.php';

header('Content-Type: application/json; charset=utf-8');

if (!usuarioAutenticado()) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'Debes iniciar sesión.']);
    exit;
}

$material = trim($_GET['material'] ?? '');
$offset   = filter_var($_GET['offset'] ?? 0, FILTER_VALIDATE_INT) ?: 0;
$limite   = 3;

$pdo = conectarDB();

$sql = 'SELECT id, nombre, direccion, distancia_km, materiales FROM centros_acopio';
$params = [];

if ($material !== '' && $material !== 'Todos') {
    $sql .= ' WHERE materiales LIKE :material';
    $params['material'] = '%' . $material . '%';
}

$sql .= ' ORDER BY distancia_km ASC LIMIT :limite OFFSET :offset';

$stmt = $pdo->prepare($sql);
foreach ($params as $clave => $valor) {
    $stmt->bindValue($clave, $valor, PDO::PARAM_STR);
}
$stmt->bindValue('limite', $limite + 1, PDO::PARAM_INT);
$stmt->bindValue('offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$centros = $stmt->fetchAll();

$hayMas = count($centros) > $limite;
if ($hayMas) {
    array_pop($centros);
}

echo json_encode([
    'status'  => 'success',
    'centros' => $centros,
    'hayMas'  => $hayMas,
]);
