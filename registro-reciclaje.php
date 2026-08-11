<?php
require_once __DIR__ . '/includes/session_check.php';
requerirLogin();
$usuario = usuarioActual();
$paginaActiva = 'reciclaje';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Registrar Reciclaje – REUSE</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet" />
  <link rel="stylesheet" href="styles.css" />
  <style>
    #bloqueConfirmacion { display: none; }
  </style>
</head>
<body>

<?php require __DIR__ . '/includes/header_dashboard.php'; ?>

    <nav aria-label="breadcrumb" class="mb-3">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="panel.php">Inicio</a></li>
        <li class="breadcrumb-item active">Registrar reciclaje</li>
      </ol>
    </nav>

    <div class="row g-0" style="min-height: calc(100vh - 180px);">

      <div class="col-lg-4 pe-lg-4">
        <div class="form-page-card">
          <h2>Registrar reciclaje</h2>
          <p class="subtitle">Registra los materiales que reciclaste y gana puntos.</p>

          <form id="formReciclaje">
            <div class="mb-3">
              <label class="form-label">Tipo de material</label>
              <select class="form-select" id="materialSelect" required>
                <option value="" disabled selected>Selecciona un material</option>
                <option value="10">Plástico</option>
                <option value="15">Vidrio</option>
                <option value="8">Papel / Cartón</option>
                <option value="20">Metal</option>
                <option value="25">Electrónico</option>
                <option value="5">Orgánico</option>
              </select>
            </div>

            <div class="mb-3">
              <label class="form-label">Cantidad (kg)</label>
              <div class="input-group">
                <input type="number" class="form-control" id="cantidadKg" min="0.1" step="0.1" placeholder="0" required />
                <span class="input-group-text">kg</span>
              </div>
            </div>

            <div class="mb-3">
              <label class="form-label">Centro de acopio</label>
              <select class="form-select" id="centroSelect" required>
                <option value="" disabled selected>Selecciona un centro</option>
                <option>Centro Verde Heredia</option>
                <option>EcoAcopio San José</option>
                <option>Punto Verde Alajuela</option>
                <option>Recicla Cartago</option>
              </select>
            </div>

            <div class="mb-4">
              <label class="form-label">Fecha</label>
              <input type="date" class="form-control" id="fechaReciclaje" required />
            </div>

            <button type="button" class="btn-green-full" id="btnRegistrar">Registrar acción</button>
          </form>
        </div>
      </div>

      <div class="col-lg-8">
        <div id="bloqueConfirmacion" class="accion-registrada h-100">
          <p class="accion-titulo">¡ACCIÓN<br>REGISTRADA!</p>
          <img src="Accionregistrada.png" alt="Acción registrada" style="width:100%;max-width:460px;object-fit:contain;" />
          <p class="puntos-label">HAS GANADO<br><span style="font-size:1.6rem;color:var(--green-dark);font-weight:900" id="puntosGanadosTxt">+0 PUNTOS</span></p>
          <button id="btnNuevoRegistro" class="btn mt-3"
            style="background:var(--green-dark);color:#fff;border-radius:8px;padding:10px 28px;font-weight:700">
            Registrar otro material
          </button>
          <button type="button" class="btn btn-outline-success ms-2 mt-3" id="btnExportarExcel" style="border-radius:8px; font-weight:700; padding:10px 20px;">
            Exportar Registro a CSV
          </button>
          <button type="button" class="btn btn-outline-primary ms-2 mt-3" id="btnImprimir" style="border-radius:8px; font-weight:700; padding:10px 20px;">
            Imprimir Comprobante
          </button>
          <a href="historial-reciclaje.php"
           class="btn btn-outline-success ms-2 mt-3"
            style="border-radius:8px; font-weight:700; padding:10px 20px;">
              Ver historial
          </a>
        </div>
      </div>

    </div>

  </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
  $(document).ready(function() {
    $("#fechaReciclaje").val(new Date().toISOString().substring(0, 10));

    $("#btnRegistrar").click(function() {
      let puntosBase = parseFloat($("#materialSelect").val());
      let kilos = parseFloat($("#cantidadKg").val());
      let centro = $("#centroSelect").val();
      let materialNombre = $("#materialSelect option:selected").text();
      let fecha = $("#fechaReciclaje").val();

      if (!puntosBase || !kilos || !centro || !fecha) {
        alert("Por favor completa todos los campos del formulario.");
        return;
      }

      let puntosGanados = Math.round(puntosBase * kilos);

      $.ajax({
        url: "api/guardar_reciclaje.php",
        type: "POST",
        dataType: "json",
        data: {
          material: materialNombre,
          kilos: kilos,
          centro: centro,
          puntos: puntosGanados,
          fecha: fecha
        },
        success: function(respuesta) {
          if (respuesta.status === "success") {
            $("#puntosGanadosTxt").text("+" + puntosGanados + " PUNTOS");
            $("#bloqueConfirmacion").show();
          } else {
            alert(respuesta.message || "No se pudo guardar el registro.");
          }
        },
        error: function(xhr) {
          if (xhr.status === 401) {
            window.location.href = "index.php?auth=1";
            return;
          }
          alert("Ocurrió un error al guardar el registro.");
        }
      });
    });

    $("#btnNuevoRegistro").click(function() {
      $("#formReciclaje")[0].reset();
      $("#fechaReciclaje").val(new Date().toISOString().substring(0, 10));
      $("#bloqueConfirmacion").hide();
    });

    $("#btnExportarExcel").click(function() {
      let materialNombre = $("#materialSelect option:selected").text();
      let kilos = $("#cantidadKg").val();
      let centro = $("#centroSelect").val();
      let fecha = $("#fechaReciclaje").val();
      let puntos = $("#puntosGanadosTxt").text();

      let contenidoCSV = "\ufeffFecha;Material;Cantidad (kg);Centro de Acopio;Puntos Ganados\n" +
        `"${fecha}";"${materialNombre}";"${kilos}";"${centro}";"${puntos}"`;

      let blob = new Blob([contenidoCSV], { type: 'text/csv;charset=utf-8;' });
      let url = URL.createObjectURL(blob);
      let a = document.createElement('a');
      a.href = url;
      a.download = 'Comprobante_Reciclaje_REUSE.csv';
      a.click();
    });

    $("#btnImprimir").click(function() {
      window.print();
    });
  });
</script>
</body>
</html>
