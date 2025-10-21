//var base_url="localhost:81/sanvalentin/";
var base_url="produccion.gorilanet.com/";

var img_loader=base_url+"img/ajax-loader.gif";

function enviarCorreo()
{
	if($('#mail').val()=="")
	{
		alert('Es necesario que escriba su direccion de correo electronico');
		return;
	}
	
	var links=base_url+"login/enviarConfirmacion";
	$("#confirmando").fadeIn(); 
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#confirmando').html('<img src="'+ img_loader +'"/> Se esta actualizando su password, tenga paciencia...');
		},
		type:"POST",
		url:links,
		data:
		{
			"correo":$("#mail").val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			switch(data)
			{
				case "1":
				$('#confirmando').html('<img src="'+ img_loader +'"/> Revise su correo recuperar su contraseña...');
				alert('Se ha enviado un link a su correo para actualizar su contraseña');
				window.setTimeout("redireccionar();",2000);
				break;
				case "0":
				alert('Error al solicitar el cambio de contraseña, contacte con el administrador');
				$("#confirmando").fadeOut(); 
				break;
				
				case "2":
				alert('El correo electronico no existe o es invalido');
				$("#confirmando").fadeOut(); 
				break;
			}
		},
		error:function(datos)
		{
			$("#confirmando").fadeOut(); 
			//$("#registroError").html(datos);	
		}
	});//Ajax
}

function recuperarPassword()
{
	if($('#password').val()=="")
	{
		alert('Por favor escriba el password');
		return;
	}
	
	if($('#confirmarPassword').val()=="")
	{
		alert('Las password no coinciden');
		return;
	}
	
	if($('#confirmarPassword').val()!=$('#password').val())
	{
		alert('Las password no coinciden');
		return;
	}
	
	//return;
	
	var links=base_url+"login/confirmarPassword";
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#confirmando').html('<img src="'+ img_loader +'"/> Se esta actualizando su password, tenga paciencia...');
		},
		type:"POST",
		url:links,
		data:
		{
			"password":$("#password").val(),
			"usuario":$("#usuario").val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			switch(data)
			{
				case "1":
				$('#confirmando').html('<img src="'+ img_loader +'"/> Contraseña cambiada correctamente, sera redirigido al login...');
				
				window.setTimeout("redireccionar();",2000);
				break;
				case "0":
				$("#confirmando").fadeOut(); 
				break;
			}
			
			//$('#RESPUESTA').html(data);					   
		},
		error:function(datos)
		{
			$("#confirmando").fadeOut(); 
			//$("#registroError").html(datos);	
		}
	});//Ajax
}

	
	function redireccionar()
	{
		window.location.href=base_url+"login";
	}
