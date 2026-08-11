<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/session_check.php';
requerirLogin();
$usuario = usuarioActual();
$paginaActiva = 'reciclaje';

$pdo = conectarDB();
$stmt = $pdo->prepare(
    'SELECT material, kilos, centro_acopio, puntos_ganados, fecha_registro
     FROM registro_reciclaje
     WHERE usuario_id = :id
     ORDER BY fecha_registro DESC, id DESC'
);
$stmt->execute(['id' => $usuario['id']]);
$registros = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Historial de reciclaje – REUSE</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="styles.css">
  <style>
    .historial-container { max-width: 1100px; margin: 40px auto; padding: 0 20px; }
    .historial-card { background: white; border-radius: 15px; padding: 30px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); }
    .titulo { color: #4f7d2f; font-weight: bold; margin-bottom: 8px; }
    .subtitulo { color: #666; margin-bottom: 25px; }
    .table thead { background-color: #4f7d2f; color: white; }
  </style>
</head>
<body>

<?php require __DIR__ . '/includes/header_simple.php'; ?>

<div class="historial-container">
  <a href="registro-reciclaje.php" class="btn btn-outline-secondary mb-3">← Volver a registrar reciclaje</a>

  <div class="historial-card">
    <h2 class="titulo">Historial de reciclaje</h2>
    <p class="subtitulo">Consulta los materiales que has registrado y los puntos obtenidos.</p>

    <div class="table-responsive">
      <table class="table table-bordered table-hover align-middle">
        <thead>
          <tr>
            <th>Material</th>
            <th>Kilos</th>
            <th>Centro de acopio</th>
            <th>Puntos</th>
            <th>Fecha</th>
          </tr>
        </thead>
        <tbody>
          <?php if (count($registros) > 0): ?>
            <?php foreach ($registros as $fila): ?>
              <tr>
                <td><?= htmlspecialchars($fila['material']) ?></td>
                <td><?= htmlspecialchars($fila['kilos']) ?> kg</td>
                <td><?= htmlspecialchars($fila['centro_acopio']) ?></td>
                <td><strong><?= htmlspecialchars($fila['puntos_ganados']) ?> pts</strong></td>
                <td><?= htmlspecialchars($fila['fecha_registro']) ?></td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr><td colspan="5" class="text-center">No hay registros de reciclaje todavía.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
