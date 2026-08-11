function enviarCanjeServidor(nombreEmpresa, puntosConsumidos, boton) {
  const $exito = $("#canjeExito");
  const $error = $("#canjeError");
  $exito.addClass("d-none");
  $error.addClass("d-none");

  boton.disabled = true;
  const textoOriginal = boton.textContent;
  boton.textContent = "Canjeando...";

  $.ajax({
    url: "api/canjear_cupon.php",
    type: "POST",
    dataType: "json",
    data: {
      recompensa: nombreEmpresa,
      puntos: puntosConsumidos
    },
    success: function(respuesta) {
      if (respuesta.status === "success") {
        $exito.text("¡Cupón de " + nombreEmpresa + " canjeado! Código: " + respuesta.codigo).removeClass("d-none");

        // Actualiza el contador de puntos visibles sin recargar la página
        const $puntos = $("#puntosDisponibles");
        const puntosActuales = parseInt($puntos.text().replace(/\D/g, ""), 10) || 0;
        $puntos.text((puntosActuales - puntosConsumidos).toLocaleString());
      } else {
        $error.text(respuesta.message || "No se pudo registrar el canje.").removeClass("d-none");
      }
    },
    error: function(xhr) {
      if (xhr.status === 401) {
        window.location.href = "index.php?auth=1";
        return;
      }
      $error.text("Ocurrió un error al registrar el canje en el servidor.").removeClass("d-none");
    },
    complete: function() {
      boton.disabled = false;
      boton.textContent = textoOriginal;
    }
  });
}

$(document).ready(function() {
  $("#walmart").click(function() {
    enviarCanjeServidor("Walmart", 500, this);
  });

  $("#ekono").click(function() {
    enviarCanjeServidor("Ekono", 350, this);
  });

  $("#mcdonalds").click(function() {
    enviarCanjeServidor("McDonald's", 2000, this);
  });
});
