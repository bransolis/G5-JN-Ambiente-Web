<?php
require_once __DIR__ . '/includes/session_check.php';
requerirLogin();
$usuario = usuarioActual();

require_once __DIR__ . '/config/database.php';
$pdo = conectarDB();
$stmt = $pdo->query('SELECT id, nombre, direccion, distancia_km, materiales FROM centros_acopio ORDER BY distancia_km ASC LIMIT 4');
$centros = $stmt->fetchAll();
$hayMasCentros = count($centros) > 3;
if ($hayMasCentros) {
    array_pop($centros);
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>Centros de acopio – REUSE</title>

  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
    rel="stylesheet">

  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
    rel="stylesheet">

  <link rel="stylesheet" href="styles.css">
</head>

<body>

<?php require __DIR__ . '/includes/header_simple.php'; ?>


  <main class="acopio-contenido">

    <div class="perfil-ruta">

      <a href="panel.php">Inicio</a>

      <i class="bi bi-chevron-right"></i>

      <span>Centro de acopio</span>

    </div>

    <h1 class="acopio-titulo">
      Centros de acopio
    </h1>

    <p class="acopio-descripcion">
      Encuentra centros de reciclaje cerca de ti
    </p>

    <div class="acopio-buscador">
      <i class="bi bi-geo-alt-fill"></i>
      Buscar ubicacion

      <select id="filtroMaterial" class="form-select form-select-sm ms-auto" style="max-width: 200px;">
        <option value="Todos">Todos los materiales</option>
        <option value="Plastico">Plástico</option>
        <option value="Papel">Papel</option>
        <option value="Vidrio">Vidrio</option>
        <option value="Metal">Metal</option>
      </select>
    </div>

    <div class="acopio-layout">

      <div class="lista-centros" id="listaCentros">

        <?php foreach ($centros as $centro): ?>
        <article class="centro-tarjeta">
          <div class="centro-cabecera">
            <h3><?= htmlspecialchars($centro['nombre']) ?></h3>
            <span class="centro-distancia"><?= (float) $centro['distancia_km'] ?> km</span>
          </div>
          <p class="centro-direccion"><?= htmlspecialchars($centro['direccion']) ?></p>
          <p class="centro-acepta">Acepta: <span><?= htmlspecialchars(str_replace(',', ', ', $centro['materiales'])) ?></span></p>
        </article>
        <?php endforeach; ?>

        <button class="boton-ver-mas" id="botonVerMasCentros" data-offset="3" <?= $hayMasCentros ? '' : 'style="display:none;"' ?>>
          Ver mas centros
        </button>

      </div>

      <div class="mapa-acopio">
        <iframe
          src="https://www.openstreetmap.org/export/embed.html?bbox=-84.15%2C9.90%2C-83.95%2C10.05&layer=mapnik&marker=9.9981%2C-84.0455"
          loading="lazy"
          referrerpolicy="no-referrer-when-downgrade">
        </iframe>
      </div>

    </div>

  </main>


  <script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
  </script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="centro-acopio.js"></script>

</body>

</html>
