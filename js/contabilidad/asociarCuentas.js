
function agregarCuentaCatalogoAsociar(idCuenta,nombre)
{
	$('#txtIdCuentaCatalogo').val(idCuenta);
	$('#txtBuscarCuentaContable').val(nombre);
	$('#ventanaFormularioAsociarCuenta').dialog('close');
}

function formularioAsociarCuenta()
{
	$('#ventanaFormularioAsociarCuenta').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioAsociarCuenta').html('<label><img src="'+base_url+'img/ajax-loader.gif"/> Preparando el formulario para el catálogo...</label>');
		},
		type:"POST",
		url:base_url+'contabilidad/formularioAsociarCuenta',
		data:
		{
			grupo:	$('#txtGrupoActivo').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#formularioAsociarCuenta').html(data);
			obtenerCuentasCatalogoAsociar();
		},
		error:function(datos)
		{
			$('#formularioAsociarCuenta').html('');
			notify("Error al preparar el formulario para el catálogo",500,4000,"error"); 
		}
	});	
}

$(document).ready(function()
{
	$("#ventanaFormularioAsociarCuenta").dialog(
	{
		autoOpen:false,        
		show: { effect: "scale", duration: 600 },                      
		height:400,
		width:900,
		modal:true,
		resizable:false,
		buttons: 
		{
			'Cerrar': function() 
			{
				$(this).dialog('close');
			},
			
			/*'Registrar': function() 
			{
				registrarCatalogo();
			}*/
		},
		close: function() 
		{
			$('#formularioCatalogo').html('');
		}
	});
});

function obtenerCuentasRegistro()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerCuentasRegistro').html('<label><img src="'+base_url+'img/ajax-loader.gif"/> Obteniendo cuentas...</label>');
		},
		type:"POST",
		url:base_url+'contabilidad/obtenerCuentasRegistro',
		data:
		{
			grupo:	$('#selectGruposCuenta').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerCuentasRegistro').html(data);
			subCuentasReiniciar()
			obtenerCuentasCatalogoAsociar()
		},
		error:function(datos)
		{
			$('#obtenerCuentasRegistro').html('');
			notify("Error al obtener las cuentas",500,4000,"error"); 
		}
	});	
}

function subCuentasReiniciar()
{
	$('#obtenerSubCuentasRegistro').html('<select class="cajas" id="selectSubCuentasRegistro" name="selectSubCuentasRegistro" style="width:200px"><option value="0">Seleccione subcuenta</option></select>')
}

function obtenerSubCuentasRegistro()
{
	if($('#selectCuentasRegistro').val()=="0")
	{
		subCuentasReiniciar();	
		obtenerCuentasCatalogoAsociar();
		return;
	}
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerSubCuentasRegistro').html('<label><img src="'+base_url+'img/ajax-loader.gif"/> Obteniendo cuentas...</label>');
		},
		type:"POST",
		url:base_url+'contabilidad/obtenerSubCuentasRegistro',
		data:
		{
			idCuenta:	$('#selectCuentasRegistro').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerSubCuentasRegistro').html(data);
			obtenerCuentasCatalogoAsociar();
		},
		error:function(datos)
		{
			$('#obtenerSubCuentasRegistro').html('');
			notify("Error al obtener las cuentas",500,4000,"error"); 
		}
	});	
}

function obtenerCuentasCatalogoAsociar()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerCuentasCatalogoAsociar').html('<label><img src="'+base_url+'img/ajax-loader.gif"/> Obteniendo cuentas...</label>');
		},
		type:"POST",
		url:base_url+'contabilidad/obtenerCuentasCatalogoAsociar',
		data:
		{
			idCuenta:		$('#selectCuentasRegistro').val(),
			idSubCuenta:	$('#selectSubCuentasRegistro').val(),
			grupo:			$('#selectGruposCuenta').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerCuentasCatalogoAsociar').html(data)
		},
		error:function(datos)
		{
			$('#obtenerCuentasCatalogoAsociar').html('');
			notify("Error al obtener las cuentas",500,4000,"error"); 
		}
	});	
}
