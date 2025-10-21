function accesoInstalacion()
{
	/*codigo 		= $('#txtPassword').val()
	usuarioZm 	= $('#txtUsuario').val()
	
	if(codigo==null || usuarioZm==null)return;
	
	if(codigo.length==0 || usuarioZm.length==0)
	{
		notify('El código y/o usuario son incorrectos',500,5000,'error',30,3);
		return false;
	}
	
	codigo	= hex_sha1(codigo);
	
	if(codigo!=instalacion || usuarioZm!=usuarioInstalacion)
	{
		notify('El código y/o usuario son incorrectos',500,5000,'error',30,5);
		return;
	}*/
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			//$('#registrandoCliente').html('<img src="'+base_url+'img/loader.gif"/> '+esperar);
		},
		type:"POST",
		url:base_url+"instalacion/registrarCookieInicial",
		data:$('#frmInstalacion').serialize(),
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#registrandoCliente').html('');
			data=eval(data);
			
			switch(data[0])
			{
				case "0":
					alert(data[1]);
				break;
				
				case "1":
					location.href=base_url+'instalacion/registroEstacion';
				break;
			}
		},
		error:function(datos)
		{
			
		}
	});	
}

function obtenerEstaciones()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			//$('#registrandoCliente').html('<img src="'+base_url+'img/loader.gif"/> '+esperar);
		},
		type:"POST",
		url:base_url+"instalacion/obtenerEstaciones",
		data:
		{
			idLicencia:	$('#selectSucursal').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerEstaciones').html(data);
		},
		error:function(datos)
		{
			
		}
	});	
}

function registrarEstacion()
{
	if(!confirm('¿Realmente desea registrar la estación?')) return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			//$('#registrandoCliente').html('<img src="'+base_url+'img/loader.gif"/> '+esperar);
		},
		type:"POST",
		url:base_url+"instalacion/registrarEstacion",
		data:$('#frmInstalacion').serialize(),
		datatype:"html",
		success:function(data, textStatus)
		{
			data=eval(data);
			
			
			switch(data[0])
			{
				case "0":
					alert('Error al registrar la estacion');
				break;
				
				case "1":
					location.href=base_url+'login';
				break;
			}
		},
		error:function(datos)
		{
			
		}
	});	
}
