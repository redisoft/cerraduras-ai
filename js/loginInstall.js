(function(window, document, $){
    'use strict';

    var STORAGE_KEY = 'cerradurasPwaInstalada';
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

    function marcarInstalada()
    {
        $('#estadoPwa').addClass('instalada').text('APP instalada');
        $('#btnInstalarLogin').hide();
        $('.barraInstalacion').hide();
        localStorage.setItem(STORAGE_KEY,'1');
    }

    function prepararBotonInstalacion()
    {
        $('#estadoPwa').removeClass('instalada').text('');
        $('#btnInstalarLogin').text('Instalar App Cerraduras').prop('disabled', false).show();
        $('.barraInstalacion').show();
    }

    window.addEventListener('beforeinstallprompt', function(event){
        event.preventDefault();
        deferredPrompt = event;
        prepararBotonInstalacion();
    });

    window.addEventListener('appinstalled', function(){
        deferredPrompt = null;
        marcarInstalada();
    });

    function iniciarInstalacion()
    {
        var boton = $('#btnInstalarLogin');
        boton.prop('disabled', true).text('Instalando...');
        $('.barraInstalacion').show();
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
                        marcarInstalada();
                    }
                    else
                    {
                        consola.append('<p class="linea">Instalación cancelada por el usuario.</p>');
                        prepararBotonInstalacion();
                    }
                }).catch(function(){
                    consola.append('<p class="linea">Error al iniciar la instalación.</p>');
                    prepararBotonInstalacion();
                }).finally(function(){
                    deferredPrompt = null;
                    if(!$('#estadoPwa').hasClass('instalada'))
                    {
                        boton.prop('disabled', false).text('Instalar App Cerraduras').show();
                    }
                });
            }
            else
            {
                consola.append('<p class="linea">Tu navegador ya dispone de la app o no soporta instalación automática.</p>');
                if(!$('#estadoPwa').hasClass('instalada'))
                {
                    boton.prop('disabled', false).text('Instalar App Cerraduras').show();
                }
            }
        }, duracion);
    }

    $(document).ready(function(){
        var noFirefox = navigator.userAgent.toLowerCase().indexOf('firefox') === -1;
        if(noFirefox){
            alert('El sistema es compatible únicamente con Mozilla Firefox.');
        }
        var instaladaPrev = localStorage.getItem(STORAGE_KEY) === '1' || window.matchMedia('(display-mode: standalone)').matches;
        if(instaladaPrev)
        {
            marcarInstalada();
        }
        var botonLogin = $('#btnInstalarLogin');
        if(botonLogin.length)
        {
            botonLogin.show().on('click', iniciarInstalacion);
            if(instaladaPrev)
            {
                botonLogin.hide();
            }
        }

        var botonEstacion = $('#btnSimularInstalacion');
        if(botonEstacion.length)
        {
            botonEstacion.on('click', function(){
                window.mostrarProcesoInstalacion('#instalacionCookieProceso');
            });
            if(instaladaPrev)
            {
                botonEstacion.hide();
            }
        }
    });

})(window, document, jQuery);
