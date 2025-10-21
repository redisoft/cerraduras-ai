$(document).ready(function()
{
	$("#txtCriterioGlobal").keyup(function() 
	{
		clearTimeout(tiempoRetraso);
		tiempoRetraso 	= setTimeout(function() 
		{
			obtenerComprasGlobal();
		}, 700);
	});
	
	obtenerComprasGlobal()
});

function obtenerComprasGlobal()
{
	if(ejecutar && ejecutar.readystate != 4)
	{
		ejecutar.abort();
	}
	
	ejecutar=$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerComprasGlobal').html('<img src="'+ img_loader +'"/>Obteniendo detalles de compras...');
		},
		type:"POST",
		url:base_url+'compras/obtenerComprasGlobal',
		data:
		{
			"idProveedor":	$('#txtIdProveedorGlobal').val(),
			"inicio":		$('#FechaDia').val(),
			"fin":		$('#FechaDia2').val(),
			"criterio":		$('#txtCriterioGlobal').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#obtenerComprasGlobal").html(data);
		},
		error:function(datos)
		{
			$("#obtenerComprasGlobal").html('');	
		}
	});
}