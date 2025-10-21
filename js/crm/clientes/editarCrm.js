function editarSeguimientoCrm()
{
	var mensaje	= "";
	status		= $('#selectStatus').val();
	estatus		= status.split('|');
	
	if($('#txtFechaEditar').val()=="")
	{
		mensaje+='Debe seleccionar una fecha<br /> ';
	}
	
	/*if($('#selectStatus').val()!="3")
	{
		if($('#txtComentariosEditar').val()=="")
		{
			mensaje+='Los comentarios son requeridos <br />';
		}
	}
	
	if($('#selectStatus').val()=="3")
	{
		if($('#txtBitacora').val()=="")
		{
			mensaje+='La bitácora es requerida<br />';
		}
	}*/
	
	if($('#selectContactos').val()=="0")
	{
		mensaje+='Seleccione el contacto<br />';
	}
	
	if(estatus[1]!="3")
	{
		if($('#txtComentarios').val()=="")
		{
			mensaje+='Los comentarios son requeridos <br />';
		}
	}
	
	if(estatus[1]=="3")
	{
		if($('#txtBitacora').val()=="")
		{
			mensaje+='La bitácora es requerida<br />';
		}
	}
	
	responsables	= new String($('#selectResponsable').val());
	responsable		= responsables.split("|");
	
	if(mensaje.length>0)
	{
		notify(mensaje,500,5000,'error',0,0);
		return;
	}
	
	if(confirm('¿Realmente desea editar el seguimiento CRM?')==false)
	{
		return;
	}
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#editandoCrm').html('<img src="'+ img_loader +'"/>Se esta editando el seguimiento CRM...');
		},
		type:"POST",
		url:base_url+"clientes/editarSeguimientoCrm",
		data:
		{
			"comentarios":		$("#txtComentarios").val(),
			"observaciones":	$("#txtObservacionesEditar").val(),
			"fecha":			$('#txtFechaEditar').val()+' '+$('#txtHoraSeguimiento').val(),
			"fechaCierre":		$('#txtFechaCierreEditar').val() + ' ' + $('#txtHoraCierre').val(),
			"lugar":			$('#txtLugarEditar').val(),
			"idCliente":		$('#txtIdCliente').val(),
			//"idStatus":			$('#selectStatus').val(),
			
			"idStatus":			estatus[0],
			"idStatusIgual":	estatus[1],
			
			"idServicio":		$('#selectServicio').val(),
			"idSeguimiento":	$('#txtIdSeguimiento').val(),
			"idResponsable":	responsable[0],
			"bitacora":			$("#txtBitacora").val(),
			"email":			$("#txtEmailSeguimiento").val(),
			"idTiempo":			$("#selectTiempo").val(),
			"idContacto":		$("#selectContactos").val(),
			
			"idCotizacion":		$("#txtIdCotizacionCrm").val(),
			"idVenta":			$("#txtIdVentaCrm").val(),
			
			"idEstatus":			$("#selectEstatus").val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			switch(data)
			{
				case "0":
					$('#editandoCrm').html('');
					notify('¡El seguimiento no tuvo cambios!',500,5000,'error',30,5);
				break;
				
				case "1":
					notify('¡Seguimiento editado!',500,5000,'',30,5);
					$('#editandoCrm').html('');
				break;
				
			}//switch
		},
		error:function(datos)
		{
			$('#editandoCrm').html('')
			notify('Error al editar el seguimiento',500,5000,'error',30,5);
		}
	});					  	  
}