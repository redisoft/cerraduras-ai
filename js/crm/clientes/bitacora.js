function enviarBitacora()
{
	var mensaje="";

	if($('#txtEmailSeguimiento').val()=="")
	{
		mensaje+='El email es incorrecto<br /> ';
	}

	responsables	= $('#selectResponsable').val();
	responsable	= responsables.split("|");
	
	if(mensaje.length>0)
	{
		notify(mensaje,500,5000,'error',0,0);
		return;
	}
	
	if(!confirm('¿Realmente desea enviar la bitácora?')) return;
	
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#enviandoBitacora').html('<img src="'+ img_loader +'"/>Enviando la bitácora...');
		},
		type:"POST",
		url:base_url+"clientes/enviarBitacora",
		data:
		{
			"fecha":			$('#txtFechaSeguimiento').val()+' '+$('#txtHoraSeguimiento').val(),
			"lugar":			$('#txtLugar').val(),
			"idCliente":		$('#txtIdCliente').val(),
			"idResponsable":	responsable[0],			
			"bitacora":			$("#txtBitacora").val(),
			"email":			$("#txtEmailSeguimiento").val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#enviandoBitacora').html('');
			
			switch(data)
			{
				case "0":
				notify('¡Error al enviar la bitácora!',500,5000,'error',30,5);
				break;
				
				case "1":
				notify('La bitácora se ha enviado correctamente',500,5000,'',30,5);
				break;
				
			}//switch
		},
		error:function(datos)
		{
			$('#enviandoBitacora').html('')
			notify('Error al enviar la bitácora',500,5000,'error',0,0);
		}
	});					  	  
}

function enviarBitacoraEditar()
{
	var mensaje="";

	if($('#txtEmailSeguimiento').val()=="")
	{
		mensaje+='El email es incorrecto<br /> ';
	}

	responsables	= $('#selectResponsable').val();
	responsable	= responsables.split("|");
	
	if(mensaje.length>0)
	{
		notify(mensaje,500,5000,'error',0,0);
		return;
	}
	
	if(!confirm('¿Realmente desea enviar la bitácora?')) return;
	
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#enviandoBitacora').html('<img src="'+ img_loader +'"/>Enviando la bitácora...');
		},
		type:"POST",
		url:base_url+"clientes/enviarBitacora",
		data:
		{
			"fecha":			$('#txtFechaEditar').val()+' '+$('#txtHoraSeguimiento').val(),
			"lugar":			$('#txtLugarEditar').val(),
			"idCliente":		$('#txtIdCliente').val(),
			"idResponsable":	responsable[0],			
			"bitacora":			$("#txtBitacora").val(),
			"email":			$("#txtEmailSeguimiento").val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#enviandoBitacora').html('');
			
			switch(data)
			{
				case "0":
				notify('¡Error al enviar la bitácora!',500,5000,'error',30,5);
				break;
				
				case "1":
				notify('La bitácora se ha enviado correctamente',500,5000,'',30,5);
				break;
				
			}//switch
		},
		error:function(datos)
		{
			$('#enviandoBitacora').html('')
			notify('Error al enviar la bitácora',500,5000,'error',0,0);
		}
	});					  	  
}