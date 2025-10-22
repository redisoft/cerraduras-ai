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
                setTimeout(function(){ contenedor.fadeOut(); }, 2500);
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
        $('#btnInstalarLogin').text('Instalar App Cerraduras').prop('disabled', false).show();
    });

    window.addEventListener('appinstalled', function(){
        deferredPrompt = null;
        $('#btnInstalarLogin').prop('disabled', true).text('App instalada');
    });

    function iniciarInstalacion()
    {
        var boton = $('#btnInstalarLogin');
        boton.prop('disabled', true).text('Instalando...');
        mostrarProcesoLogin();

        var duracion = pasosLogin.length * 800 + 1200;
        setTimeout(function(){
            var consola = $('#instalacionProceso .console-codigo');
            if(deferredPrompt)
            {
                deferredPrompt.prompt();
                deferredPrompt.userChoice.then(function(choiceResult){
                    if(choiceResult.outcome === 'accepted')
                    {
                        consola.append('<p class="linea">El usuario aceptó la instalación.</p>');
                    }
                    else
                    {
                        consola.append('<p class="linea">Instalación cancelada por el usuario.</p>');
                    }
                }).catch(function(){
                    consola.append('<p class="linea">Error al iniciar la instalación.</p>');
                }).finally(function(){
                    deferredPrompt = null;
                    boton.prop('disabled', false).text('Instalar App Cerraduras');
                });
            }
            else
            {
                consola.append('<p class="linea">Tu navegador ya dispone de la app o no soporta instalación automática.</p>');
                boton.prop('disabled', false).text('Instalar App Cerraduras');
            }
        }, duracion);
    }

    $(document).ready(function(){
        var botonLogin = $('#btnInstalarLogin');
        if(botonLogin.length)
        {
            botonLogin.show().on('click', iniciarInstalacion);
        }

        var botonEstacion = $('#btnSimularInstalacion');
        if(botonEstacion.length)
        {
            botonEstacion.on('click', function(){
                window.mostrarProcesoInstalacion('#instalacionCookieProceso');
            });
        }
    });

})(window, document, jQuery);
