$(document).ready(function()
{
	$("#ventanaAcrilico").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:320,
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
				registrarAcrilico();	  	  
			},
		},
		close: function() 
		{
			$("#obtenerAcrilico").html('');
		}
	});
});


function obtenerAcrilico(idCotizacion)
{
	$('#ventanaAcrilico').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerAcrilico').html('<img src="'+ img_loader +'"/>Obteniendo el formulario...');
		},
		type:"POST",
		url:base_url+'ventas/obtenerAcrilico',
		data:
		{
			"idCotizacion":	idCotizacion,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerAcrilico').html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener el formulario',500,5000,'error',2,5);
			$("#obtenerAcrilico").html('');
		}
	});
}

function registrarAcrilico()
{
	if(!confirm('¿Realmente desea continuar con el registro?')) return;

	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#registrandoAcrilico').html('<img src="'+ img_loader +'"/> Procesando registro...');
		},
		type:"POST",
		url:base_url+"ventas/registrarAcrilico",
		data:$('#frmAcrilico').serialize(),
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#registrandoAcrilico').html('');
			
			data	=eval(data);
			
			switch(data[0])
			{
				case "0":
					notify(data[1],500,5000,'error',30,5);
				break;
				
				case "1":
					
					$('#ventanaAcrilico').dialog('close');
					
					notify('El registro ha sido correcto',500,5000,'',30,5);
					obtenerVentas();
				break;
			}//switch
		},
		error:function(datos)
		{
			notify('Error en el registro',500,5000,'error',30,5);
			$('#registrandoAcrilico').html('');
		}
	});		
}


