
$(document).ready(function()
{
	obtenerEstadisticas()
});


function obtenerEstadisticas()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerEstadisticas').html('<img src="'+ img_loader +'"/>Obteniendo detalles de estadisticas...');
		},
		type:"POST",
		url:base_url+'redisoft/obtenerEstadisticas',
		data:
		{
			/*inicio:		$('#FechaDia').val(),
			fin:		$('#FechaDia2').val(),
			idPersonal:	$('#txtPersonal').val(),*/
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerEstadisticas').html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener los detalles de la estadisticas',500,5000,'error',2,5);
			$("#obtenerEstadisticas").html('');
		}
	});
}
