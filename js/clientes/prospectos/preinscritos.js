$(document).ready(function()
{
	$('#txtBusquedaPreinscritos').keypress(function(e)
	 {
		if(e.which == 13) 
		{
			obtenerPreinscritos();
		}
	});
	
	$("#ventanaPreinscritos").dialog(
	{
		autoOpen:false,
		height:650,
		width:1250,
		modal:true,
		resizable:false,
		buttons: 
		{
			Aceptar: function() 
			{
				$(this).dialog('close');				 
			},
		},
		close: function() 
		{
			//$("#obtenerPreinscritos").html('');
		}
	});
	
	$(document).on("click", ".ajax-pagPreinscritos > li a", function(eve)
	{
		eve.preventDefault();
		var element 	= "#obtenerPreinscritos";
		var link 		= $(this).attr('href');
		
		$.ajax(
		{
			url:link,
			type:"POST",
			data:
			{
				inicio:					$('#txtFechaInicialPreinscritos').val(),
				fin: 					$('#txtFechaFinalPreinscritos').val(),
				idUsuario: 				$('#selectPromotoresPreinscritos').val(),
				criterio: 				$('#txtBusquedaPreinscritos').val(),
				idPrograma: 			$('#selectProgramasPreinscritos').val(),
				idCampana: 				$('#selectCampanasPreinscritos').val(),
				idFuente: 				$('#selectFuentesPreinscritos').val(),
				
				idCampanaOriginal: 		$('#selectCampanasOriginalPreinscritos').val(),
				mes: 					$('#selectMesPreinscrito').val(),
			},
			dataType:"html",
			beforeSend:function()
			{
				$('#obtenerPreinscritos').html('<img src="'+ img_loader +'"/>Obteniendo reporte..');
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

function obtenerPreinscritos()
{
	$('#ventanaPreinscritos').dialog('open');

	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerPreinscritos').html('<img src="'+ img_loader +'"/> Obteniendo reporte...');
		},
		type:"POST",
		url:base_url+'crm/obtenerPreinscritos',
		data:
		{
			inicio:					$('#txtFechaInicialPreinscritos').val(),
			fin: 					$('#txtFechaFinalPreinscritos').val(),
			idUsuario: 				$('#selectPromotoresPreinscritos').val(),
			criterio: 				$('#txtBusquedaPreinscritos').val(),
			idPrograma: 			$('#selectProgramasPreinscritos').val(),
			idCampana: 				$('#selectCampanasPreinscritos').val(),
			idFuente: 				$('#selectFuentesPreinscritos').val(),
			idCampanaOriginal: 		$('#selectCampanasOriginalPreinscritos').val(),
			mes: 					$('#selectMesPreinscrito').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerPreinscritos').html(data);
		},
		error:function(datos)
		{
			$('#obtenerPreinscritos').html('');
		}
	});		
}

function excelPreinscritos()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#procesandoPreinscritos').html('<img src="'+ img_loader +'"/> Se esta generando el reporte en excel...');},
		type:"POST",
		url:base_url+'crm/excelPreinscritos',
		data:
		{
			inicio:					$('#txtFechaInicialPreinscritos').val(),
			fin: 					$('#txtFechaFinalPreinscritos').val(),
			idUsuario: 				$('#selectPromotoresPreinscritos').val(),
			criterio: 				$('#txtBusquedaPreinscritos').val(),
			idPrograma: 			$('#selectProgramasPreinscritos').val(),
			idCampana: 				$('#selectCampanasPreinscritos').val(),
			idFuente: 				$('#selectFuentesPreinscritos').val(),
			idCampanaOriginal: 		$('#selectCampanasOriginalPreinscritos').val(),
			mes: 					$('#selectMesPreinscrito').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#procesandoPreinscritos').html('');
			
			window.location.href=base_url+'reportes/descargarExcelReportes/'+data+'/Preinscritos';
			notify('El excel se ha creado correctamente',500,4000,"error");
		},
		error:function(datos)
		{
			$("#procesandoPreinscritos").html('');
		}
	});//Ajax		
}

function validarProspecto(idCliente)
{
	var mensaje="";

	if(!confirm('¿Realmente desea validar el registro?')) return;

	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#procesandoPreinscritos').html('<img src="'+ img_loader +'"/>Se esta validando el registro...');
		},
		type:"POST",
		url:base_url+"crm/validarProspecto",
		data:
		{
			"idCliente":			idCliente,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#procesandoPreinscritos').html('');
			
			switch(data)
			{
				case "0":
					notify('¡Sin cambios en el registro!',500,5000,'error',30,5);
				break;
				
				case "1":
					notify('El registro fue validado correctamente',500,5000,'',30,5);
					obtenerPreinscritos()
				break;
				
			}//switch
		},
		error:function(datos)
		{
			$('#procesandoPreinscritos').html('')
			notify('Error al validar el registro',500,5000,'error',0,0);
		}
	});					  	  
}

function borrarPreinscrito(idCliente)
{
	var mensaje="";

	if(!confirm('¿Realmente desea borrar el registro?')) return;

	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#procesandoPreinscritos').html('<img src="'+ img_loader +'"/>Se esta validando el registro...');
		},
		type:"POST",
		url:base_url+"crm/borrarPreinscrito",
		data:
		{
			"idCliente":			idCliente,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#procesandoPreinscritos').html('');
			
			switch(data)
			{
				case "0":
					notify('¡Sin cambios en el registro!',500,5000,'error',30,5);
				break;
				
				case "1":
					notify('El registro fue borrado correctamente',500,5000,'',30,5);
					obtenerPreinscritos()
				break;
				
			}//switch
		},
		error:function(datos)
		{
			$('#procesandoPreinscritos').html('')
			notify('Error al borrar el registro',500,5000,'error',0,0);
		}
	});					  	  
}



