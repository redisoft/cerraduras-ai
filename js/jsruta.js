//var base_url="https://redisoftsystems.com/nube/greenlive/";
//base_url="http://localhost/greenlive/";

//var img_loader=base_url+"img/ajax-loader.gif";

//************* Script Ajax **********************
// JavaScript Document
var leyendas		= "";
var esperar			= "";
var conexion		= "";
var XMLHttpObject	= null;
var xhr				= false;
var ejecutar		= false;

var ejecutarAccion	 = false;

var tiempoRetraso;
/******************************************************************************/
var XMLHttpObject = function()
{
	try
	{
		var xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
	}
	catch(e)
	{
		try
		{
			var xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		catch(e)
		{
			var xmlhttp = false;
		}
	}
	
	return (!xmlhttp && typeof XMLHttpRequest!='undefined')?new XMLHttpRequest():xmlhttp || new function(){};
}

function XML(URL,PARAMETROS)
{
	var rpc 	=  new XMLHttpObject();
	rpc.open("POST",URL,false);
	rpc.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	//rpc.send(encodeURI("param1=parametro1&param2=parametro2&param3=Hola mundo"));
	rpc.send(PARAMETROS);
    return (rpc.responseText);
}

function getFechActual(T)
{
 	var URL=base_url+"clientes/FechActual";
    return (XML(URL,"Fecha="+T));
}

function number_format(numero,decimal,Val,Espacio)
{
	var URL	= base_url+"clientes/numberFormat";
	
	return(XML(URL,"numero="+numero+"&decimal="+decimal));
}

function Solo_Numerico(variable)
{
	//patron = /^\d+(\.\d{1,2})?$/;
	patron 	= /^\d+\.?\d*$/;
	Numer	= variable;
	
	if(!patron.test(Numer)){
    	return "";
    }
	else
	{
     	return Numer;
    } 
}

function redondeo2decimales(numero)
{
    /*var original	=parseFloat(numero);
    var result		=Math.round(original*100)/100 ;
	
	return result;*/
	
	try
	{
		return numero.toFixed(2);
	}
	catch(e)
	{
		return "0";
	}
}

function OpenWindow(URL,Title,H,W,T,L)
{
	window.open(URL,Title,'top='+T+',left='+L+',height='+H+',width='+W+',fullscreen=no,toolbar=0,location=0,directories=0,status=0,menubar=0,resizable=0,scrolling=1,scrollbars=1','true');
}

function SelectTipo(Cadena)
{
	var BAND=false;
	
	switch(Cadena)
	{
		case "0": 
		BAND=true;
		break;
	}
	
	return (BAND);
}

function longitudCadena(cadena,tamano)
{
	if(cadena.length<tamano)
	{
		return false;
	}
	
	return true;
}

function camposVacios(cadena)
{
	if(cadena.length==0)
	{
		return false;
	}
	
	criterio = /^\s+$/;

	if(criterio.test(cadena))
	{
    	return false;
    }
	else
	{
		return true;
	} 
}

function validarEmail(email) 
{
	if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,4})+$/.test(email))
	{
		return true;
	} 
	else 
	{
		return false;
	}
}

function obtenerNumero(numero)
{
	criterio 	= /^\d+\.?\d*$/;
	numero		= parseFloat(numero);

	if(criterio.test(numero))
	{
    	return numero;
    }
	else
	{
		return 0;
	} 
}

function obtenerNumeros(numero)
{
	numero		= parseFloat(numero);

	if(!isNaN(numero))
	{
    	return numero;
    }
	else
	{
		return 0;
	} 
}

function valorMinimo(numero,valor)
{
	numero		= parseFloat(numero);

	if(!isNaN(numero))
	{
    	return numero;
    }
	else
	{
		return valor;
	} 
}

function menuSie(menu,submenu)
{
	/*$("#"+menu).addClass("active");
	$("#"+menu).removeAttr('onclick'); 
		
	window.setTimeout(function() 
	{
		$( "#"+menu).trigger( "click" );
	}, 2000);*/
	
	$("#"+submenu).addClass("active");

}


function menuActivo(menu,submenu)
{
	$("#menu-"+menu).addClass("activado");
	$("#"+menu).removeAttr('onclick'); 
	
	$("#"+submenu).addClass("activado");
	$("#"+submenu).removeAttr('onclick'); 
	
	/*$("#"+menu+'-submenu').fadeIn();
	$("#"+submenu).addClass("activado");
	$("#"+submenu).removeAttr('onclick'); */

}

function comprobarNumeros(numero)
{
	criterio 	= /^\d+\.?\d*$/;
	numero		= parseFloat(numero);
	
	if(!criterio.test(numero))
	{
    	return false;
    }
	else
	{
		return true;
	} 
}

