(function(window, document, $){
	'use strict';

	var dialogId = 'modalVentasPendientes';
	var tablaDetallesId = 'tablaPendientesPOS';
var botonPendientesId = 'btnPendientesPOS';

	function obtenerModuloActual()
	{
		if(typeof window.obtenerModuloPOSActual === 'function')
		{
			return window.obtenerModuloPOSActual();
		}
		return 'general';
	}

	function ensureDialog()
	{
		var modal = document.getElementById(dialogId);
		if(!modal)
		{
			modal = document.createElement('div');
			modal.id = dialogId;
			modal.title = 'Ventas guardadas sin conexión';
			modal.style.display = 'none';
			document.body.appendChild(modal);
			$('#'+dialogId).dialog({
				autoOpen:false,
				width:600,
				modal:true,
				buttons:{
					Cerrar:function(){ $(this).dialog('close'); }
				}
			});
		}
		return modal;
	}

	function renderVentas(lista)
	{
		if(!Array.isArray(lista) || !lista.length)
		{
			return '<div class="mensajePendientes">Sin ventas pendientes.</div>';
		}
		var html = '<table class="tablaPendientes" id="'+tablaDetallesId+'">';
		html += '<thead><tr><th>#</th><th>Cliente</th><th>Total</th><th>Fecha</th><th>Acciones</th></tr></thead><tbody>';
		lista.forEach(function(venta, index){
			html += '<tr data-id="'+venta.id+'" data-url="'+(venta.url || '')+'" data-payload="'+encodeURIComponent(JSON.stringify(venta.payload || {}))+'">'+
				'<td>'+(index+1)+'</td>'+
				'<td>'+(venta.clienteNombre || 'N/A')+'</td>'+
				'<td>$'+(venta.total || '0')+'</td>'+
				'<td>'+(venta.fecha ? new Date(venta.fecha).toLocaleString() : '-')+'</td>'+
				'<td><button class="btn-Reintentar" data-id="'+venta.id+'">Enviar</button></td>'+
			'</tr>';
		});
		html += '</tbody></table>';
		html += '<div class="detallePendiente"><pre id="detalleVentaPendiente">Selecciona una venta para ver el detalle.</pre></div>';
		return html;
	}

	window.mostrarVentasPendientes = function()
	{
		if(!window.posCache || typeof window.posCache.getVentasPendientes !== 'function')
		{
			notify('No se puede obtener la lista de ventas sin conexión.',500,5000,'error',30,5);
			return;
		}
		window.posCache.getVentasPendientes().then(function(lista)
		{
			ensureDialog();
			$('#'+dialogId).html(renderVentas(lista));
			var tabla = document.getElementById(tablaDetallesId);
			if(tabla)
			{
				tabla.addEventListener('click', function(e)
				{
					var target = e.target;
					if(target.classList.contains('btn-Reintentar'))
					{
						var id = parseInt(target.getAttribute('data-id'), 10);
						if(!isNaN(id) && typeof window.reintentarVentaPendiente === 'function')
						{
							target.disabled = true;
							target.textContent = 'Enviando...';
							window.reintentarVentaPendiente(id).finally(function()
							{
								target.disabled = false;
								target.textContent = 'Enviar';
							});
						}
					}

					var fila = target.closest('tr');
					if(fila && fila.dataset.payload)
					{
						var detalle = document.getElementById('detalleVentaPendiente');
						if(detalle)
						{
							try
							{
								var payload = JSON.parse(decodeURIComponent(fila.dataset.payload));
								detalle.textContent = JSON.stringify(payload, null, 2);
							}
							catch(err)
							{
								detalle.textContent = 'No fue posible decodificar la venta.';
							}
						}
					}
				});
			}
			$('#'+dialogId).dialog('open');
		}).catch(function(error)
		{
			console.error('Error obteniendo ventas pendientes', error);
			notify('No se pudo obtener la lista de ventas pendientes.',500,5000,'error',30,5);
		});
	};

	function actualizarEstadoBotonPendientes()
	{
		var boton = document.getElementById('btnPendientesPOS');
		if(!boton || !window.posCache || typeof window.posCache.countVentasPendientes !== 'function')
		{
			return;
		}
		if(obtenerModuloActual() !== 'ventas')
		{
			boton.style.display = 'none';
			return;
		}
		boton.style.display = 'inline-block';
		window.posCache.countVentasPendientes().then(function(total)
		{
			if(total > 0)
			{
				boton.classList.remove('empty');
				boton.textContent = 'Pendientes ('+total+')';
			}
			else
			{
				boton.classList.add('empty');
				boton.textContent = 'Pendientes';
			}
		});
	}

	window.actualizarEstadoBotonPendientes = actualizarEstadoBotonPendientes;

	window.reintentarVentaPendiente = function(id)
	{
		if(!window.posCache || typeof window.posCache.getVentasPendientes !== 'function')
		{
			return Promise.resolve();
		}

		return window.posCache.getVentasPendientes().then(function(lista)
		{
			var venta = lista.find(function(item){ return item.id === id; });
			if(!venta)
			{
				notify('Venta no encontrada en pendientes.',500,4000,'error',30,5);
				return;
			}

			if(!navigator.onLine)
			{
				notify('Sin conexión. No es posible enviar la venta.',500,4000,'error',30,5);
				return;
			}

			return enviarVentaPendiente(venta).then(function(res){
				if(res)
				{
					notify('Venta enviada correctamente.',500,4000,'',30,5);
					return true;
				}
				notify('No se pudo enviar la venta. Intente más tarde.',500,4000,'error',30,5);
				return false;
			}).finally(function(){
				actualizarEstadoBotonPendientes();
				if(typeof window.actualizarEstadoConexion === 'function')
				{
					window.actualizarEstadoConexion();
				}
			});
		});
	};

	$(document).ready(function(){
		ensureDialog();
		var boton = document.getElementById('btnPendientesPOS');
		if(boton)
		{
			if(obtenerModuloActual() !== 'ventas')
			{
				boton.style.display = 'none';
				return;
			}
			boton.addEventListener('click', function(){
				if(boton.classList.contains('empty'))
				{
					notify('No hay ventas pendientes.',500,3000,'',30,5);
					return;
				}
				window.mostrarVentasPendientes();
			});
		}
		actualizarEstadoBotonPendientes();
	});

})(window, document, jQuery);
