<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/session_check.php';
requerirLogin();
$usuario = usuarioActual();
$paginaActiva = 'configuracion';

$pdo = conectarDB();
$stmt = $pdo->prepare('SELECT * FROM configuracion_usuario WHERE usuario_id = :id');
$stmt->execute(['id' => $usuario['id']]);
$config = $stmt->fetch();
if (!$config) {

    $pdo->prepare('INSERT INTO configuracion_usuario (usuario_id) VALUES (:id)')->execute(['id' => $usuario['id']]);
    $stmt->execute(['id' => $usuario['id']]);
    $config = $stmt->fetch();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Configuración – REUSE</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="styles.css">
  <style>
    .config-seccion { padding: 30px 32px; border-bottom: 1px solid #e7e7df; }
    .config-seccion:last-child { border-bottom: none; }
    .config-seccion h3 { font-size: 1.1rem; font-weight: 700; color: #183d2b; margin-bottom: 4px; }
    .config-seccion > p { color: #666; font-size: .88rem; margin-bottom: 20px; }
    .config-toggle-row { display: flex; justify-content: space-between; align-items: center; padding: 12px 0; border-bottom: 1px solid #f1f1e9; }
    .config-toggle-row:last-child { border-bottom: none; }
    .config-toggle-row strong { display: block; font-size: .92rem; color: #222; }
    .config-toggle-row span { font-size: .8rem; color: #777; }
    .form-check-input:checked { background-color: var(--green-dark); border-color: var(--green-dark); }
    .config-zona-peligro { background: #fff5f5; border: 1px solid #f3caca; border-radius: 10px; padding: 18px 20px; }
  </style>
</head>
<body>

<?php require __DIR__ . '/includes/header_simple.php'; ?>

<main class="editar-contenido">

  <div class="editar-ruta">
    <a href="panel.php">Inicio</a>
    <i class="bi bi-chevron-right"></i>
    <a href="perfil.php">Mi perfil</a>
    <i class="bi bi-chevron-right"></i>
    <span>Configuración</span>
  </div>

  <div class="editar-encabezado">
    <div>
      <h1>Configuración</h1>
      <p>Administra tu contraseña, notificaciones y privacidad.</p>
    </div>
  </div>


  <section class="editar-tarjeta mb-4">
    <div class="config-seccion">
      <h3><i class="bi bi-shield-lock"></i> Contraseña</h3>
      <p>Usa una contraseña de al menos 8 caracteres que no uses en otros sitios.</p>

      <div class="alert alert-success d-none" id="passExito"></div>
      <div class="alert alert-danger d-none" id="passError"></div>

      <form id="formPassword">
        <div class="row g-3">
          <div class="col-md-4">
            <label class="form-label">Contraseña actual</label>
            <input type="password" class="form-control editar-input" name="password_actual" required>
          </div>
          <div class="col-md-4">
            <label class="form-label">Nueva contraseña</label>
            <input type="password" class="form-control editar-input" name="password_nueva" minlength="8" required>
          </div>
          <div class="col-md-4">
            <label class="form-label">Confirmar nueva contraseña</label>
            <input type="password" class="form-control editar-input" name="password_confirmacion" minlength="8" required>
          </div>
        </div>
        <div class="editar-botones mt-4">
          <button type="submit" class="boton-guardar" id="btnPassword">Actualizar contraseña</button>
        </div>
      </form>
    </div>
  </section>

  <section class="editar-tarjeta mb-4">
    <div class="config-seccion">
      <h3><i class="bi bi-bell"></i> Notificaciones y privacidad</h3>
      <p>Elige cómo querés que REUSE se comunique contigo.</p>

      <div class="alert alert-success d-none" id="prefExito"></div>

      <form id="formPreferencias">
        <div class="config-toggle-row">
          <div>
            <strong>Notificaciones por correo</strong>
            <span>Recordatorios, novedades y recompensas disponibles.</span>
          </div>
          <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" role="switch" name="notificaciones_email"
                   <?= $config['notificaciones_email'] ? 'checked' : '' ?>>
          </div>
        </div>

        <div class="config-toggle-row">
          <div>
            <strong>Notificaciones push</strong>
            <span>Avisos dentro de la aplicación.</span>
          </div>
          <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" role="switch" name="notificaciones_push"
                   <?= $config['notificaciones_push'] ? 'checked' : '' ?>>
          </div>
        </div>

        <div class="config-toggle-row">
          <div>
            <strong>Perfil público</strong>
            <span>Otros usuarios pueden ver tu progreso en el ranking.</span>
          </div>
          <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" role="switch" name="perfil_publico"
                   <?= $config['perfil_publico'] ? 'checked' : '' ?>>
          </div>
        </div>

        <div class="row g-3 mt-1">
          <div class="col-md-4">
            <label class="form-label">Idioma</label>
            <select class="form-select editar-input" name="idioma">
              <option value="es" <?= $config['idioma'] === 'es' ? 'selected' : '' ?>>Español</option>
              <option value="en" <?= $config['idioma'] === 'en' ? 'selected' : '' ?>>English</option>
            </select>
          </div>
        </div>

        <div class="editar-botones mt-4">
          <button type="submit" class="boton-guardar" id="btnPreferencias">Guardar preferencias</button>
        </div>
      </form>
    </div>
  </section>

  <section class="editar-tarjeta">
    <div class="config-seccion">
      <h3 class="text-danger"><i class="bi bi-exclamation-triangle"></i> Zona de cuenta</h3>
      <div class="config-zona-peligro d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
          <strong>Cerrar sesión en este dispositivo</strong>
          <p class="mb-0" style="font-size:.85rem;color:#777">Tendrás que volver a iniciar sesión.</p>
        </div>
        <a href="api/logout.php" class="btn btn-outline-danger btn-sm">Cerrar sesión</a>
      </div>
    </div>
  </section>

</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>

  const formPass = document.getElementById('formPassword');
  const passExito = document.getElementById('passExito');
  const passError = document.getElementById('passError');

  formPass.addEventListener('submit', async function (e) {
    e.preventDefault();
    passExito.classList.add('d-none');
    passError.classList.add('d-none');

    const btn = document.getElementById('btnPassword');
    btn.disabled = true;
    btn.textContent = 'Guardando...';

    try {
      const data = new FormData(formPass);
      const res = await fetch('api/cambiar_password.php', { method: 'POST', body: data });
      const json = await res.json();

      if (json.ok) {
        passExito.textContent = json.mensaje;
        passExito.classList.remove('d-none');
        formPass.reset();
      } else {
        passError.textContent = json.error || 'No se pudo actualizar la contraseña.';
        passError.classList.remove('d-none');
      }
    } catch (err) {
      passError.textContent = 'Error de conexión con el servidor.';
      passError.classList.remove('d-none');
    } finally {
      btn.disabled = false;
      btn.textContent = 'Actualizar contraseña';
    }
  });


  const formPref = document.getElementById('formPreferencias');
  const prefExito = document.getElementById('prefExito');

  formPref.addEventListener('submit', async function (e) {
    e.preventDefault();
    prefExito.classList.add('d-none');

    const btn = document.getElementById('btnPreferencias');
    btn.disabled = true;
    btn.textContent = 'Guardando...';

    try {
      const data = new FormData(formPref);
      const res = await fetch('api/actualizar_configuracion.php', { method: 'POST', body: data });
      const json = await res.json();

      if (json.ok) {
        prefExito.textContent = json.mensaje;
        prefExito.classList.remove('d-none');
      }
    } catch (err) {

    } finally {
      btn.disabled = false;
      btn.textContent = 'Guardar preferencias';
    }
  });
</script>
</body>
</html>
