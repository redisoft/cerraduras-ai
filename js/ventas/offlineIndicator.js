(function(window, document, $){
	'use strict';

	function createIndicator()
	{
	var indicator = document.getElementById('menuDesconectado');
	if(!indicator)
	{
		return null;
	}

	indicator.className = 'estado-conexion';

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

	function createSyncButton()
	{
		var contenedor = document.querySelector('#barraTop .col-md-4');
		if(!contenedor)
		{
			return null;
		}

		var boton = document.getElementById('btnSincronizarPOS');
		if(!boton)
		{
			boton = document.createElement('span');
			boton.id = 'btnSincronizarPOS';
			boton.className = 'boton-sync-pos';
			boton.textContent = 'Sincronizar';
			boton.title = 'Sincronizar catálogo y ventas pendientes';
			boton.onclick = function()
			{
				if(typeof window.sincronizarPOS === 'function')
				{
					$(boton).addClass('en-progreso');
					window.sincronizarPOS().then(function(){
						notify('Sincronización completada',500,4000,'',30,5);
					}).catch(function(error){
						console.warn('Sincronización manual fallida', error);
						notify('No se pudo sincronizar. Intente de nuevo.',500,5000,'error',30,5);
					}).finally(function(){
						$(boton).removeClass('en-progreso');
						if(typeof window.actualizarEstadoConexion === 'function')
						{
							window.actualizarEstadoConexion();
						}
					});
				}
			};
			contenedor.insertBefore(boton, document.getElementById('menuDesconectado'));
		}

		boton.style.display = 'inline-block';
		boton.style.padding = '0.5vh 1.2vh';
		boton.style.borderRadius = '16px';
		boton.style.backgroundColor = '#01579b';
		boton.style.color = '#fff';
		boton.style.fontSize = '1.4vh';
		boton.style.fontWeight = '600';
		boton.style.cursor = 'pointer';
		boton.style.marginRight = '1vh';
		boton.style.boxShadow = '0 2px 4px rgba(0,0,0,0.3)';

		return boton;
	}

	function updateIndicator()
	{
		var indicator = createIndicator();
		if(!indicator)
		{
			return;
		}
		createSyncButton();
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
			if(typeof window.actualizarEstadoBotonPendientes === 'function')
			{
				window.actualizarEstadoBotonPendientes();
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
