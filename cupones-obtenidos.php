<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/session_check.php';
requerirLogin();
$usuario = usuarioActual();
$paginaActiva = 'perfil';

$pdo = conectarDB();
$stmt = $pdo->prepare(
    'SELECT recompensa, puntos_usados, codigo_cupon, fecha_canje
     FROM historial_canjes
     WHERE usuario_id = :id
     ORDER BY fecha_canje DESC, id DESC'
);
$stmt->execute(['id' => $usuario['id']]);
$cupones = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cupones obtenidos – REUSE</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="styles.css">
  <style>
    .historial-container { max-width: 1100px; margin: 40px auto; padding: 0 20px; }
    .historial-card { background: white; border-radius: 15px; padding: 30px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); }
    .titulo { color: #4f7d2f; font-weight: bold; margin-bottom: 8px; }
    .subtitulo { color: #666; margin-bottom: 25px; }

    .cupon-item {
      display: flex;
      align-items: center;
      gap: 16px;
      padding: 18px 0;
      border-bottom: 1px dashed #ddd;
    }
    .cupon-item:last-child { border-bottom: none; }
    .cupon-icono {
      width: 48px;
      height: 48px;
      min-width: 48px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      background-color: #fdeecb;
      color: #b98600;
      font-size: 22px;
    }
    .cupon-info h4 { font-size: 16px; margin-bottom: 4px; font-weight: 600; }
    .cupon-codigo {
      font-family: monospace;
      background: #f4f4f4;
      padding: 2px 8px;
      border-radius: 6px;
      color: #4f7d2f;
      font-weight: bold;
    }
    .cupon-fecha { font-size: 13px; color: #999; margin-top: 2px; }
    .cupon-puntos {
      margin-left: auto;
      font-weight: bold;
      color: #b04a4a;
      white-space: nowrap;
    }
  </style>
</head>
<body>

<?php require __DIR__ . '/includes/header_simple.php'; ?>

<div class="historial-container">
  <a href="perfil.php" class="btn btn-outline-secondary mb-3">← Volver a mi perfil</a>

  <div class="historial-card">
    <h2 class="titulo">Cupones obtenidos</h2>
    <p class="subtitulo">Estos son los cupones que has canjeado con tus puntos.</p>

    <?php if (count($cupones) > 0): ?>
      <?php foreach ($cupones as $cupon): ?>
        <div class="cupon-item">
          <div class="cupon-icono"><i class="bi bi-ticket-perforated-fill"></i></div>
          <div class="cupon-info">
            <h4><?= htmlspecialchars($cupon['recompensa']) ?></h4>
            <div>Código: <span class="cupon-codigo"><?= htmlspecialchars($cupon['codigo_cupon']) ?></span></div>
            <div class="cupon-fecha"><?= htmlspecialchars(date('d/m/Y', strtotime($cupon['fecha_canje']))) ?></div>
          </div>
          <div class="cupon-puntos">-<?= number_format((int) $cupon['puntos_usados']) ?> pts</div>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <p class="text-center text-muted mb-0">
        Todavía no has canjeado ningún cupón. <a href="recompensas.php">Ver recompensas disponibles</a>.
      </p>
    <?php endif; ?>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
