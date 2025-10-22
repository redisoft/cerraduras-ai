(function(window, document, $){
	'use strict';

	function createIndicator()
	{
		var existing = document.getElementById('estadoConexion');
		if(existing)
		{
			return existing;
		}

		var div = document.createElement('div');
		div.id = 'estadoConexion';
		div.style.position = 'fixed';
		div.style.bottom = '20px';
		div.style.right = '20px';
		div.style.zIndex = '9999';
		div.style.padding = '10px 16px';
		div.style.borderRadius = '6px';
		div.style.fontSize = '14px';
		div.style.boxShadow = '0 4px 10px rgba(0,0,0,0.2)';
		div.style.display = 'none';
		div.style.backgroundColor = '#b00020';
		div.style.color = '#fff';
		div.textContent = 'Sin conexión';
		document.body.appendChild(div);
		return div;
	}

	function updateIndicator()
	{
		var indicator = createIndicator();
		if(navigator.onLine)
		{
			indicator.style.display = 'none';
		}
		else
		{
			indicator.style.display = 'block';
		}
	}

	window.addEventListener('online', updateIndicator);
	window.addEventListener('offline', updateIndicator);

	$(document).ready(function(){
		updateIndicator();
	});

})(window, document, jQuery);
