function construirTarjetaCentro(centro) {
  return (
    '<article class="centro-tarjeta">' +
      '<div class="centro-cabecera">' +
        '<h3>' + centro.nombre + '</h3>' +
        '<span class="centro-distancia">' + parseFloat(centro.distancia_km) + ' km</span>' +
      '</div>' +
      '<p class="centro-direccion">' + centro.direccion + '</p>' +
      '<p class="centro-acepta">Acepta: <span>' + centro.materiales.split(',').join(', ') + '</span></p>' +
    '</article>'
  );
}

function cargarCentros(material, offset, agregar) {
  const $lista = $('#listaCentros');
  const $boton = $('#botonVerMasCentros');

  $.ajax({
    url: 'api/centros_acopio.php',
    type: 'GET',
    dataType: 'json',
    data: {
      material: material,
      offset: offset
    },
    success: function(respuesta) {
      if (respuesta.status !== 'success') {
        return;
      }

      const html = respuesta.centros.map(construirTarjetaCentro).join('');

      if (agregar) {
        $lista.find('.centro-tarjeta').last().after(html);
      } else {
        $lista.find('.centro-tarjeta').remove();
        $lista.prepend(html);
      }

      $boton.toggle(respuesta.hayMas);
      $boton.data('offset', offset + respuesta.centros.length);
    },
    error: function(xhr) {
      if (xhr.status === 401) {
        window.location.href = 'index.php?auth=1';
      }
    }
  });
}

$(document).ready(function() {
  $('#filtroMaterial').on('change', function() {
    const material = $(this).val();
    cargarCentros(material, 0, false);
  });

  $('#botonVerMasCentros').on('click', function() {
    const material = $('#filtroMaterial').val();
    const offset = $(this).data('offset') || 3;
    cargarCentros(material, offset, true);
  });
});
