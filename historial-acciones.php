<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/session_check.php';
requerirLogin();
$usuario = usuarioActual();
$paginaActiva = 'perfil';

$pdo = conectarDB();

$stmt = $pdo->prepare(
    'SELECT material, kilos, centro_acopio, puntos_ganados, fecha_registro, creado_en
     FROM registro_reciclaje
     WHERE usuario_id = :id'
);
$stmt->execute(['id' => $usuario['id']]);
$acciones = [];
foreach ($stmt->fetchAll() as $fila) {
    $acciones[] = [
        'tipo'    => 'reciclaje',
        'icono'   => 'bi-recycle',
        'titulo'  => 'Registraste ' . htmlspecialchars($fila['kilos']) . ' kg de ' . htmlspecialchars($fila['material']),
        'detalle' => 'Centro de acopio: ' . htmlspecialchars($fila['centro_acopio']),
        'puntos'  => (int) $fila['puntos_ganados'],
        'fecha'   => $fila['creado_en'] ?: $fila['fecha_registro'],
    ];
}

$stmt = $pdo->prepare(
    'SELECT pais, provincia, tipo_vivienda, nivel_huella, fecha_calculo, creado_en
     FROM huella_calculos
     WHERE usuario_id = :id'
);
$stmt->execute(['id' => $usuario['id']]);
foreach ($stmt->fetchAll() as $fila) {
    $acciones[] = [
        'tipo'    => 'huella',
        'icono'   => 'bi-feather',
        'titulo'  => 'Calculaste tu huella de carbono: ' . htmlspecialchars($fila['nivel_huella']),
        'detalle' => htmlspecialchars($fila['provincia']) . ', ' . htmlspecialchars($fila['pais'])
                     . ' · Vivienda: ' . htmlspecialchars($fila['tipo_vivienda']),
        'puntos'  => null,
        'fecha'   => $fila['creado_en'] ?: $fila['fecha_calculo'],
    ];
}

$stmt = $pdo->prepare(
    'SELECT recompensa, puntos_usados, codigo_cupon, fecha_canje
     FROM historial_canjes
     WHERE usuario_id = :id'
);
$stmt->execute(['id' => $usuario['id']]);
foreach ($stmt->fetchAll() as $fila) {
    $acciones[] = [
        'tipo'    => 'canje',
        'icono'   => 'bi-ticket-perforated-fill',
        'titulo'  => 'Canjeaste un cupón de ' . htmlspecialchars($fila['recompensa']),
        'detalle' => 'Código: ' . htmlspecialchars($fila['codigo_cupon']),
        'puntos'  => -1 * (int) $fila['puntos_usados'],
        'fecha'   => $fila['fecha_canje'],
    ];
}

usort($acciones, function ($a, $b) {
    return strtotime($b['fecha']) <=> strtotime($a['fecha']);
});
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Historial de acciones – REUSE</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="styles.css">
  <style>
    .historial-container { max-width: 1100px; margin: 40px auto; padding: 0 20px; }
    .historial-card { background: white; border-radius: 15px; padding: 30px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); }
    .titulo { color: #4f7d2f; font-weight: bold; margin-bottom: 8px; }
    .subtitulo { color: #666; margin-bottom: 25px; }

    .accion-item {
      display: flex;
      align-items: flex-start;
      gap: 16px;
      padding: 16px 0;
      border-bottom: 1px solid #eee;
    }
    .accion-item:last-child { border-bottom: none; }
    .accion-icono {
      width: 44px;
      height: 44px;
      min-width: 44px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      background-color: #e9f3e2;
      color: #4f7d2f;
      font-size: 20px;
    }
    .accion-icono.canje { background-color: #fdeecb; color: #b98600; }
    .accion-icono.huella { background-color: #e4f1f7; color: #2f7d9f; }
    .accion-texto h4 { font-size: 16px; margin-bottom: 4px; font-weight: 600; }
    .accion-texto p { font-size: 14px; color: #777; margin-bottom: 2px; }
    .accion-fecha { font-size: 13px; color: #999; }
    .accion-puntos {
      margin-left: auto;
      font-weight: bold;
      white-space: nowrap;
      padding-left: 12px;
    }
    .accion-puntos.positivo { color: #4f7d2f; }
    .accion-puntos.negativo { color: #b04a4a; }
  </style>
</head>
<body>

<?php require __DIR__ . '/includes/header_simple.php'; ?>

<div class="historial-container">
  <a href="perfil.php" class="btn btn-outline-secondary mb-3">← Volver a mi perfil</a>

  <div class="historial-card">
    <h2 class="titulo">Historial de acciones</h2>
    <p class="subtitulo">Todo lo que has hecho en REUSE: reciclaje registrado, cálculos de huella de carbono y cupones canjeados.</p>

    <?php if (count($acciones) > 0): ?>
      <?php foreach ($acciones as $accion): ?>
        <div class="accion-item">
          <div class="accion-icono <?= $accion['tipo'] === 'canje' ? 'canje' : ($accion['tipo'] === 'huella' ? 'huella' : '') ?>">
            <i class="bi <?= $accion['icono'] ?>"></i>
          </div>
          <div class="accion-texto">
            <h4><?= $accion['titulo'] ?></h4>
            <p><?= $accion['detalle'] ?></p>
            <span class="accion-fecha"><?= htmlspecialchars(date('d/m/Y', strtotime($accion['fecha']))) ?></span>
          </div>
          <?php if ($accion['puntos'] !== null): ?>
            <div class="accion-puntos <?= $accion['puntos'] >= 0 ? 'positivo' : 'negativo' ?>">
              <?= $accion['puntos'] >= 0 ? '+' : '' ?><?= number_format($accion['puntos']) ?> pts
            </div>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <p class="text-center text-muted mb-0">Todavía no tienes acciones registradas.</p>
    <?php endif; ?>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
