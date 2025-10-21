function notify(msg,speed,fadeSpeed,type,izquierda,arriba)
{
   //Borra cualquier mensaje existente
   $('.notify').remove();

   //Si el temporizador para hacer desaparecer el mensaje está
   //activo, lo desactivamos.
   if (typeof fade != "undefined")
   {
	   clearTimeout(fade);
   }

   //Creamos la notificación con la clase (type) y el texto (msg)
   $('body').append('<div class="notify '+type+'" style=" margin-left:'+izquierda+'%; margin-top: '+arriba+'%;display:none;position:fixed; z-index: 3004"><p>'+msg+'</p></div>');

   //Calculamos la altura de la notificación.
   notifyHeight = $('.notify').outerHeight();

   //Creamos la animación en la notificación con la velocidad
   //que pasamos por el parametro speed
   $('.notify').css('top',-notifyHeight).animate({top:10,opacity:'toggle'},speed);

   //Creamos el temporizador para hacer desaparecer la notificación
   //con el tiempo almacenado en el parametro fadeSpeed
   fade = setTimeout(function()
   {
	   $('.notify').animate({top:notifyHeight+10,opacity:'toggle'}, speed);

   }, fadeSpeed);

}

function notificacion(msg,speed,fadeSpeed,type,izquierda,arriba)
{
	//Borra cualquier mensaje existente
	$('.notificaciones').remove();
	
	//Si el temporizador para hacer desaparecer el mensaje está
	//activo, lo desactivamos.
	if (typeof fade != "undefined")
	{
		clearTimeout(fade);
	}
	
	//Creamos la notificación con la clase (type) y el texto (msg)
	$('body').append('<div class="notificaciones '+type+'" id="eventoNotificacion" style=" margin-left:'+izquierda+'%; margin-top: '+arriba+'%;display:none;position:fixed;"><p>'+msg+'</p></div>');
	
	//Calculamos la altura de la notificación.
	notifyHeight = $('.notificaciones').outerHeight();
	
	//Creamos la animación en la notificación con la velocidad
	//que pasamos por el parametro speed
	$('.notificaciones').css('bottom',-notifyHeight).animate({bottom:10,opacity:'toggle'},speed);
	
	//Creamos el temporizador para hacer desaparecer la notificación
	//con el tiempo almacenado en el parametro fadeSpeed
	
	//OCULTAR LA NOTIFICACIÓN DESPUES DE CIERTO TIEMPO
	/*fade = setTimeout(function()
	{
		$('.notificaciones').animate({left:notifyHeight+10,opacity:'toggle'}, speed);
	
	}, fadeSpeed);*/
}

function notificacionPago(msg,speed,fadeSpeed,type,izquierda,arriba)
{
	//Borra cualquier mensaje existente
	$('.notificacionesPago').remove();
	
	//Si el temporizador para hacer desaparecer el mensaje está
	//activo, lo desactivamos.
	if (typeof fade != "undefined")
	{
		clearTimeout(fade);
	}
	
	//Creamos la notificación con la clase (type) y el texto (msg)
	$('body').append('<div class="notificacionesPago '+type+'" id="eventoNotificacionPago" style=" margin-left:'+izquierda+'%; margin-top: '+arriba+'%;display:none;position:fixed;"><p>'+msg+'</p></div>');
	
	//Calculamos la altura de la notificación.
	notifyHeight = $('.notificacionesPago').outerHeight();
	
	//Creamos la animación en la notificación con la velocidad
	//que pasamos por el parametro speed
	$('.notificacionesPago').css('bottom',-notifyHeight).animate({bottom:10,opacity:'toggle'},speed);
}

function ocultarNotificacion()
{
	notifyHeight = $('.notificaciones').outerHeight();
	
	fade = setTimeout(function()
	{
		$('.notificaciones').animate({left:notifyHeight+10,opacity:'toggle'}, 500);
	
	}, 200);
}

function minimizarNotificacion(tiempo,criterio)
{
	if(criterio==1)
	{
		configurarNotificaciones('0');
	}
	
	$('.notificacionesPago').fadeOut();
	$('.notificaciones').fadeOut();
	/*notifyHeight = $('.notificaciones').outerHeight();
	$('#btnOcultarNotificacion').fadeOut();
	$('#btnMostrarNotificacion').fadeIn();
	
	$('#btnOcultarNotificacion2').fadeOut();
	$('#btnMostrarNotificacion2').fadeIn();
	
	fade = setTimeout(function()
	{
		$('.notificaciones').animate({height:30, bottom:30}, tiempo); 
		$('.notificacionesPago').animate({height:30, bottom:30}, tiempo); 
	
	}, tiempo);*/
}

function maximizarNotificacion()
{
	$('#btnMostrarNotificacion').fadeOut();
	$('#btnOcultarNotificacion').fadeIn();
	
	$('#btnMostrarNotificacion2').fadeOut();
	$('#btnOcultarNotificacion2').fadeIn();
	
	configurarNotificaciones('1');
	notifyHeight = $('.notificaciones').outerHeight();
	
	fade = setTimeout(function()
	{
		$('.notificaciones').animate({height:300, bottom:10}, 500);
		$('.notificacionesPago').animate({height:300, bottom:10}, 500);
	
	}, 200);
}

//Crea un mensaje de error
$('#detalles').click(function()
{
	notificacion('',500,5000,'detalles');
	return false;
});



//Crea un mensaje normal
$('#standard').click(function()
{
	notify('Texto de notificacion',500,4000);
	return false;
});

//Crea un mensaje de error
$('#error').click(function()
{
	notify('Error en la informacion',500,5000,'error');
	return false;
});
	
	