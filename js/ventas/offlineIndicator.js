(function(window, document, $){
	'use strict';

	function createIndicator()
	{
		var existing = document.getElementById('estadoConexion');
		if(existing)
		{
			return existing;
		}

		var contenedor = document.querySelector('#barraTop .menuBarra');
		if(!contenedor)
		{
			contenedor = document.body;
		}

		var li = document.createElement('li');
		li.id = 'estadoConexion';
		li.style.display = 'inline-block';
		li.style.marginLeft = '12px';
		li.style.padding = '4px 12px';
		li.style.borderRadius = '16px';
		li.style.fontSize = '12px';
		li.style.fontWeight = '600';
		li.style.boxShadow = '0 2px 4px rgba(0,0,0,0.2)';
		li.style.transition = 'background-color 0.3s ease';
		li.style.listStyle = 'none';
		li.style.alignSelf = 'center';
		contenedor.appendChild(li);
		return li;
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
