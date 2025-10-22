(function(window, document, $){
	'use strict';

	function createIndicator()
	{
		var contenedor = document.querySelector('#barraTop .col-md-4');
		if(!contenedor)
		{
			return null;
		}

		var indicator = document.getElementById('estadoConexion');
		if(indicator && indicator.parentElement && indicator.tagName !== 'SPAN')
		{
			target = indicator.parentElement;
			target.removeChild(indicator);
			indicator = null;
		}
		if(!indicator)
		{
			indicator = document.createElement('span');
			indicator.id = 'estadoConexion';
			indicator.className = 'estado-conexion';
			contenedor.appendChild(indicator);
		}
		else if(indicator.parentElement !== contenedor)
		{
			contenedor.appendChild(indicator);
		}

		indicator.style.display = 'inline-block';
		indicator.style.marginLeft = '1vh';
		indicator.style.padding = '0.5vh 1.5vh';
		indicator.style.borderRadius = '16px';
		indicator.style.fontSize = '1.5vh';
		indicator.style.fontWeight = '600';
		indicator.style.boxShadow = '0 2px 4px rgba(0,0,0,0.3)';
		indicator.style.cursor = 'default';
		indicator.style.border = 'none';
		indicator.style.backgroundRepeat = 'no-repeat';
		indicator.style.backgroundImage = 'none';
		indicator.style.verticalAlign = 'middle';

		return indicator;
	}

	function updateIndicator()
	{
		var indicator = createIndicator();
		if(!indicator)
		{
			return;
		}
		if(!indicator)
		{
			return;
		}

		var mensaje = 'Conectado';
		var color = '#1b5e20';

		if(!navigator.onLine)
		{
			mensaje = 'Sin conexión';
			color = '#b00020';
		}

	indicator.style.backgroundColor = color;
	indicator.style.color = '#fff';
	indicator.textContent = mensaje;
	indicator.title = 'Click para sincronizar';
	indicator.style.cursor = 'pointer';
	indicator.onclick = function(){
		if(typeof window.sincronizarPOS === 'function')
		{
			window.sincronizarPOS().then(function(){
				if(typeof window.actualizarEstadoConexion === 'function')
				{
					window.actualizarEstadoConexion();
				}
			}).catch(function(error){
				console.warn('Sincronización manual fallida', error);
			});
		}
	};

	if(window.posCache && typeof window.posCache.countVentasPendientes === 'function')
	{
		window.posCache.countVentasPendientes().then(function(total)
		{
			if(total > 0)
			{
				indicator.textContent = mensaje + ' · Pendientes: ' + total;
			}
		});
	}
}

	window.addEventListener('online', updateIndicator);
	window.addEventListener('offline', updateIndicator);

	$(document).ready(function(){
	updateIndicator();
	});

window.actualizarEstadoConexion = updateIndicator;

})(window, document, jQuery);
