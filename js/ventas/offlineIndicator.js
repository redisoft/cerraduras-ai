(function(window, document, $){
	'use strict';

	function createIndicator()
	{
		var indicator = document.getElementById('menuDesconectado');
		if(!indicator)
		{
			return null;
		}

		indicator.classList.add('estado-conexion');

		indicator.style.display = 'inline-block';
		indicator.style.float = 'none';
		indicator.style.backgroundImage = 'none';
		indicator.style.backgroundColor = '#1b5e20';
		indicator.style.width = 'auto';
		indicator.style.padding = '0.5vh 1.5vh';
		indicator.style.margin = '0 0 0 1vh';
		indicator.style.borderRadius = '16px';
		indicator.style.fontSize = '1.5vh';
		indicator.style.fontWeight = '600';
		indicator.style.lineHeight = 'normal';
		indicator.style.boxShadow = '0 2px 4px rgba(0,0,0,0.3)';
		indicator.style.border = 'none';
		indicator.style.cursor = 'default';
		indicator.style.textAlign = 'center';
		indicator.style.minWidth = '8vh';

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
