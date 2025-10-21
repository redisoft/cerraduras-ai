$(document).ready(function()
{
	$("#ventanaMarcasProveedor").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:500,
		width:800,
		modal:true,
		resizable:false,
		buttons: 
		{
			Cancelar: function() 
			{
				$(this).dialog('close');
			},
			'Guardar': function() 
			{
				registrarMarcaProveedor();
			},
		},
		close: function() 
		{
			$("#obtenerMarcasProveedor").html('');
		}
	});
});

function obtenerMarcasProveedor(idProveedor)
{
	$('#ventanaMarcasProveedor').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerMarcasProveedor').html('<img src="'+ img_loader +'"/> Preparando el formulario...');
		},
		type:"POST",
		url:base_url+"proveedores/obtenerMarcasProveedor",
		data:
		{
			"idProveedor":idProveedor,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerMarcasProveedor').html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener la información',500,5000,'error',5,5);
			$("#obtenerMarcasProveedor").html('');	
		}
	});
}

function registrarMarcaProveedor()
{
	var mensaje	= "";

	if($("#txtIdMarca").val()=="0")
	{
		mensaje+="Seleccione la marca<br />";
	}

	if(mensaje.length>0)
	{
		notify(mensaje,500,5000,'error',30,5);
		return;
	}
	
	if(!confirm('¿Realmente desea continuar el registro?'))return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#registrandoMarcaProveedor').html('<img src="'+ img_loader +'"/> Registrando, por favor espere...');
		},
		type:"POST",
		url:base_url+"proveedores/registrarMarcaProveedor",
		data:$('#frmMarcas').serialize(),
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#registrandoMarcaProveedor").html("");
			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
					notify(data[1],500,5000,'error',30,5);
				break;
				
				case "1":
					obtenerMarcasProveedor($('#txtIdProveedor').val());
				break;
			}
		},
		error:function(datos)
		{
			$("#registrandoMarcaProveedor").html('');
			notify('Error al registrar al contacto',500,5000,'error',30,5);
		}
	});
}

function borrarMarcaProveedor(idRelacion)
{
	if(!confirm('¿Realmente desea borrar el registro?'))return;

	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#registrandoMarcaProveedor').html('<img src="'+ img_loader +'"/> Se esta borrando el registro, por favor espere...');
		},
		type:"POST",
		url:base_url+"proveedores/borrarMarcaProveedor",
		data:
		{
			idRelacion:idRelacion
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#registrandoMarcaProveedor').html('')
			
			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
					notify(data[1],500,5000,'error',30,5);
				break;
				
				case "1":
					obtenerMarcasProveedor($('#txtIdProveedor').val());
				break;
			}
		},
		error:function(datos)
		{
			$('#registrandoMarcaProveedor').html('')
			notify('Error al borrar el registro',500,5000,'error',0,0);
		}
	});	
}
