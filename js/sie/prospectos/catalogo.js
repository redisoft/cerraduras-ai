//HORARIOS
$(document).ready(function()
{
	$("#ventanaListaProspectosSie").dialog(
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
				$('#ventanaListaProspectosSie').dialog('close');
			}
		},
		close: function() 
		{
			$('#listaProspectosSie').html('');
		}
	});
});

function listaProspectosSie()
{
	$('#ventanaListaProspectosSie').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#listaProspectosSie').html('<img src="'+base_url+'img/ajax-loader.gif"/> Obteniendo registros');
		},
		type:"POST",
		url:base_url+'prospectos/listaProspectosSie',
		data:
		{
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#listaProspectosSie").html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener los registros',500,5000,'error',30,3);
			$("#listaProspectosSie").html('');
		}
	});		
}
