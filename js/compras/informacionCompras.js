$(document).ready(function()
{
	$("#ventanitaCompras").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:450,
		width:800,
		modal:true,
		resizable:false,
		buttons: 
		{
			'Imprimir': function() 
			{
				window.open(base_url+'compras/'+$('#txtModuloCompras').val()+'/'+$('#txtIdCompraModulo').val()+'/1');
			},
			'Aceptar': function() 
			{
				$("#cargarComprita").html(''); 
				$(this).dialog('close');	
			},
		},
		close: function() 
		{
			$("#cargarComprita").html('');
			detalleCita	= false;
		}
	});
});

function obtenerComprita(idCompras)
{
	detalleCita	= true;
	$('#ventanitaCompras').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#cargarComprita').html('<img src="'+ img_loader +'"/> Se estan cargando los datos de la compra...');},
		type:"POST",
		url:base_url+'tablero/obtenerCompra',
		data:
		{
			"idCompras":idCompras
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#cargarComprita").html(data);
		},
		error:function(datos)
		{
			$("#cargarComprita").html('Error al obtener la compra');	
		}
	});		
}
