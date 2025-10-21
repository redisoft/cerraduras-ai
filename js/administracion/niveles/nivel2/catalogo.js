//HORARIOS
$(document).ready(function()
{
	$("#ventanaListaNiveles2").dialog(
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
				$('#ventanaListaNiveles2').dialog('close');
			}
		},
		close: function() 
		{
			if($('#txtRegistrosAfectados2').val()!="0")
			{
				obtenerNiveles2Catalogo()
			}
			
			$('#listaNiveles2').html('');
		}
	});
});

function listaNiveles2()
{
	$('#ventanaListaNiveles2').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#listaNiveles2').html('<img src="'+base_url+'img/ajax-loader.gif"/> Obteniendo registros');
		},
		type:"POST",
		url:base_url+'catalogos/listaNiveles2',
		data:
		{
			//idUsuario:idUsuario
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#listaNiveles2").html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener la lista de modelos',500,5000,'error',30,3);
			$("#listaNiveles2").html('');
		}
	});		
}

function obtenerNiveles2Catalogo()
{
	$('#obtenerNiveles2Catalogo').html('<select class="cajas" id="selectNivel2" name="selectNivel2" style="width:290px"><option value="0">Seleccione</option></select>')
	$('#obtenerNiveles3Catalogo').html('<select class="cajas" id="selectNivel3" name="selectNivel3" style="width:290px"><option value="0">Seleccione</option></select>')
	
	if($('#selectNivel1').val()=="0") return;
	
	setTimeout(function()
	{
		$.ajax(
		{
			async:true,
			beforeSend:function(objeto)
			{
				$('#obtenerNiveles2Catalogo').html('<img src="'+base_url+'img/ajax-loader.gif"/> Obteniendo registros');
			},
			type:"POST",
			url:base_url+'catalogos/obtenerNiveles2Catalogo',
			data:
			{
				idNivel1:$('#selectNivel1').val()
			},
			datatype:"html",
			success:function(data, textStatus)
			{
				$("#obtenerNiveles2Catalogo").html(data);
			},
			error:function(datos)
			{
				notify('Error al obtener la lista de modelos',500,5000,'error',30,3);
				$("#obtenerNiveles2Catalogo").html('');
			}
		});	
	},400);
}



