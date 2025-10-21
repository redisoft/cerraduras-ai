//HORARIOS
$(document).ready(function()
{
	$("#ventanaListaNiveles1").dialog(
	{
		autoOpen:false,  
		show: { effect: "scale", duration: 600 },                            
		height:550,
		width:840,
		modal:true,
		resizable:false,
		buttons: 
		{
			'Aceptar': function() 
			{
				$('#ventanaListaNiveles1').dialog('close');
			}
		},
		close: function() 
		{
			if($('#txtRegistrosAfectados1').val()!="0")
			{
				obtenerNiveles1Catalogo()
			}
			
			$('#listaNiveles1').html('');
		}
	});
});

function listaNiveles1()
{
	$('#ventanaListaNiveles1').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#listaNiveles1').html('<img src="'+base_url+'img/ajax-loader.gif"/> Obteniendo registros');
		},
		type:"POST",
		url:base_url+'catalogos/listaNiveles1',
		data:
		{
			//idUsuario:idUsuario
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#listaNiveles1").html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener la lista de modelos',500,5000,'error',30,3);
			$("#listaNiveles1").html('');
		}
	});		
}

function obtenerNiveles1Catalogo()
{
	$('#obtenerNiveles1Catalogo').html('<select class="cajas" id="selectNivel1" name="selectNivel1" style="width:290px"><option value="0">Seleccione</option></select>')
	$('#obtenerNiveles2Catalogo').html('<select class="cajas" id="selectNivel2" name="selectNivel2" style="width:290px"><option value="0">Seleccione</option></select>')
	$('#obtenerNiveles3Catalogo').html('<select class="cajas" id="selectNivel3" name="selectNivel3" style="width:290px"><option value="0">Seleccione</option></select>')

	setTimeout(function()
	{
		$.ajax(
		{
			async:true,
			beforeSend:function(objeto)
			{
				$('#obtenerNiveles1Catalogo').html('<img src="'+base_url+'img/ajax-loader.gif"/> Obteniendo registros');
			},
			type:"POST",
			url:base_url+'catalogos/obtenerNiveles1Catalogo',
			data:
			{
				//idNivel1:$('#selectNivel1').val()
			},
			datatype:"html",
			success:function(data, textStatus)
			{
				$("#obtenerNiveles1Catalogo").html(data);
			},
			error:function(datos)
			{
				notify('Error al obtener la lista de modelos',500,5000,'error',30,3);
				$("#obtenerNiveles1Catalogo").html('');
			}
		});	
	},400);
}
