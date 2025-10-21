$(document).ready(function()
{
	$("#ventanaEditarResponsable").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:250,
		width:800,
		modal:true,
		resizable:false,
		
		buttons: 
		{
			'Aceptar': function() 
			{
				editarResponsable()		 
			}
		},
		close: function()
		{
			$("#formularioEditarResponsable").html('');
		}
	});
})

function formularioEditarResponsable(idSeguimiento)
{
	$('#ventanaEditarResponsable').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioEditarResponsable').html('<img src="'+ img_loader +'"/>Cargando los detalles del seguimiento...');
		},
		type:"POST",
		url:base_url+'crm/formularioEditarResponsable',
		data:
		{
			"idSeguimiento":idSeguimiento,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#formularioEditarResponsable').html(data)
		},
		error:function(datos)
		{
			$('#formularioEditarResponsable').html('Error al obtener los detalles del seguimiento')
		}
	});		
}

function editarResponsable()
{
	if(!confirm('¿Realmente desea editar el registro?'))return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#editandoResponsable').html('<img src="'+ img_loader +'"/> Editando el registro, por favor espere...');
		},
		type:"POST",
		url:base_url+"crm/editarResponsable",
		data:$('#frmEditarResponsable').serialize(),
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#editandoResponsable').html('');
			data	= eval(data);
			
			notify('El registro se ha editado correctamente',500,3000,'',30,5);
			$('#ventanaEditarResponsable').dialog('close');
			obtenerLlamadas();
					
			/*switch(data[0])
			{
				case "0":
					notify('El registro no tuvo cambios',500,3000,'error',30,5);
				break;
				
				case "1":
					notify('El registro se ha editado correctamente',500,3000,'',30,5);
					$('#ventanaEditarResponsable').dialog('close');
					obtenerLlamadas();
				break;
				
			}//switch*/
		},
		error:function(datos)
		{
			$('#editandoResponsable').html('')
			notify('Error en el registro',500,5000,'error',0,0);
		}
	});					  	  
}