function compararCantidades(numero1, numero2)
{
	try
	{
		if(!comprobarNumeros(numero1) || !comprobarNumeros(numero2)) return false; // Si ambos no son números
		
		numero1	=parseFloat(numero1); //El numero 1 siempre debe ser mayor
		numero2	=parseFloat(numero2);

		if(numero1<numero2) return false;
		if(numero1>=numero2) return true;
	}
	catch(e)
	{
		return false;
	}
}

function redondear(numero)
{
	try
	{	
		numero	= parseFloat(numero);
	
		return numero.toFixed(2);
	}
	catch(e )
	{
		return "0";
	}
}

function redondearDecimales(numero,decimales)
{
	try
	{	
		numero	= parseFloat(numero);
	
		return numero.toFixed(decimales);
	}
	catch(e )
	{
		return "0";
	}
}

function soloNumerico(evt)
{
	var charCode = (evt.which) ? evt.which : event.keyCode
	if (charCode > 31 && (charCode < 48 || charCode > 57))
	return false;
	 
	return true;
}

var nav4 = window.Event ? true : false;
function soloDecimales(evt)
{
	// NOTE: Backspace = 8, Enter = 13, '0' = 48, '9' = 57, '.' = 46
	var key = nav4 ? evt.which : evt.keyCode;
	return (key <= 13 || (key >= 48 && key <= 57) || key == 46);
}

function clasesConexion()
{
	$('#menuDesconectado').removeClass('desconectado')
	$('#menuDesconectado').addClass('conectado');
	
	$('#menuUsuarioRegistrado').removeClass('usuarioRegistrado')
	$('#menuUsuarioRegistrado').addClass('usuarioRegistrado');
	
	$('#menuConfiguracion').removeClass('configuracion')
	$('#menuConfiguracion').addClass('configuracion');
	
	$('#menuSalir').removeClass('salir')
	$('#menuSalir').addClass('salir');
	
	$('#menuEmail').removeClass('email')
	$('#menuEmail').addClass('email');
	
	$('#menuTutorial').removeClass('tutorial')
	$('#menuTutorial').addClass('tutorial');
	
	$('#menuManual').removeClass('manual')
	$('#menuManual').addClass('manual');
	
	$('#menuRecargar').removeClass('desconectado')
	//$('#menuRecargar').addClass('recargar');	
}

function clasesDesconexion()
{
	$('#menuDesconectado').removeClass('conectado')
	$('#menuDesconectado').addClass('desconectado');
	
	//$().enable();
	//$( "#menuRecargar" ).attr( "disabled", true );
	
	/*$( ".recargar" ).menu(
	{
		disabled: true
	});*/
	
	
	/*$('#menuUsuarioRegistrado').removeClass('usuarioRegistrado')
	$('#menuUsuarioRegistrado').addClass('usuarioRegistrado');
	
	$('#menuConfiguracion').removeClass('configuracion')
	$('#menuConfiguracion').addClass('configuracion');
	
	$('#menuSalir').removeClass('salir')
	$('#menuSalir').addClass('salir');
	
	$('#menuEmail').removeClass('email')
	$('#menuEmail').addClass('email');
	
	$('#menuTutorial').removeClass('tutorial')
	$('#menuTutorial').addClass('tutorial');
	
	$('#menuManual').removeClass('manual')
	$('#menuManual').addClass('manual');
	
	//$('#menuRecargar').removeClass('recargar')
	$('#menuRecargar').addClass('desconectado');	*/
}


function configurarTabsCliente(tab)
{
	$('.menuTabsCliente > li').removeClass('activado');
	$('#'+tab).addClass('activado');
	
	$('.divCliente').removeClass('visible');
	$('#div-'+tab).addClass('visible');
}

function agregarComas(nStr)
{
	nStr += '';
	x = nStr.split('.');
	x1 = x[0];
	x2 = x.length > 1 ? '.' + x[1] : '';
	var rgx = /(\d+)(\d{3})/;
	while (rgx.test(x1)) {
		x1 = x1.replace(rgx, '$1' + ',' + '$2');
	}
	return x1 + x2;
}

function sugerirHora()
{
	Hora	= new String($('#txtHoraCierre').val())
	hora	= Hora.split(':');
	HORA	= obtenerNumeros(hora[0]);
	MINUTO	= obtenerNumeros(hora[1]);

	if(MINUTO<55)
	{
		HORA	= HORA<10?'0'+HORA:HORA;
		MINUTO	= MINUTO==0?'05':MINUTO+5;
		
		$('#txtHoraCierreFin').val(HORA+':'+MINUTO);
		
		return;
	}
	
	if(MINUTO==55)
	{
		HORA++;
		HORA	= HORA<10?'0'+HORA:HORA;
		MINUTO	= "00";
		
		$('#txtHoraCierreFin').val(HORA+':'+MINUTO);
	}
	
	return;
}

