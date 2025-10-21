//HORARIOS
$(document).ready(function()
{
	$("#ventanaListaNiveles3").dialog(
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
				$('#ventanaListaNiveles3').dialog('close');
			}
		},
		close: function() 
		{
			if($('#txtRegistrosAfectados3').val()!="0")
			{
				obtenerNiveles3Catalogo()
			}
			
			$('#listaNiveles3').html('');
		}
	});
});

function listaNiveles3()
{
	$('#ventanaListaNiveles3').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#listaNiveles3').html('<img src="'+base_url+'img/ajax-loader.gif"/> Obteniendo registros');
		},
		type:"POST",
		url:base_url+'catalogos/listaNiveles3',
		data:
		{
			//idUsuario:idUsuario
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#listaNiveles3").html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener la lista de modelos',500,5000,'error',30,3);
			$("#listaNiveles3").html('');
		}
	});		
}

function obtenerNiveles3Catalogo()
{
	$('#obtenerNiveles3Catalogo').html('<select class="cajas" id="selectNivel3" name="selectNivel3" style="width:290px"><option value="0">Seleccione</option></select>')
	
	if($('#selectNivel2').val()=="0") return;
	
	setTimeout(function()
	{
		$.ajax(
		{
			async:true,
			beforeSend:function(objeto)
			{
				$('#obtenerNiveles3Catalogo').html('<img src="'+base_url+'img/ajax-loader.gif"/> Obteniendo registros');
			},
			type:"POST",
			url:base_url+'catalogos/obtenerNiveles3Catalogo',
			data:
			{
				idNivel2:$('#selectNivel2').val()
			},
			datatype:"html",
			success:function(data, textStatus)
			{
				$("#obtenerNiveles3Catalogo").html(data);
			},
			error:function(datos)
			{
				notify('Error al obtener la lista de modelos',500,5000,'error',30,3);
				$("#obtenerNiveles3Catalogo").html('');
			}
		});		
	},400);
}