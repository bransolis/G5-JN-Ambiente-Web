<?php
require_once __DIR__ . '/includes/session_check.php';

if (usuarioAutenticado()) {
    header('Location: panel.php');
    exit;
}

$abrirLogin = isset($_GET['auth']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>REUSE – Recicla, aprende y gana recompensas</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet" />
  <link rel="stylesheet" href="styles.css" />
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-reuse sticky-top">
  <div class="container-fluid px-4">
    <a class="navbar-brand" href="index.php">
      <img src="reuselogo.png" alt="REUSE" />
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navMain">
      <ul class="navbar-nav mx-auto gap-2">
        <li class="nav-item"><a class="nav-link" href="#">Inicio</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Nosotros</a></li>
        <li class="nav-item"><a class="nav-link" href="CentroAcopio.html">Centro de acopio</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Noticias</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Voluntariados</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Contacto</a></li>
      </ul>
      <div class="d-flex gap-2 ms-auto">
        <button class="btn-login" data-bs-toggle="modal" data-bs-target="#modalLogin">Iniciar Sesión</button>
        <button class="btn-register" data-bs-toggle="modal" data-bs-target="#modalRegistro">Registrarse</button>
      </div>
    </div>
  </div>
</nav>

<section class="hero-section">
  <div class="container-fluid px-5">
    <div class="row align-items-center">
      <div class="col-lg-6">
        <h1>Recicla, aprende y<br>gana recompensas<br>mientras cuidas<br><span>nuestro planeta.</span></h1>
        <p class="mt-3">Únete a Reuse y sé parte del cambio<br>en Costa Rica</p>
        <div class="d-flex gap-3 flex-wrap">
          <button class="btn-cta" data-bs-toggle="modal" data-bs-target="#modalLogin">Comenzar ahora</button>
          <button class="btn-outline-cta">Conocer más</button>
        </div>
      </div>
      <div class="col-lg-6 text-center mt-4 mt-lg-0">
        <img src="corazonreciclaje.png" alt="Recicla el planeta" class="img-fluid" style="max-height:360px;" />
      </div>
    </div>
  </div>
</section>

<section class="py-4 px-5">
  <div class="container-fluid">
    <div class="row g-3">
      <div class="col-md-4">
        <div class="feature-card">
          <div class="icon-wrap">♻️</div>
          <h6>Recicla y gana puntos</h6>
          <p>Registra tus acciones de reciclaje y acumula puntos.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="feature-card">
          <div class="icon-wrap">🌿</div>
          <h6>Calcula tu huella</h6>
          <p>Conoce tu impacto ambiental y aprende a mejorarlo.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="feature-card">
          <div class="icon-wrap">🎁</div>
          <h6>Canjea recompensas</h6>
          <p>Usa tus puntos para obtener cupones y beneficios.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<div class="modal fade modal-reuse" id="modalLogin" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered" style="max-width:420px">
    <div class="modal-content p-2">
      <div class="modal-header">
        <h5 class="modal-title">Iniciar Sesión</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="alert alert-danger d-none" id="loginError" role="alert"></div>
        <form id="formLogin" novalidate>
          <div class="mb-3">
            <label class="form-label">Correo electrónico</label>
            <input type="email" class="form-control" name="email" id="emailLogin" placeholder="correo@ejemplo.com" required />
          </div>
          <div class="mb-2">
            <label class="form-label">Contraseña</label>
            <div class="input-group">
              <input type="password" class="form-control" name="password" id="passLogin" placeholder="••••••••••" required />
              <button type="button" class="btn btn-outline-secondary" onclick="togglePass('passLogin')">
                <i class="bi bi-eye"></i>
              </button>
            </div>
          </div>
          <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" id="remember" />
              <label class="form-check-label" style="font-size:.83rem" for="remember">Recordarme</label>
            </div>
            <a href="#" style="font-size:.83rem;color:var(--green-dark)">¿Olvidaste tu contraseña?</a>
          </div>
          <button type="submit" class="btn btn-green-full mb-2 d-block w-100" id="btnLoginSubmit">Iniciar sesión</button>
          <div class="divider-text">O continua con</div>
          <button type="button" class="btn-social" disabled title="Próximamente">
            <img src="https://www.google.com/favicon.ico" width="16" /> Continuar con Google
          </button>
          <button type="button" class="btn-social" style="color:#1877F2" disabled title="Próximamente">
            <i class="bi bi-facebook"></i> Continuar con Facebook
          </button>
          <p class="text-center mt-2" style="font-size:.83rem">
            ¿No tienes cuenta?
            <a href="#" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#modalRegistro"
               style="color:var(--green-dark);font-weight:700">Regístrate aquí</a>
          </p>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="modal fade modal-reuse" id="modalRegistro" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered" style="max-width:420px">
    <div class="modal-content p-2">
      <div class="modal-header">
        <h5 class="modal-title">Registrarse</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="alert alert-danger d-none" id="registroError" role="alert"></div>
        <form id="formRegistro" novalidate>
          <div class="mb-3">
            <label class="form-label">Nombre</label>
            <input type="text" class="form-control" name="nombre" placeholder="Tu nombre" required />
          </div>
          <div class="mb-3">
            <label class="form-label">Apellidos</label>
            <input type="text" class="form-control" name="apellidos" placeholder="Tus apellidos" required />
          </div>
          <div class="mb-3">
            <label class="form-label">Correo electrónico</label>
            <input type="email" class="form-control" name="email" placeholder="correo@ejemplo.com" required />
          </div>
          <div class="mb-3">
            <label class="form-label">Contraseña</label>
            <div class="input-group">
              <input type="password" class="form-control" name="password" id="passReg" placeholder="Mínimo 8 caracteres" minlength="8" required />
              <button type="button" class="btn btn-outline-secondary" onclick="togglePass('passReg')">
                <i class="bi bi-eye"></i>
              </button>
            </div>
          </div>
          <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" id="terms" name="terminos" required />
            <label class="form-check-label" style="font-size:.83rem" for="terms">
              Acepto los <a href="#" style="color:var(--green-dark)">Términos de servicio</a>
              y la <a href="#" style="color:var(--green-dark)">Política de privacidad</a>
            </label>
          </div>
          <button type="submit" class="btn-green-full mb-2 w-100" id="btnRegistroSubmit">Crear Cuenta</button>
          <p class="text-center mt-2" style="font-size:.83rem">
            ¿Ya tienes cuenta?
            <a href="#" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#modalLogin"
               style="color:var(--green-dark);font-weight:700">Inicia Sesión aquí</a>
          </p>
        </form>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  function togglePass(id) {
    const inp = document.getElementById(id);
    inp.type = inp.type === 'password' ? 'text' : 'password';
  }

  function mostrarError(elId, mensaje) {
    const el = document.getElementById(elId);
    el.textContent = mensaje;
    el.classList.remove('d-none');
  }

  function ocultarError(elId) {
    document.getElementById(elId).classList.add('d-none');
  }

  document.getElementById('formLogin').addEventListener('submit', async function (e) {
    e.preventDefault();
    ocultarError('loginError');

    const btn = document.getElementById('btnLoginSubmit');
    btn.disabled = true;
    btn.textContent = 'Ingresando...';

    try {
      const data = new FormData(this);
      const res = await fetch('api/login.php', { method: 'POST', body: data });
      const json = await res.json();

      if (json.ok) {
        window.location.href = json.redirect;
      } else {
        mostrarError('loginError', json.error || 'No se pudo iniciar sesión.');
      }
    } catch (err) {
      mostrarError('loginError', 'Error de conexión con el servidor.');
    } finally {
      btn.disabled = false;
      btn.textContent = 'Iniciar sesión';
    }
  });

  document.getElementById('formRegistro').addEventListener('submit', async function (e) {
    e.preventDefault();
    ocultarError('registroError');

    const btn = document.getElementById('btnRegistroSubmit');
    btn.disabled = true;
    btn.textContent = 'Creando cuenta...';

    try {
      const data = new FormData(this);
      const res = await fetch('api/registro.php', { method: 'POST', body: data });
      const json = await res.json();

      if (json.ok) {
        window.location.href = json.redirect;
      } else {
        mostrarError('registroError', json.error || 'No se pudo crear la cuenta.');
      }
    } catch (err) {
      mostrarError('registroError', 'Error de conexión con el servidor.');
    } finally {
      btn.disabled = false;
      btn.textContent = 'Crear Cuenta';
    }
  });

  <?php if ($abrirLogin): ?>
  window.addEventListener('DOMContentLoaded', () => {
    new bootstrap.Modal(document.getElementById('modalLogin')).show();
  });
  <?php endif; ?>
</script>
</body>
</html>
