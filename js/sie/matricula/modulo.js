//MATRICULA
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
$(document).ready(function()
{
	obtenerMatricula();

});

function obtenerMatricula()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerMatricula').html('<img src="'+base_url+'img/ajax-loader.gif"/> Obteniendo registros');
		},
		type:"POST",
		url:base_url+'matricula/obtenerMatricula',
		data:
		{
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#obtenerMatricula").html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener los registros',500,5000,'error',30,3);
			$("#obtenerMatricula").html('');
		}
	});		
}

function obtenerDetallesMatricula(cuatrimestre,licenciatura)
{
	$("#ventanaDetallesMatricula").modal('show');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerDetallesMatricula').html('<img src="'+base_url+'img/ajax-loader.gif"/> Obteniendo el formulario');
		},
		type:"POST",
		url:base_url+'matricula/obtenerDetallesMatricula',
		data:
		{
			cuatrimestre:	cuatrimestre,
			licenciatura:	licenciatura
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#obtenerDetallesMatricula").html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener el formulario',500,5000,'error',30,3);
			$("#obtenerDetallesMatricula").html('');
		}
	});		
}