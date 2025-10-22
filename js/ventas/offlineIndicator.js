(function(window, document, $){
	'use strict';

	function createIndicator()
	{
		var existing = document.getElementById('estadoConexion');
		if(existing)
		{
			return existing;
		}

		var contenedor = document.getElementById('barraTop') || document.body;
		if(window.getComputedStyle(contenedor).position === 'static')
		{
			contenedor.style.position = 'relative';
		}

		var div = document.createElement('div');
		div.id = 'estadoConexion';
		div.style.position = 'absolute';
		div.style.top = '8px';
		div.style.right = '20px';
		div.style.zIndex = '999';
		div.style.padding = '6px 14px';
		div.style.borderRadius = '20px';
		div.style.fontSize = '13px';
		div.style.fontWeight = '600';
		div.style.boxShadow = '0 3px 6px rgba(0,0,0,0.2)';
		div.style.transition = 'background-color 0.3s ease';
		contenedor.appendChild(div);
		return div;
	}

	function updateIndicator()
	{
		var indicator = createIndicator();
		if(navigator.onLine)
		{
			indicator.style.backgroundColor = '#1b5e20';
			indicator.style.color = '#fff';
			indicator.textContent = 'Conectado';
			indicator.style.display = 'inline-block';
		}
		else
		{
			indicator.style.backgroundColor = '#b00020';
			indicator.style.color = '#fff';
			indicator.textContent = 'Sin conexión';
			indicator.style.display = 'inline-block';
		}
	}

	window.addEventListener('online', updateIndicator);
	window.addEventListener('offline', updateIndicator);

	$(document).ready(function(){
		updateIndicator();
	});

})(window, document, jQuery);
