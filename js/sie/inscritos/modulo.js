//INSCRITOS
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
$(document).ready(function()
{
	$('#txtInicioSieBusqueda').daterangepicker(
	{
		singleDatePicker: true,
		locale: 
		{
		  format: 'YYYY-MM-DD'
		}
	});

	obtenerInscritos();
});

function obtenerInscritos()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerInscritos').html('<img src="'+base_url+'img/ajax-loader.gif"/> Obteniendo registros');
		},
		type:"POST",
		url:base_url+'prospectos/obtenerInscritos',
		data:
		{
			inicio: 	$('#txtInicioSieBusqueda').val(),
			fin:	 	$('#txtFinSieBusqueda').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#obtenerInscritos").html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener los registros',500,5000,'error',30,3);
			$("#obtenerInscritos").html('');
		}
	});		
}
