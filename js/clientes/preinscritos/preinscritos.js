$(document).ready(function()
{
	$('#txtBusquedasPreinscritos').keypress(function(e)
	 {
		if(e.which == 13) 
		{
			obtenerPreinscritos();
		}
	});

	
	obtenerPreinscritos()
	
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
				criterio:			$('#txtBusquedasPreinscritos').val(),
				idPromotor:			$('#selectPromotorBusqueda').val(),
				tipoRegistro: 		$('#txtTipoRegistro').val(),
				criterioSeccion:	$('#txtCriterioSeccion').val(),
				idCampana:			$('#selectCampanasBusqueda').val(),
				idPrograma:			$('#selectProgramaBusqueda').val(),
				inicio:				$('#txtFechaInicio').val(),
				fin:				$('#txtFechaFin').val(),
				matricula:			$('#selectMatriculaBusqueda').val(),
				
				mes:				$('#selectMesBusqueda').val(),
				idPeriodo:			$('#selectPeriodosBusqueda').val(),
			},
			dataType:"html",
			beforeSend:function()
			{
				$('#obtenerPreinscritos').html('<img src="'+ img_loader +'"/>Obteniendo registros..');
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
	if(ejecutar && ejecutar.readystate != 4)
	{
		ejecutar.abort();
	}

	ejecutar=$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerPreinscritos').html('<img src="'+ img_loader +'"/> Obteniendo los registros...');
		},
		type:"POST",
		url:base_url+"clientes/obtenerPreinscritos",
		data:
		{
			criterio:			$('#txtBusquedasPreinscritos').val(),
			idPromotor:			$('#selectPromotorBusqueda').val(),
			tipoRegistro: 		$('#txtTipoRegistro').val(),
			criterioSeccion:	$('#txtCriterioSeccion').val(),
			idCampana:			$('#selectCampanasBusqueda').val(),
			idPrograma:			$('#selectProgramaBusqueda').val(),
			inicio:				$('#txtFechaInicio').val(),
			fin:				$('#txtFechaFin').val(),
			
			matricula:			$('#selectMatriculaBusqueda').val(),
			mes:				$('#selectMesBusqueda').val(),
			idPeriodo:			$('#selectPeriodosBusqueda').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerPreinscritos').html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener los registros ',500,4000,"error");
			$("#obtenerPreinscritos").html('');	
		}
	});				
}

function excelPreinscritosClientes()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#exportandoReporte').html('<img src="'+ img_loader +'"/> Se esta generando el reporte en excel...');},
		type:"POST",
		url:base_url+'clientes/excelPreinscritos',
		data:
		{
			criterio:			$('#txtBusquedasPreinscritos').val(),
			idPromotor:			$('#selectPromotorBusqueda').val(),
			tipoRegistro: 		$('#txtTipoRegistro').val(),
			criterioSeccion:	$('#txtCriterioSeccion').val(),
			idCampana:			$('#selectCampanasBusqueda').val(),
			idPrograma:			$('#selectProgramaBusqueda').val(),
			inicio:				$('#txtFechaInicio').val(),
			fin:				$('#txtFechaFin').val(),
			
			matricula:			$('#selectMatriculaBusqueda').val(),
			mes:				$('#selectMesBusqueda').val(),
			idPeriodo:			$('#selectPeriodosBusqueda').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#exportandoReporte').html('');
			
			window.location.href=base_url+'reportes/descargarExcelReportes/'+data+'/Preinscritos';
			notify('El excel se ha creado correctamente',500,4000,"error");
		},
		error:function(datos)
		{
			$("#exportandoReporte").html('');
		}
	});//Ajax		
}

//MATRÍCULA
//FICHEROS
$(document).ready(function()
{
	$("#ventanaMatricula").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:250,
		width:700,
		modal:true,
		resizable:false,
		
		buttons: 
		{
			'Aceptar': function() 
			{
				registrarMatricula()			 
			},
		},
		close: function()
		{
			$("#formularioMatricula").html('');
		}
	});
});

function formularioMatricula(idCliente)
{
	$('#ventanaMatricula').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioMatricula').html('<img src="'+ img_loader +'"/> Obteniendo ficheros, por favor espere...');
		},
		type:"POST",
		url:base_url+'crm/formularioMatricula',
		data:
		{
			"idCliente":idCliente,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#formularioMatricula').html(data);
		},
		error:function(datos)
		{
			$('#formularioMatricula').html('');
			notify('Error al obtener los ficheros',500,5000,'error',0,0);
		}
	});		
}

function registrarMatricula()
{
	if(!camposVacios($('#txtMatricula').val()))
	{
		notify('La matrícula es incorrecta',500,5000,'error',30,5);
		return;	
	}
	
	if(!confirm('¿Realmente desea continuar con el registro?'))return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#registrandoMatricula').html('<img src="'+ img_loader +'"/> Registrando matrícula, por favor espere...');
		},
		type:"POST",
		url:base_url+'crm/registrarMatricula',
		data: $('#frmMatricula').serialize(),
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#registrandoMatricula').html('')
			
			switch(data)
			{
				case "0":
					notify('¡Error en el registro!',500,5000,'error',30,5);
				break;
				
				case "1":
					obtenerPreinscritos()
					notify('¡El registro fue exitoso!',500,5000,'',30,5);
					$('#ventanaMatricula').dialog('close');
				break;
				
			}//switch
		},
		error:function(datos)
		{
			$('#registrandoMatricula').html('');
			notify('¡Error en el registro!',500,5000,'error',30,5);
		}
	});		
}