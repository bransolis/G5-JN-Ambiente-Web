<?php

?>
<nav class="navbar navbar-expand-lg navbar-dashboard sticky-top">
  <div class="container-fluid px-4">
    <a class="navbar-brand brand" href="panel.php">
      <img src="reuselogo.png" alt="REUSE">
    </a>
    <div class="collapse navbar-collapse">
      <ul class="navbar-nav mx-auto gap-2">
        <li class="nav-item"><a class="nav-link" href="panel.php">Inicio</a></li>
        <li class="nav-item"><a class="nav-link" href="CentroAcopio.html">Centro de acopio</a></li>
        <li class="nav-item"><a class="nav-link" href="noticias.html">Noticias</a></li>
        <li class="nav-item"><a class="nav-link" href="voluntariados.html">Voluntariados</a></li>
        <li class="nav-item"><a class="nav-link" href="recompensas.php">Recompensas</a></li>
      </ul>
      <div class="d-flex align-items-center gap-3 ms-auto">
        <i class="bi bi-bell fs-5"></i>
        <i class="bi bi-box-arrow-right fs-5"></i>
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
