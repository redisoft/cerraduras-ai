//SERVICIOS
//=========================================================================================================================================//
function obtenerComisiones()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerComisiones').html('<img src="'+ img_loader +'"/> Obteniendo la lista de Comisiones.');
		},
		type:"POST",
		url:base_url+'configuracion/obtenerComisiones',
		data:
		{
			criterio: 	$('#txtBuscarComision').val(),
			idPromotor: $('#selectPromotoresComisiones').val(),
			idCampana: 	$('#selectCampanasComisiones').val(),
			idPrograma: $('#selectProgramasComisiones').val(),
		},
		datatype:"html",
		success:function(data, textComisiones)
		{
			$("#obtenerComisiones").html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener la lista de Comisiones',500,5000,'error',30,3);
			$("#obtenerComisiones").html('');
		}
	});
}

$(document).ready(function()
{
	$(document).on("click", ".ajax-pagComisiones > li a", function(eve)
	{
		eve.preventDefault();
		var element 	= "#obtenerComisiones";
		var link 		= $(this).attr('href');
		
		$.ajax(
		{
			url:link,
			type:"POST",
			data:
			{
				criterio: 	$('#txtBuscarComision').val(),
				idPromotor: $('#selectPromotoresComisiones').val(),
				idCampana: 	$('#selectCampanasComisiones').val(),
				idPrograma: $('#selectProgramasComisiones').val(),
			},
			dataType:"html",
			beforeSend:function()
			{
				$('#obtenerComisiones').html('<img src="'+ img_loader +'"/>Obteniendo registros..');
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

function excelComisiones()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#procesandoComisiones').html('<img src="'+ img_loader +'"/> Se esta generando el reporte en excel...');},
		type:"POST",
		url:base_url+'crm/excelComisiones',
		data:
		{
			criterio: 	$('#txtBuscarComision').val(),
			idPromotor: $('#selectPromotoresComisiones').val(),
			idCampana: 	$('#selectCampanasComisiones').val(),
			idPrograma: $('#selectProgramasComisiones').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#procesandoComisiones').html('');
			
			window.location.href=base_url+'reportes/descargarExcelReportes/'+data+'/Comisiones';
			notify('El excel se ha creado correctamente',500,4000,"error");
		},
		error:function(datos)
		{
			$("#procesandoComisiones").html('');
		}
	});//Ajax		
}


$(document).ready(function()
{
	$("#ventanaEditarComision").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:350,
		width:900,
		modal:true,
		resizable:false,
		
		buttons: 
		{
			'Aceptar': function() 
			{
				editarComision()
			}
		},
		close: function()
		{
			$("#formularioEditarComision").html('');
		}
	});
})

function formularioEditarComision(idCliente)
{
	$('#ventanaEditarComision').dialog('open');

	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioEditarComision').html('<img src="'+ img_loader +'"/> Obteniendo detalles de registro...');
		},
		type:"POST",
		url:base_url+'crm/formularioEditarComision',
		data:
		{
			idCliente: idCliente
		},
		datatype:"html",
		success:function(data, textComisiones)
		{
			$('#formularioEditarComision').html(data)
		},
		error:function(datos)
		{
			$('#formularioEditarComision').html('');
			notify('Error al obtener el registro',500,5000,'error',30,5);
		}
	});
}

function editarComision()
{
	var mensaje	= "";

	if(obtenerNumeros($("#txtVenta").val()) ==0 )
	{
		notify('Por favor escriba el monto de la venta',500,5000,'error',30,5);
		return;
	}
	
	if(!confirm('¿Realmente desea editar el registro?')) return;

	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#editandoComisiones').html('<img src="'+ img_loader +'"/>Procesando registro...');
		},
		type:"POST",
		url:base_url+"crm/editarComision",
		data:
		{

			"idPrograma":			$("#selectProgramas").val(),
			"idCampana":			$("#selectCampanas").val(),
			"venta":				$("#txtVenta").val(),
			"idCliente":			$("#txtIdClienteProspecto").val(),
			"idPromotor":			$("#txtIdPromotor").val(),
			"idVenta":				$("#txtIdVentaProspecto").val(),
			
			"inscripcion":				$("#txtInscripcion").val(),
			"colegiatura":				$("#txtColegiatura").val(),
			"reinscripcion":			$("#txtReinscripcion").val(),
			"titulacion":				$("#txtTitulacion").val(),
			"cantidadInscripcion":		$("#txtCantidadInscripcion").val(),
			"cantidadColegiatura":		$("#txtCantidadColegiatura").val(),
			"cantidadReinscripcion":	$("#txtCantidadReinscripcion").val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#editandoComisiones').html('');
			
			switch(data)
			{
				case "0":
					notify('¡Sin cambios en el registro!',500,5000,'error',30,5);
				break;
				
				case "1":
					notify('Se registro exitósamente',500,5000,'',30,5);
					
					$('#ventanaEditarComision').dialog('close');
					
					obtenerComisiones()
				break;
				
			}//switch
		},
		error:function(datos)
		{
			$('#editandoComisiones').html('')
			notify('Error en el registro',500,5000,'error',0,0);
		}
	});					  	  
}

function calcularTotalesAcademicosProspecto()
{
	totalAcademico=0;
	
	inscripcion		= obtenerNumeros($('#txtInscripcion').val())
	cantidad		= obtenerNumeros($('#txtCantidadInscripcion').val())	
	importe			= cantidad*inscripcion
	totalAcademico +=importe;

	
	colegiatura		= obtenerNumeros($('#txtColegiatura').val())
	cantidad		= obtenerNumeros($('#txtCantidadColegiatura').val())	
	importe			= cantidad*colegiatura
	totalAcademico +=importe;
	
	reinscripcion	= obtenerNumeros($('#txtReinscripcion').val())
	cantidad		= obtenerNumeros($('#txtCantidadReinscripcion').val())	
	importe			= cantidad*reinscripcion
	totalAcademico 	+=importe;

	$('#txtVenta').val((redondear(totalAcademico)))
}