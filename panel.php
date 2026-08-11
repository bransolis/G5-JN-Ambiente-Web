<?php
require_once __DIR__ . '/includes/session_check.php';
requerirLogin();
$usuario = usuarioActual();
$paginaActiva = 'panel';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Panel Principal – REUSE</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet" />
  <link rel="stylesheet" href="styles.css" />
</head>
<body>

<?php require __DIR__ . '/includes/header_dashboard.php'; ?>

    <div class="d-flex justify-content-between align-items-start mb-4">
      <div>
        <h4 class="fw-bold mb-1">Hola, <?= htmlspecialchars($usuario['nombre']) ?>! 👋</h4>
        <p class="text-muted mb-0" style="font-size:.88rem">Gracias por ser parte del cambio.</p>
      </div>
      <div class="p-3 rounded-3 text-center" style="background:#fff;border:1px solid #e0e0d0;font-size:.78rem;max-width:200px">
        <span class="fw-bold" style="color:var(--green-dark)">💡 Tip:</span> Sigue así, cada acción cuenta para un mundo más limpio.
      </div>
    </div>

    <div class="row g-3 mb-4">
      <div class="col-6 col-md-3">
        <div class="stat-card text-center">
          <div class="stat-icon">⭐</div>
          <div class="stat-value" style="color:#e8a000"><?= number_format((int) $usuario['puntos']) ?></div>
          <div class="stat-label">Puntos acumulados</div>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="stat-card text-center">
          <div class="stat-icon">♻️</div>
          <div class="stat-value" style="color:var(--green-dark)"><?= number_format((float) $usuario['kg_reciclado'], 1) ?> kg</div>
          <div class="stat-label">Material reciclado total</div>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="stat-card text-center">
          <div class="stat-icon">🌿</div>
          <div class="stat-value" style="font-size:1.2rem"><?= htmlspecialchars($usuario['nivel_huella']) ?></div>
          <div class="stat-label">Tu huella de carbono</div>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="stat-card text-center">
          <div class="stat-icon">🎁</div>
          <div class="stat-value"><?= (int) floor($usuario['puntos'] / 500) ?></div>
          <div class="stat-label">Cupones disponibles</div>
        </div>
      </div>
    </div>

    <h6 class="fw-bold mb-3">Accesos Rápidos</h6>
    <div class="row g-3">
      <div class="col-6 col-md-4">
        <a href="registro-reciclaje.php" class="access-card">
          <div class="ac-icon">♻️</div>
          <h6>Registro Reciclaje</h6>
          <p>Registra el material que has reciclado.</p>
        </a>
      </div>
      <div class="col-6 col-md-4">
        <a href="huella-carbono.php" class="access-card">
          <div class="ac-icon">🌍</div>
          <h6>Calcula tu huella</h6>
          <p>Descubre tu impacto ambiental.</p>
        </a>
      </div>
      <div class="col-6 col-md-4">
        <a href="CentroAcopio.html" class="access-card">
          <div class="ac-icon">📍</div>
          <h6>Centros de acopio</h6>
          <p>Encuentra lugares de reciclaje cercanos.</p>
        </a>
      </div>
      <div class="col-6 col-md-4">
        <a href="voluntariados.html" class="access-card">
          <div class="ac-icon">🤝</div>
          <h6>Voluntariados</h6>
          <p>Únete a actividades ambientales.</p>
        </a>
      </div>
      <div class="col-6 col-md-4">
        <a href="noticias.html" class="access-card">
          <div class="ac-icon">📰</div>
          <h6>Noticias Ecológicas</h6>
          <p>Infórmate y aprende.</p>
        </a>
      </div>
      <div class="col-6 col-md-4">
        <a href="recompensas.php" class="access-card">
          <div class="ac-icon">🎁</div>
          <h6>Recompensas</h6>
          <p>Canjea tus puntos para obtener beneficios.</p>
        </a>
      </div>
    </div>

<?php require __DIR__ . '/includes/footer_dashboard.php'; ?>
