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

function solicitarPersistencia()
{
	if(navigator.storage && navigator.storage.persist)
	{
		navigator.storage.persist().then(function(granted){
			if(!granted)
			{
				console.warn('El almacenamiento persistente no fue concedido, riesgo de pérdida de datos offline.');
			}
		}).catch(function(err){
			console.warn('No se pudo solicitar almacenamiento persistente', err);
		});
	}
}

$(document).ready(function(){
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

	solicitarPersistencia();
});

})(window, document);
