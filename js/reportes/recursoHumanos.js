$(document).ready(function ()
{
	obtenerRecursosHumanos()
});

function obtenerRecursosHumanos()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#obtenerRecursosHumanos').html('<img src="'+ img_loader +'"/> Obteniendo detalles...');},
		type:"POST",
		url:base_url+'reportes/obtenerRecursosHumanos',
		data:
		{
			
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerRecursosHumanos').html(data);
		},
		error:function(datos)
		{
			$("#obtenerRecursosHumanos").html('');
		}
	});
}