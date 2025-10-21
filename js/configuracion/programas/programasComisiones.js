//SERVICIOS
//=========================================================================================================================================//
function obtenerProgramas()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerProgramas').html('<img src="'+ img_loader +'"/> Obteniendo la lista de Programas.');
		},
		type:"POST",
		url:base_url+'configuracion/obtenerProgramasComisiones',
		data:
		{
			criterio: $('#txtBuscarPrograma').val()
		},
		datatype:"html",
		success:function(data, textProgramas)
		{
			$("#obtenerProgramas").html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener la lista de Programas',500,5000,'error',30,3);
			$("#obtenerProgramas").html('');
		}
	});
}

$(document).ready(function()
{
	$("#ventanaEditarProgramas").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:270,
		width:650,
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
				editarProgramas();		  	  
			},
		},
		close: function() 
		{
			$('#obtenerProgramasEditar').html('');
		}
	});
	
	$(document).on("click", ".ajax-pagProgramas > li a", function(eve)
	{
		eve.preventDefault();
		var element 	= "#obtenerProgramas";
		var link 		= $(this).attr('href');
		
		$.ajax(
		{
			url:link,
			type:"POST",
			data:
			{
				"criterio":	$('#txtBuscarPrograma').val(),
			},
			dataType:"html",
			beforeSend:function()
			{
				$('#obtenerProgramas').html('<img src="'+ img_loader +'"/>Obteniendo registros..');
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

function obtenerProgramasEditar(idPrograma)
{
	$('#ventanaEditarProgramas').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerServicio').html('<img src="'+ img_loader +'"/> Obteniendo los datos para editar el programa..');
		},
		type:"POST",
		url:base_url+'configuracion/obtenerProgramasComisionesEditar',
		data:
		{
			idPrograma:idPrograma
		},
		datatype:"html",
		success:function(data, textProgramas)
		{
			$("#obtenerProgramasEditar").html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener los datos para editar el programa',500,5000,'error',30,3);
			$("#obtenerProgramasEditar").html('');
		}
	});
}

function editarProgramas()
{
	mensaje="";
	
	/*if(!camposVacios($('#txtPrograma').val()))
	{
		mensaje+="El nombre del programa es incorrecto <br />";
	}*/

	if(mensaje.length>0)
	{
		notify(mensaje,500,3000,'error',30,3);
		return;
	}
	
	if(!confirm('¿Realmente desea editar el registro del programa?')) return
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#editandoProgramas').html('<img src="'+ img_loader +'"/> Editando el programa, por favor tenga paciencia..');
		},
		type:"POST",
		url:base_url+'configuracion/editarProgramasComisiones',
		data:
		{
			idPrograma: 	$('#txtIdPrograma').val(),
			importe: 		$('#txtImporte').val(),
			comision: 		$('#txtComision').val(),
		},
		datatype:"html",
		success:function(data, textProgramas)
		{
			$('#editandoProgramas').html('');
			
			switch(data)
			{
				case "0":
					notify('El registro no tuvo cambios',500,3000,'error',30,5);
				
				break;
				
				case "1":
					$('#ventanaEditarProgramas').dialog('close');
					notify('El Programa se ha editado correctamente',500,3000,'',30,5);
					obtenerProgramas();
					$('#txtProgramasEditado').val('1')
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al editar el programa',500,5000,'error',30,3);
			$("#editandoProgramas").html('');
		}
	});		
}
