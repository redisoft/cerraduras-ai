
function formularioBajas(idCliente)
{
	$('#ventanaFormularioBajas').dialog('open');

	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioBajas').html('<img src="'+ img_loader +'"/> Espere...');
		},
		type:"POST",
		url:base_url+'crm/formularioBajas',
		data:
		{
			"idCliente":	idCliente,
			"tipo":			$("#txtTipoBajas").val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#formularioBajas').html(data);
		},
		error:function(datos)
		{
			$('#formularioBajas').html('');
		}
	});		
}

$(document).ready(function()
{
	$("#ventanaFormularioBajas").dialog(
	{
		autoOpen:false,
		height:250,
		width:600,
		modal:true,
		resizable:false,
		buttons: 
		{
			Cancelar: function() 
			{
				$(this).dialog('close');				 
			},
			'Aceptar': function() 
			{
				registrarBaja();	  	  
			},
		},
		close: function() 
		{
			$("#cargarClientes").html('');
		}
	});
});

function registrarBaja()
{
	if($('#selectCausas').val()=="0")
	{
		notify('Seleccione la causa',500,3000,'error',30,3);
		return;
	}
	
	if(!confirm('¿Realmente desea dar de baja el registo?')) return;

	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#registrandoBaja').html('<img src="'+ img_loader +'"/> Procesando, por favor tenga paciencia..');
		},
		type:"POST",
		url:base_url+'crm/registrarBaja',
		data:$('#frmBajas').serialize(),
		datatype:"html",
		success:function(data, textCausas)
		{
			$('#registrandoBaja').html('');
			
			switch(data)
			{
				case "0":
					notify('El registro no tuvo cambios',500,3000,'error',30,5);
				
				break;
				
				case "1":
					$('#ventanaFormularioBajas').dialog('close');
					notify('El registro fue dado de baja correctamente',500,3000,'',30,5);
					
					if($("#txtTipoBajas").val()=="0")
					{
						obtenerClientes();
					}
					else
					{
						obtenerLlamadas();
					}
					
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al editar el causa',500,5000,'error',30,3);
			$("#registrandoBaja").html('');
		}
	});		
}


