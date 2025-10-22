(function(window, document, $){
	'use strict';

	function createIndicator()
	{
		var contenedor = document.querySelector('#barraTop .menuBarra');
		if(!contenedor)
		{
			return null;
		}

		var indicator = document.getElementById('estadoConexion');
		if(!document.getElementById('estadoConexionStyle'))
		{
			var style = document.createElement('style');
			style.id = 'estadoConexionStyle';
			style.textContent = '#barraTop .menuBarra .estado-conexion:after{content:"" !important;}';
			document.head.appendChild(style);
		}
		if(!indicator)
		{
			indicator = document.createElement('li');
			indicator.id = 'estadoConexion';
			indicator.className = 'estado-conexion';
			contenedor.appendChild(indicator);
		}
		else if(indicator.parentElement !== contenedor)
		{
			contenedor.appendChild(indicator);
		}

		indicator.style.display = 'inline-flex';
		indicator.style.alignItems = 'center';
		indicator.style.justifyContent = 'center';
		indicator.style.margin = '0 1vh';
		indicator.style.padding = '0 2vh';
		indicator.style.height = '4vh';
		indicator.style.width = 'auto';
		indicator.style.borderRadius = '16px';
		indicator.style.fontSize = '1.5vh';
		indicator.style.fontWeight = '600';
		indicator.style.boxShadow = '0 2px 4px rgba(0,0,0,0.3)';
		indicator.style.cursor = 'default';
		indicator.style.listStyle = 'none';
		indicator.style.border = 'none';
		indicator.style.backgroundRepeat = 'no-repeat';
		indicator.style.backgroundImage = 'none';

		contenedor.style.display = 'flex';
		contenedor.style.alignItems = 'center';
		contenedor.style.justifyContent = 'flex-end';
		contenedor.style.gap = '8px';

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
