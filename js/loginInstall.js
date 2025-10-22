(function(window, document, $){
	window.mostrarProcesoInstalacion = function(selector){
		var contenedor = $(selector);
		if(!contenedor.length){ return; }
		contenedor.show().html('<p>Configurando estación...</p>');
		var pasos = ['Validando navegador...', 'Verificando licencia...', 'Asignando estación...', 'Guardando cookie...', 'Finalizando...'];
		var i = 0;
		var interval = setInterval(function(){
			if(i >= pasos.length){
				clearInterval(interval);
				contenedor.append('<p>Configuración completada.</p>');
				setTimeout(function(){ contenedor.fadeOut(); }, 1500);
				return;
			}
			contenedor.append('<p>'+pasos[i]+'</p>');
			contenedor.scrollTop(contenedor[0].scrollHeight);
			i++;
		}, 1000);
	};


    'use strict';

    var deferredPrompt = null;
    var procesoInterval = null;
    var pasos = [
        'Descargando paquete de instalación...',
        'Verificando integridad...',
        'Preparando recursos offline...',
        'Configurando permisos...',
        'Instalando módulos POS...',
        'Optimizando caché para modo sin conexión...',
        'Registrando aplicación...',
        'Finalizando...' 
    ];

    function mostrarProceso()
    {
        var contenedor = $('#instalacionProceso');
        contenedor.show().html('');
        var index = 0;

        procesoInterval = setInterval(function(){
            if(index >= pasos.length)
            {
                clearInterval(procesoInterval);
                contenedor.append('<p>Instalación completada.</p>');
                setTimeout(function(){ contenedor.fadeOut(); }, 1500);
                return;
            }
            contenedor.append('<p>'+pasos[index]+'</p>');
            contenedor.scrollTop(contenedor[0].scrollHeight);
            index++;
        }, 800);
    }

    window.addEventListener('beforeinstallprompt', function(event){
        event.preventDefault();
        deferredPrompt = event;
        $('#btnInstalarLogin').show();
    });

    window.addEventListener('appinstalled', function(){
        deferredPrompt = null;
        $('#btnInstalarLogin').hide();
    });

    $(document).ready(function(){
        var boton = $('#btnInstalarLogin');
        if(!boton.length)
        {
            return;
        }

        boton.hide();

        boton.on('click', function(){
            if(!deferredPrompt)
            {
                mostrarProceso();
                setTimeout(function(){ $('#instalacionProceso').append('<p>Tu navegador ya tiene la app instalada.</p>'); }, 2000);
                return;
            }

            boton.prop('disabled', true);
            mostrarProceso();

            setTimeout(function(){
                deferredPrompt.prompt();
                deferredPrompt.userChoice.then(function(choiceResult){
                    if(choiceResult.outcome === 'accepted')
                    {
                        $('#instalacionProceso').append('<p>El usuario aceptó la instalación.</p>');
                    }
                    else
                    {
                        $('#instalacionProceso').append('<p>Instalación cancelada.</p>');
                    }
                }).catch(function(){
                    $('#instalacionProceso').append('<p>Error al iniciar la instalación.</p>');
                }).finally(function(){
                    deferredPrompt = null;
                    boton.prop('disabled', false);
                    setTimeout(function(){ $('#instalacionProceso').fadeOut(); }, 2000);
                });
            }, 1500);
        });
    });

})(window, document, jQuery);