function sugerirHoraProspecto()
{
	Hora	= new String($('#txtHoraInicioProspecto').val())
	hora	= Hora.split(':');
	HORA	= obtenerNumeros(hora[0]);
	MINUTO	= obtenerNumeros(hora[1]);

	if(MINUTO<55)
	{
		HORA	= HORA<10?'0'+HORA:HORA;
		MINUTO	= MINUTO==0?'05':MINUTO+5;
		
		$('#txtHoraFinProspecto').val(HORA+':'+MINUTO);
		
		return;
	}
	
	if(MINUTO==55)
	{
		HORA++;
		HORA	= HORA<10?'0'+HORA:HORA;
		MINUTO	= "00";
		
		$('#txtHoraFinProspecto').val(HORA+':'+MINUTO);
	}
	
	return;
}

function fo(){
}

//SIMULAR EVENTO DEL MOUSE
function simulate(element, eventName)
{
    var options = extend(defaultOptions, arguments[2] || {});
    var oEvent, eventType = null;

    for (var name in eventMatchers)
    {
        if (eventMatchers[name].test(eventName)) { eventType = name; break; }
    }

    if (!eventType)
        throw new SyntaxError('Only HTMLEvents and MouseEvents interfaces are supported');

    if (document.createEvent)
    {
        oEvent = document.createEvent(eventType);
        if (eventType == 'HTMLEvents')
        {
            oEvent.initEvent(eventName, options.bubbles, options.cancelable);
        }
        else
        {
            oEvent.initMouseEvent(eventName, options.bubbles, options.cancelable, document.defaultView,
            options.button, options.pointerX, options.pointerY, options.pointerX, options.pointerY,
            options.ctrlKey, options.altKey, options.shiftKey, options.metaKey, options.button, element);
        }
        element.dispatchEvent(oEvent);
    }
    else
    {
        options.clientX = options.pointerX;
        options.clientY = options.pointerY;
        var evt = document.createEventObject();
        oEvent = extend(evt, options);
        element.fireEvent('on' + eventName, oEvent);
    }
    return element;
}

function extend(destination, source) {
    for (var property in source)
      destination[property] = source[property];
    return destination;
}

var eventMatchers = {
    'HTMLEvents': /^(?:load|unload|abort|error|select|change|submit|reset|focus|blur|resize|scroll)$/,
    'MouseEvents': /^(?:click|dblclick|mouse(?:down|up|over|move|out))$/
}
var defaultOptions = {
    pointerX: 0,
    pointerY: 0,
    button: 0,
    ctrlKey: false,
    altKey: false,
    shiftKey: false,
    metaKey: false,
    bubbles: true,
    cancelable: true
}

//OTRO EVENTO

function simulatedClick(target, options) {

  var event = target.ownerDocument.createEvent('MouseEvents'),
      options = options || {},
      opts = { // These are the default values, set up for un-modified left clicks
        type: 'click',
        canBubble: true,
        cancelable: true,
        view: target.ownerDocument.defaultView,
        detail: 1,
        screenX: 0, //The coordinates within the entire page
        screenY: 0,
        clientX: 0, //The coordinates within the viewport
        clientY: 0,
        ctrlKey: false,
        altKey: false,
        shiftKey: false,
        metaKey: false, //I *think* 'meta' is 'Cmd/Apple' on Mac, and 'Windows key' on Win. Not sure, though!
        button: 0, //0 = left, 1 = middle, 2 = right
        relatedTarget: null,
      };

  //Merge the options with the defaults
  for (var key in options) {
    if (options.hasOwnProperty(key)) {
      opts[key] = options[key];
    }
  }

  //Pass in the options
  event.initMouseEvent(
      opts.type,
      opts.canBubble,
      opts.cancelable,
      opts.view,
      opts.detail,
      opts.screenX,
      opts.screenY,
      opts.clientX,
      opts.clientY,
      opts.ctrlKey,
      opts.altKey,
      opts.shiftKey,
      opts.metaKey,
      opts.button,
      opts.relatedTarget
  );

  //Fire the event
  target.dispatchEvent(event);
}

function sugerirSaltosCajasTexto() 
{
	var currentBoxNumber = 0;
	
	$(".cajas").keyup(function (event) 
	{
		if (event.keyCode == 13) 
		{
			//alert('Wichi');
			textboxes 			= $("input.cajas");
			currentBoxNumber 	= textboxes.index(this);
			//console.log(textboxes.index(this));
			if (textboxes[currentBoxNumber + 1] != null) {
				nextBox 		= textboxes[currentBoxNumber + 1];
				nextBox.focus();
				//nextBox.select();
				event.preventDefault();
				return false;
			}
		}
	});
}

function checarRegistrosGenerico(registros,chkGeneral,chkIndividual)
{
	
	for(i=0;i<registros;i++)
	{
		if(document.getElementById(chkGeneral).checked)
		{
			document.getElementById(chkIndividual+i).checked	= true;
			
			//$('#chkTodas').prop('checked',true);
		}
		
		if(!document.getElementById(chkGeneral).checked)
		{
			document.getElementById(chkIndividual+i).checked	= false;
			
			//$('#chkTodas').prop('checked',false);
		}
	}
}