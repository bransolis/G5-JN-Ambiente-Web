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
  <title>Editar Perfil – REUSE</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="styles.css">
</head>
<body>

<?php require __DIR__ . '/includes/header_simple.php'; ?>

<main class="editar-contenido">

  <div class="editar-ruta">
    <a href="panel.php">Inicio</a>
    <i class="bi bi-chevron-right"></i>
    <a href="perfil.php">Mi perfil</a>
    <i class="bi bi-chevron-right"></i>
    <span>Editar perfil</span>
  </div>

  <div class="editar-encabezado">
    <div>
      <h1>Editar perfil</h1>
      <p>Actualiza la información de tu cuenta.</p>
    </div>
  </div>

  <section class="editar-tarjeta">
    <div class="editar-perfil-superior">
      <img src="<?= htmlspecialchars($usuario['foto_perfil']) ?>" alt="Foto de perfil" class="editar-foto-perfil">
      <div class="editar-identidad">
        <h2 id="identidadNombre"><?= htmlspecialchars($usuario['nombre'] . ' ' . $usuario['apellidos']) ?></h2>
        <p id="identidadCorreo"><?= htmlspecialchars($usuario['email']) ?></p>
        <span class="editar-estado">
          <i class="bi bi-check-circle-fill"></i>
          <?= $usuario['activo'] ? 'Usuario activo' : 'Usuario inactivo' ?>
        </span>
      </div>
    </div>

    <div class="alert alert-success d-none" id="editarExito"></div>
    <div class="alert alert-danger d-none" id="editarError"></div>

    <form class="editar-formulario" id="formEditarPerfil">
      <div class="editar-titulo-formulario">
        <h3>Datos personales</h3>
        <p>Puedes modificar la información de tu perfil.</p>
      </div>

      <div class="row g-4">
        <div class="col-md-6">
          <label for="nombre" class="form-label">Nombre</label>
          <input type="text" class="form-control editar-input" id="nombre" name="nombre"
                 value="<?= htmlspecialchars($usuario['nombre']) ?>" required>
        </div>

        <div class="col-md-6">
          <label for="apellidos" class="form-label">Apellidos</label>
          <input type="text" class="form-control editar-input" id="apellidos" name="apellidos"
                 value="<?= htmlspecialchars($usuario['apellidos']) ?>" required>
        </div>

        <div class="col-md-6">
          <label for="correo" class="form-label">Correo electrónico</label>
          <input type="email" class="form-control editar-input" id="correo" name="email"
                 value="<?= htmlspecialchars($usuario['email']) ?>" required>
        </div>

        <div class="col-md-6">
          <label for="usuario" class="form-label">Nombre de usuario</label>
          <input type="text" class="form-control editar-input" id="usuario" name="username"
                 value="<?= htmlspecialchars($usuario['username']) ?>" required>
        </div>
      </div>

      <div class="editar-botones">
        <a href="perfil.php" class="boton-cancelar">Cancelar</a>
        <button type="submit" class="boton-guardar" id="btnGuardarPerfil">Guardar cambios</button>
      </div>
    </form>
  </section>

</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  const formulario = document.getElementById('formEditarPerfil');
  const exito = document.getElementById('editarExito');
  const error = document.getElementById('editarError');
  const btn = document.getElementById('btnGuardarPerfil');

  formulario.addEventListener('submit', async function (evento) {
    evento.preventDefault();
    exito.classList.add('d-none');
    error.classList.add('d-none');

    btn.disabled = true;
    btn.textContent = 'Guardando...';

    try {
      const data = new FormData(formulario);
      const res = await fetch('api/actualizar_perfil.php', { method: 'POST', body: data });
      const json = await res.json();

      if (json.ok) {
        document.getElementById('identidadNombre').textContent =
          data.get('nombre') + ' ' + data.get('apellidos');
        document.getElementById('identidadCorreo').textContent = data.get('email');

        exito.textContent = json.mensaje;
        exito.classList.remove('d-none');
      } else {
        error.textContent = json.error || 'No se pudieron guardar los cambios.';
        error.classList.remove('d-none');
      }
    } catch (err) {
      error.textContent = 'Error de conexión con el servidor.';
      error.classList.remove('d-none');
    } finally {
      btn.disabled = false;
      btn.textContent = 'Guardar cambios';
    }
  });
</script>
</body>
</html>
