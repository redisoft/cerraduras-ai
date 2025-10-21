tipo = "";

$(document).ready(function()
{
	$("#ventanaRegistrarProspecto").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:650,
		width:950,
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
				registrarProspecto()		  	  
			},
		},
		close: function() 
		{
			$("#formularioProspectos").html('');
		}
	});
});

function formularioProspectos()
{	
	if(ejecutar && ejecutar.readystate != 4)
	{
		ejecutar.abort();
	}
	
	$('#ventanaRegistrarProspecto').dialog('open');
	
	ejecutar=$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioProspectos').html('<img src="'+ img_loader +'"/> Obteniendo el formulario para prospectos...');
		},
		type:"POST",
		url:base_url+'clientes/formularioProspectos',
		data:
		{
			tipoRegistro: 'prospectos'
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#formularioProspectos').html(data);
		},
		error:function(datos)
		{
			$('#formularioProspectos').html('');
		}
	});		
}

function registrarProspecto()
{
	var mensaje	= "";
	prospecto	= 1;

	if(sistemaActivo=='IEXE')
	{
		if(!camposVacios($('#txtNombreAlumno').val())  ) // || !camposVacios($('#txtApellidoPaterno').val()) || !camposVacios($('#txtApellidoMaterno').val())
		{
			mensaje+='Los datos del prospecto son requeridos <br />';
		}
	}
	
	if(sistemaActivo!='IEXE')
	{
		if(!camposVacios($('#empresa').val()))
		{
			mensaje+='El nombre de la empresa es incorrecto <br />';
		}
	}

	if(!camposVacios($('#telefono').val()))
	{
		mensaje+='El teléfono es incorrecto <br />';
	}
	
	if($('#selectZonas').val()=="0")
	{
		mensaje+='Por favor seleccione '+$('#txtIdentificador').val()+' <br />';
	}
	
	if(parseInt($('#limiteCredito').val())<0)
	{
		mensaje+='Los días de crédito son incorrectos <br />';
	}
	
	if(mensaje.length>0)
	{
		notify(mensaje,500,5000,'error',30,5);
		return;
	}
	
	if(!confirm('¿Realmente desea registrar el prospecto?')) return;

	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#registrandoProspecto').html('<img src="'+ img_loader +'"/> Se esta registrando el prospecto, por favor espere...');
		},
		type:"POST",
		url:base_url+"clientes/registrarCliente",
		data: $('#frmClientes').serialize(),
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#registrandoProspecto').html('')
			
			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
					notify(data[1],500,5000,'error',30,5);
				break;
				
				case "1":
					notify('El prospecto se ha registrado correctamente',500,5000,'',30,5);
					$('#ventanaRegistrarProspecto').dialog('close');
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al registrar el prospecto',500,5000,'error',30,5);
			$('#registrandoProspecto').html('')
		}
	});	
}


