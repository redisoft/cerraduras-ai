(function(window, document){
	'use strict';

	var deferredPrompt = null;

window.addEventListener('beforeinstallprompt', function(event){
		event.preventDefault();
		deferredPrompt = event;
		mostrarBotonInstalar(true);
	});

	window.addEventListener('appinstalled', function(){
		deferredPrompt = null;
		mostrarBotonInstalar(false);
	});

function mostrarBotonInstalar(visible)
	{
		var boton = document.getElementById('btnInstalarPWA');
		if(!boton)
		{
			return;
		}
		if(visible)
		{
			boton.classList.remove('hidden');
			boton.style.display = 'inline-block';
		}
		else
		{
			boton.classList.add('hidden');
			boton.style.display = 'none';
		}
	}

	document.addEventListener('DOMContentLoaded', function(){
		var boton = document.getElementById('btnInstalarPWA');
		if(!boton)
		{
			return;
		}
		boton.addEventListener('click', function(){
			if(!deferredPrompt)
			{
				return;
			}
			boton.disabled = true;
			deferredPrompt.prompt();
			deferredPrompt.userChoice.finally(function(){
				boton.disabled = false;
				mostrarBotonInstalar(false);
				deferredPrompt = null;
			});
		});
	});

})(window, document);
