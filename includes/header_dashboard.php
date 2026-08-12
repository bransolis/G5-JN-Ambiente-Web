<?php
$paginaActiva = $paginaActiva ?? '';
function navActiva(string $clave, string $activa): string {
    return $clave === $activa ? 'active' : '';
}
?>
<nav class="navbar navbar-expand-lg navbar-dashboard sticky-top">
  <div class="container-fluid px-4">
    <a class="navbar-brand brand" href="panel.php">
      <img src="reuselogo.png" alt="REUSE" />
    </a>
    <div class="collapse navbar-collapse">
      <ul class="navbar-nav mx-auto gap-2">
        <li class="nav-item"><a class="nav-link" href="panel.php">Inicio</a></li>
        <li class="nav-item"><a class="nav-link" href="CentroAcopio.php">Centro de acopio</a></li>
        <li class="nav-item"><a class="nav-link" href="noticias.html">Noticias</a></li>
        <li class="nav-item"><a class="nav-link" href="voluntariados.html">Voluntariados</a></li>
        <li class="nav-item"><a class="nav-link" href="recompensas.php">Recompensas</a></li>
      </ul>
      <div class="d-flex align-items-center gap-3 ms-auto">
        <i class="bi bi-bell fs-5"></i>
        <i class="bi bi-chat-dots fs-5"></i>
        <div class="dropdown">
          <button class="btn btn-sm dropdown-toggle d-flex align-items-center gap-1" data-bs-toggle="dropdown"
            style="background:transparent;border:1px solid #ccc;border-radius:8px;font-size:.85rem">
            <i class="bi bi-person-circle"></i> @<?= htmlspecialchars($usuario['username']) ?>
          </button>
          <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="perfil.php">Mi perfil</a></li>
            <li><a class="dropdown-item" href="configuracion.php">Configuración</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="api/logout.php">Cerrar sesión</a></li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</nav>

<div class="dashboard-wrapper">

  <aside class="sidebar">
    <nav class="nav flex-column">
      <a class="nav-link <?= navActiva('panel', $paginaActiva) ?>" href="panel.php"><i class="bi bi-grid-fill"></i> Panel Principal</a>
      <a class="nav-link <?= navActiva('reciclaje', $paginaActiva) ?>" href="registro-reciclaje.php"><i class="bi bi-recycle"></i> Registrar reciclaje</a>
      <a class="nav-link <?= navActiva('huella', $paginaActiva) ?>" href="huella-carbono.php"><i class="bi bi-cloud"></i> Huella de carbono</a>
      <a class="nav-link <?= navActiva('acopio', $paginaActiva) ?>" href="CentroAcopio.php"><i class="bi bi-geo-alt"></i> Centros de acopio</a>
      <a class="nav-link <?= navActiva('noticias', $paginaActiva) ?>" href="noticias.html"><i class="bi bi-newspaper"></i> Noticias</a>
      <a class="nav-link <?= navActiva('voluntariados', $paginaActiva) ?>" href="voluntariados.html"><i class="bi bi-people"></i> Voluntariados</a>
      <a class="nav-link <?= navActiva('recompensas', $paginaActiva) ?>" href="recompensas.php"><i class="bi bi-gift"></i> Recompensas</a>
      <hr class="mx-3" />
      <a class="nav-link <?= navActiva('perfil', $paginaActiva) ?>" href="perfil.php"><i class="bi bi-person"></i> Mi perfil</a>
      <a class="nav-link <?= navActiva('configuracion', $paginaActiva) ?>" href="configuracion.php"><i class="bi bi-gear"></i> Configuración</a>
      <a class="nav-link text-danger" href="api/logout.php"><i class="bi bi-box-arrow-left"></i> Cerrar sesión</a>
    </nav>
  </aside>

  <main class="main-content">
