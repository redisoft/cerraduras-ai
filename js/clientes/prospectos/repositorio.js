$(document).ready(function()
{
	$('#txtBuscarProspecto').keypress(function(e)
	 {
		if(e.which == 13) 
		{
			obtenerReporte();
		}
	});
	
	$('#txtInicio,#txtFin').datepicker();
	
	obtenerReporte()
	
	$(document).on("click", ".ajax-pagReporte > li a", function(eve)
	{
		eve.preventDefault();
		var element 	= "#obtenerReporte";
		var link 		= $(this).attr('href');
		
		$.ajax(
		{
			url:link,
			type:"POST",
			data:
			{
				criterio: 			$('#txtBuscarProspecto').val(),
				inicio: 			$('#txtInicio').val(),
				fin: 				$('#txtFin').val(),
			},
			dataType:"html",
			beforeSend:function()
			{
				$('#obtenerReporte').html('<img src="'+ img_loader +'"/>Obteniendo reporte..');
			},
			success:function(html,textStatus)
			{
				setTimeout(function()
				{
					$(element).html(html);},300);
				},
				error:function(datos){$(element).html('Error '+ datos).show('slow');
			}
		});
	});
});

function obtenerReporte()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerReporte').html('<img src="'+ img_loader +'"/> Obteniendo reporte...');
		},
		type:"POST",
		url:base_url+'crm/obtenerRepositorio',
		data:
		{
			criterio: 			$('#txtBuscarProspecto').val(),
			inicio: 			$('#txtInicio').val(),
			fin: 				$('#txtFin').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerReporte').html(data);
		},
		error:function(datos)
		{
			$('#obtenerReporte').html('');
		}
	});		
}

function excelReporte()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#exportandoReporte').html('<img src="'+ img_loader +'"/> Se esta generando el reporte en excel...');},
		type:"POST",
		url:base_url+'crm/excelRepositorio',
		data:
		{
			criterio: 			$('#txtBuscarProspecto').val(),
			inicio: 			$('#txtInicio').val(),
			fin: 				$('#txtFin').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#exportandoReporte').html('');
			
			window.location.href=base_url+'reportes/descargarExcelReportes/'+data+'/Educaedu';
			notify('El excel se ha creado correctamente',500,4000,"error");
		},
		error:function(datos)
		{
			$("#exportandoReporte").html('');
		}
	});//Ajax		
}



$(document).ready(function()
{
	$("#ventanaAsignarPromotor").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:400,
		width:800,
		modal:true,
		resizable:false,
		
		buttons: 
		{
			'Aceptar': function() 
			{
				asignarPromotor();				 
			}
		},
		close: function()
		{
			$("#detallesSeguimiento").html('');
		}
	});
})

function formularioAsignarPromotor(idAlumno)
{
	$('#ventanaAsignarPromotor').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioAsignarPromotor').html('<img src="'+ img_loader +'"/>Cargando los detalles del seguimiento...');
		},
		type:"POST",
		url:base_url+'crm/formularioAsignarPromotor',
		data:
		{
			"idAlumno":idAlumno,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#formularioAsignarPromotor').html(data)
		},
		error:function(datos)
		{
			$('#formularioAsignarPromotor').html('Error al obtener los detalles del seguimiento')
		}
	});		
}

function asignarPromotor()
{
	var mensaje="";

	if(mensaje.length>0)
	{
		notify(mensaje,500,5000,'error',0,0);
		return;
	}
	
	if(!confirm('¿Realmente desea continuar con el registro?'))return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#asignandoPromotor').html('<img src="'+ img_loader +'"/> Registrando...');
		},
		type:"POST",
		url:base_url+"crm/asignarPromotor",
		data:$('#frmAlumno').serialize(),
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#asignandoPromotor').html('');
			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
					notify(data[1],500,3000,'error',30,5);
				break;
				
				case "1":
					notify('¡Registro correcto!',500,3000,'',30,5);
					$('#ventanaAsignarPromotor').dialog('close');
					
					obtenerReporte();
				break;
				
			}//switch
		},
		error:function(datos)
		{
			$('#asignandoPromotor').html('')
			notify('Error en el registro',500,5000,'error',0,0);
		}
	});					  	  
}
