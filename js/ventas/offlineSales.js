(function(window, document, $){
	'use strict';

	var dialogId = 'modalVentasPendientes';

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
		var html = '<table class="tablaPendientes"><thead><tr><th>#</th><th>Cliente</th><th>Total</th><th>Fecha</th></tr></thead><tbody>';
		lista.forEach(function(venta, index){
			html += '<tr>'+
				'<td>'+(index+1)+'</td>'+
				'<td>'+(venta.clienteNombre || 'N/A')+'</td>'+
				'<td>$'+(venta.total || '0')+'</td>'+
				'<td>'+(venta.fecha ? new Date(venta.fecha).toLocaleString() : '-')+'</td>'+
			'</tr>';
		});
		html += '</tbody></table>';
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

	$(document).ready(function(){
		ensureDialog();
		var boton = document.getElementById('btnPendientesPOS');
		if(boton)
		{
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
