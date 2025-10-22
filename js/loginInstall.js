(function(window, document, $){
    'use strict';

    var deferredPrompt = null;
    var procesoInterval = null;
    var pasosLogin = [
        '>> Iniciando instalador Cerraduras POS',
        'Descargando paquete de instalación...',
        'Verificando integridad...',
        'Preparando recursos offline...',
        'Configurando permisos...',
        'Compilando scripts...',
        'Instalando módulos POS...',
        'Inyectando dependencias...',
        'Finalizando...'
    ];

    var pasosEstacion = [
        'Validando navegador...',
        'Verificando licencia...',
        'Asignando estación...',
        'Guardando cookie...',
        'Generando llaves...',
        'Compilando scripts...',
        'Estación lista.'
    ];

    function renderConsola(contenedor, pasos, mensajeFinal)
    {
        contenedor.show().html('<div class="console-codigo"></div>');
        var consola = contenedor.find('.console-codigo');
        var index = 0;

        procesoInterval = setInterval(function(){
            if(index >= pasos.length)
            {
                clearInterval(procesoInterval);
                consola.append('<p class="linea">'+mensajeFinal+'</p>');
                setTimeout(function(){ contenedor.fadeOut(); }, 2000);
                return;
            }
            var linea = $('<p class="linea"></p>').text(pasos[index]);
            consola.append(linea);
            consola.scrollTop(consola[0].scrollHeight);
            index++;
        }, 800);
    }

    window.mostrarProcesoInstalacion = function(selector)
    {
        var contenedor = $(selector);
        if(!contenedor.length){ return; }
        renderConsola(contenedor, pasosEstacion, '>> Estación configurada.');
    };

    function mostrarProcesoLogin()
    {
        var contenedor = $('#instalacionProceso');
        renderConsola(contenedor, pasosLogin, '>> Instalación completada.');
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
                mostrarProcesoLogin();
                setTimeout(function(){ $('#instalacionProceso .console-codigo').append('<p class="linea">Tu navegador ya tiene la app instalada.</p>'); }, 2000);
                return;
            }

            boton.prop('disabled', true);
            mostrarProcesoLogin();

            setTimeout(function(){
                deferredPrompt.prompt();
                deferredPrompt.userChoice.then(function(choiceResult){
                    var consola = $('#instalacionProceso .console-codigo');
                    if(choiceResult.outcome === 'accepted')
                    {
                        consola.append('<p class="linea">El usuario aceptó la instalación.</p>');
                    }
                    else
                    {
                        consola.append('<p class="linea">Instalación cancelada.</p>');
                    }
                }).catch(function(){
                    $('#instalacionProceso .console-codigo').append('<p class="linea">Error al iniciar la instalación.</p>');
                }).finally(function(){
                    deferredPrompt = null;
                    boton.prop('disabled', false);
                    setTimeout(function(){ $('#instalacionProceso').fadeOut(); }, 2000);
                });
            }, 1500);
        });
    });

})(window, document, jQuery);
