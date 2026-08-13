<?php
require_once __DIR__ . '/includes/session_check.php';
requerirLogin();
$usuario = usuarioActual();
$paginaActiva = 'perfil';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mi Perfil – REUSE</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="styles.css">
</head>
<body>

<?php require __DIR__ . '/includes/header_simple.php'; ?>

<main class="perfil-contenido">

  <div class="perfil-ruta">
    <a href="panel.php">Inicio</a>
    <i class="bi bi-chevron-right"></i>
    <span>Mi perfil</span>
  </div>

  <h1 class="perfil-titulo">Mi perfil</h1>
  <p class="perfil-descripcion">Administra tu información y consulta tu actividad.</p>

  <section class="perfil-tarjeta">
    <div class="perfil-datos">
      <div class="perfil-foto">
        <img src="<?= htmlspecialchars($usuario['foto_perfil']) ?>" alt="Foto de perfil">
      </div>
      <div>
        <h3><?= htmlspecialchars($usuario['nombre'] . ' ' . $usuario['apellidos']) ?></h3>
        <p><?= htmlspecialchars($usuario['email']) ?></p>
        <div class="perfil-activo">
          <i class="bi bi-check-circle-fill"></i>
          <span><?= $usuario['activo'] ? 'Usuario activo' : 'Usuario inactivo' ?></span>
        </div>
      </div>
    </div>

    <a href="editar-perfil.php" class="boton-editar">Editar perfil</a>
  </section>

  <section class="perfil-estadisticas">
    <div class="perfil-estadistica">
      <div class="estadistica-icono estrella"><i class="bi bi-star-fill"></i></div>
      <div>
        <h3><?= number_format((int) $usuario['puntos']) ?></h3>
        <p>Puntos acumulados</p>
      </div>
    </div>

    <div class="perfil-estadistica">
      <div class="estadistica-icono"><i class="bi bi-recycle"></i></div>
      <div>
        <h3><?= number_format((float) $usuario['kg_reciclado'], 1) ?> kg</h3>
        <p>Material reciclado</p>
      </div>
    </div>

    <div class="perfil-estadistica">
      <div class="estadistica-icono"><i class="bi bi-feather"></i></div>
      <div>
        <h3><?= htmlspecialchars($usuario['nivel_huella']) ?></h3>
        <p>Huella de carbono</p>
      </div>
    </div>
  </section>

  <section class="perfil-opciones">
    <a href="historial-acciones.php" class="perfil-opcion">
      <div><i class="bi bi-file-earmark-text"></i><span>Historial de acciones</span></div>
      <i class="bi bi-chevron-right"></i>
    </a>

    <a href="cupones-obtenidos.php" class="perfil-opcion">
      <div><i class="bi bi-ticket-perforated-fill"></i><span>Cupones obtenidos</span></div>
      <i class="bi bi-chevron-right"></i>
    </a>

    <a href="configuracion.php" class="perfil-opcion">
      <div><i class="bi bi-gear-fill"></i><span>Configuración</span></div>
      <i class="bi bi-chevron-right"></i>
    </a>

    <a href="api/logout.php" class="perfil-opcion">
      <div><i class="bi bi-box-arrow-left"></i><span>Cerrar sesión</span></div>
      <i class="bi bi-chevron-right"></i>
    </a>
  </section>


</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
