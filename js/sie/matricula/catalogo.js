//HORARIOS
$(document).ready(function()
{
	$("#ventanaListaMatriculaSie").dialog(
	{
		autoOpen:false,  
		show: { effect: "scale", duration: 600 },                            
		height:550,
		width:1000,
		modal:true,
		resizable:false,
		buttons: 
		{
			'Aceptar': function() 
			{
				$('#ventanaListaMatriculaSie').dialog('close');
			}
		},
		close: function() 
		{
			$('#listaMatriculaSie').html('');
		}
	});
});

function listaMatriculaSie(licenciatura)
{
	$('#ventanaListaMatriculaSie').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#listaMatriculaSie').html('<img src="'+base_url+'img/ajax-loader.gif"/> Obteniendo registros');
		},
		type:"POST",
		url:base_url+'matricula/listaMatriculaSie',
		data:
		{
			licenciatura:licenciatura
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#listaMatriculaSie").html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener los registros',500,5000,'error',30,3);
			$("#listaMatriculaSie").html('');
		}
	});		
}
