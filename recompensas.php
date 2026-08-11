<?php
require_once __DIR__ . '/includes/session_check.php';
requerirLogin();
$usuario = usuarioActual();
$paginaActiva = 'recompensas';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Recompensas – REUSE</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="styles.css">
</head>
<body>

<?php require __DIR__ . '/includes/header_simple.php'; ?>

<main class="recompensas-contenido">
  <div class="perfil-ruta">
    <a href="panel.php">Inicio</a>
    <i class="bi bi-chevron-right"></i>
    <span>Recompensas</span>
  </div>

  <h1 class="recompensas-titulo">Recompensas</h1>
  <p class="recompensas-descripcion">
    Canjea tus puntos por increíbles beneficios. Tenés
    <strong id="puntosDisponibles"><?= number_format((int) $usuario['puntos']) ?></strong> puntos disponibles.
  </p>

  <div class="alert alert-success d-none" id="canjeExito"></div>
  <div class="alert alert-danger d-none" id="canjeError"></div>

  <section class="lista-recompensas">
    <article class="recompensa-tarjeta">
      <div class="recompensa-imagen">
        <img src="Image Jul 9, 2026, 10_44_58 AM.png" alt="Logo de Walmart">
      </div>
      <div class="recompensa-info">
        <h3>Cupón ₡7000 en Walmart</h3>
        <p><i class="bi bi-plus-circle-fill"></i> 500 puntos</p>
      </div>
      <button id="walmart" class="boton-canjear">Canjear</button>
    </article>

    <article class="recompensa-tarjeta">
      <div class="recompensa-imagen">
        <img src="ekono..png" alt="Logo de ekono">
      </div>
      <div class="recompensa-info">
        <h3>Cupón ₡5000 en ekono</h3>
        <p><i class="bi bi-plus-circle-fill"></i> 350 puntos</p>
      </div>
      <button id="ekono" class="boton-canjear">Canjear</button>
    </article>

    <article class="recompensa-tarjeta">
      <div class="recompensa-imagen">
        <img src="McDonald's.png" alt="Logo de McDonald's">
      </div>
      <div class="recompensa-info">
        <h3>Cupón ₡10 000 en McDonald's</h3>
        <p><i class="bi bi-plus-circle-fill"></i> 2000 puntos</p>
      </div>
      <button id="mcdonalds" class="boton-canjear">Canjear</button>
    </article>
  </section>

  <button class="boton-ver-mas">Ver más recompensas</button>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="botones-canjear.js"></script>
</body>
</html>
