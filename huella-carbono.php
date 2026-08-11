<?php
require_once __DIR__ . '/includes/session_check.php';
requerirLogin();
$usuario = usuarioActual();
$paginaActiva = 'huella';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Huella de Carbono – REUSE</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet" />
  <link rel="stylesheet" href="styles.css" />
  <style>
    .nivel-badge { display:inline-block;padding:8px 24px;border-radius:20px;font-weight:800;font-size:1.1rem;margin-top:8px; }
    .nivel-bajo  { background: #d4edda; color: #155724; }
    .nivel-medio { background: #fff3cd; color: #856404; }
    .nivel-alto  { background: #f8d7da; color: #721c24; }
    .tip-card { background: #f9fdf5; border-radius: 12px; border: 1px solid #e0e0d0; padding: 16px 20px; margin-top: 12px; }
    .tip-card h6 { font-weight: 700; color: var(--green-dark); margin-bottom: 8px; }
    .tip-card ul { font-size: .85rem; padding-left: 18px; margin: 0; color: #444; }
    #resultadoHuella { display: none; }
  </style>
</head>
<body>

<?php require __DIR__ . '/includes/header_dashboard.php'; ?>

    <nav aria-label="breadcrumb" class="mb-3">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="panel.php">Inicio</a></li>
        <li class="breadcrumb-item active">Huella de carbono</li>
      </ol>
    </nav>

    <div class="row g-0" style="min-height: calc(100vh - 180px);">

      <div class="col-lg-4 pe-lg-4">
        <div class="form-page-card">
          <h2>Huella de Carbono</h2>
          <p class="subtitle">Calcula tu impacto ambiental según tu ubicación y tipo de vivienda.</p>

          <form>
            <div class="mb-3">
              <label class="form-label">País</label>
              <select class="form-select" id="pais">
                <option selected>Costa Rica</option>
                <option>Guatemala</option>
                <option>Honduras</option>
                <option>El Salvador</option>
                <option>Nicaragua</option>
                <option>Panamá</option>
              </select>
            </div>

            <div class="mb-3">
              <label class="form-label">Estado / Provincia</label>
              <select class="form-select" id="estado"></select>
            </div>

            <div class="mb-3">
              <label class="form-label">Tipo de vivienda</label>
              <select class="form-select" id="tipo">
                <option selected>Vivienda</option>
                <option>Apartamento</option>
                <option>Casa independiente</option>
                <option>Oficina</option>
                <option>Local comercial</option>
              </select>
            </div>

            <div class="mb-4">
              <label class="form-label">Fecha</label>
              <input type="date" class="form-control" id="fecha" />
            </div>

            <button type="button" class="btn-green-full" onclick="calcularHuella()">Buscar</button>
          </form>
        </div>
      </div>

      <div class="col-lg-8 d-flex align-items-center justify-content-center">

        <div id="vistaInicial" class="ilustracion-lateral">
          <img src="corazonreciclaje.png" alt="Huella de carbono" style="width:100%;max-width:440px;object-fit:contain;opacity:.85;" />
          <p class="mt-3 text-muted" style="font-size:.9rem">Completa el formulario y calcula tu huella de carbono</p>
        </div>

        <div id="resultadoHuella" class="form-page-card w-100">
          <h5 class="fw-bold mb-1">Resultado de tu Huella de Carbono</h5>
          <p class="text-muted mb-3" style="font-size:.85rem" id="resUbicacion"></p>

          <div class="text-center py-2">
            <p class="mb-1" style="font-size:.9rem;color:#555">Tu nivel estimado de huella:</p>
            <div class="nivel-badge nivel-bajo" id="nivelBadge">Nivel Bajo</div>
            <p class="mt-3" style="font-size:.85rem;color:#555">
              Estimado de CO₂ mensual: <strong id="co2Val">1.2 toneladas</strong>
            </p>
          </div>

          <div class="mb-3">
            <div class="d-flex justify-content-between" style="font-size:.78rem;color:#666">
              <span>Bajo</span><span>Medio</span><span>Alto</span>
            </div>
            <div class="progress" style="height:12px;border-radius:8px">
              <div class="progress-bar" id="progressBar" role="progressbar"
                   style="width:25%;background:var(--green-dark);border-radius:8px"></div>
            </div>
          </div>

          <div class="tip-card">
            <h6>💡 Consejos para mejorar tu huella</h6>
            <ul>
              <li>Utiliza transporte público o bicicleta.</li>
              <li>Reduce el consumo de energía en el hogar.</li>
              <li>Prefiere productos locales y de temporada.</li>
              <li>Recicla y reutiliza siempre que puedas.</li>
            </ul>
          </div>

          <div class="d-flex gap-2 mt-3 flex-wrap">
            <a href="registro-reciclaje.php" class="btn"
               style="background:var(--green-dark);color:#fff;border-radius:8px;font-weight:700;font-size:.88rem">
              ♻️ Registrar reciclaje
            </a>
            <button onclick="resetHuella()" class="btn btn-outline-secondary" style="border-radius:8px;font-size:.88rem">
              Nueva búsqueda
            </button>
          </div>
        </div>

      </div>
    </div>

  </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
  document.getElementById('fecha').valueAsDate = new Date();

  $(document).ready(function() {
    const provinciasMap = {
      "Costa Rica": ["San José", "Alajuela", "Cartago", "Heredia", "Guanacaste", "Puntarenas", "Limón"],
      "Guatemala": ["Ciudad de Guatemala", "Quetzaltenango", "Escuintla", "Alta Verapaz", "Sacatepéquez", "Petén"],
      "Honduras": ["Tegucigalpa", "San Pedro Sula", "Atlántida", "Copán", "Choluteca", "Comayagua"],
      "El Salvador": ["San Salvador", "Santa Ana", "San Miguel", "La Libertad", "Sonsonate", "Usulután"],
      "Nicaragua": ["Managua", "León", "Granada", "Chinandega", "Estelí", "Masaya"],
      "Panamá": ["Panamá", "Panamá Oeste", "Colón", "Chiriquí", "Veraguas", "Coclé"]
    };

    function cargarEstados(pais) {
      let $selectEstado = $("#estado");
      $selectEstado.empty();
      let estados = provinciasMap[pais] || [];
      estados.forEach(function(est) {
        $selectEstado.append('<option value="' + est + '">' + est + '</option>');
      });
    }

    cargarEstados($("#pais").val());
    $("#pais").change(function() { cargarEstados($(this).val()); });
  });

  function calcularHuella() {
    const pais = document.getElementById('pais').value;
    const estado = document.getElementById('estado').value;
    const tipo = document.getElementById('tipo').value;
    const fecha = document.getElementById('fecha').value;

    if (!pais || !estado || !tipo || !fecha) {
      alert("Por favor completa todos los campos del formulario.");
      return;
    }

    $.ajax({
      url: "api/guardar_huella.php",
      type: "POST",
      dataType: "json",
      data: { pais: pais, estado: estado, tipo: tipo, fecha: fecha },
      success: function(respuesta) {
        if (respuesta.status === "success") {
          const nivel = respuesta.nivel;
          const clsMap = { Bajo: 'nivel-bajo', Medio: 'nivel-medio', Alto: 'nivel-alto' };
          const colorMap = { Bajo: 'var(--green-dark)', Medio: '#e8a000', Alto: '#dc3545' };

          document.getElementById('resUbicacion').textContent = `${pais} · ${estado} · ${tipo}`;

          const badge = document.getElementById('nivelBadge');
          badge.className = 'nivel-badge ' + clsMap[nivel];
          badge.textContent = 'Nivel ' + nivel;

          document.getElementById('co2Val').textContent = respuesta.co2 + ' toneladas';

          const bar = document.getElementById('progressBar');
          bar.style.width = respuesta.porcentaje + '%';
          bar.style.background = colorMap[nivel];

          document.getElementById('vistaInicial').style.display = 'none';
          document.getElementById('resultadoHuella').style.display = 'block';
        } else {
          alert(respuesta.message || "No se pudo guardar la huella de carbono.");
        }
      },
      error: function(xhr) {
        if (xhr.status === 401) {
          window.location.href = "index.php?auth=1";
          return;
        }
        alert("Ocurrió un error al guardar la huella de carbono.");
      }
    });
  }

  function resetHuella() {
    document.getElementById('resultadoHuella').style.display = 'none';
    document.getElementById('vistaInicial').style.display = 'flex';
  }
</script>
</body>
</html>
