
$(document).ready(function()
{
	$("#ventanaUsos").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:500,
		width:780,
		modal:true,
		resizable:false,
		buttons: 
		{
			Cancelar: function() 
			{
				$(this).dialog('close');				 
			},
			'Aceptar': function() 
			{
				registrarUsoInventario()
			},
			
		},
		close: function() 
		{
			$('#obtenerUsosInventario').html('');
		}
	});
	
	//$('.ajax-pagUso > li a').live('click',function(eve)
	$(document).on("click", ".ajax-pagUso > li a", function(eve)
	{
		eve.preventDefault();
		var element = "#obtenerUsosInventario";
		var link = $(this).attr('href');
		
		$.ajax(
		{
			url:link,
			type:"POST",
			data:
			{
				"idInventario":$("#txtIdInventario").val(),
			},
			dataType:"html",
			beforeSend:function(){$(element).html('<img src="'+ img_loader +'"/> Obteniendo detalles de uso de inventario...');},
			success:function(html,textStatus)
			{
				setTimeout(function()
				{
					$(element).html(html);},300);
				},
				error:function(datos){$(element).html('Error '+ datos).show('slow');
			}
		});
	});//.ajax
});


function registrarUsoInventario()
{
	mensaje="";
	
	if(Solo_Numerico($("#txtCantidadUsar").val())=="" || parseFloat($("#txtCantidadUsar").val())>parseFloat($("#txtExistencia").val()))
	{
		notify('La cantidad es incorrecta',500,4000,"error",30,5);
		return;								
	}

	if(!confirm('¿Realmente desea registrar el uso del inventario?')) return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#registrandoUsos').html('<img src="'+ img_loader +'"/> Registrando el uso de inventario...');
		},
		type:"POST",
		url:base_url+"inventarioProductos/registrarUsoInventario",
		data:
		{
			"idInventario":	$('#txtIdInventario').val(),
			"cantidad":		$('#txtCantidadUsar').val(),
			"comentarios":	$('#txtComentarios').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#registrandoUsos").html('');
			data=eval(data);
			
			switch(data[0])
			{
				case "0":
					notify(data[1],500,4000,"error",30,5);
				break;
				
				case "1":
					notify(data[1],500,4000,"",30,5);
					$("#registrandoUsos").html('');
					obtenerUsosInventario($('#txtIdInventario').val());
					obtenerInventarios();
				break;
			}
		},
		error:function(datos)
		{
			$("#registrandoUsos").html('');
			notify('Error al registrar el uso del inventario',500,4000,"error",30,5);
		}
	});
}


function obtenerUsosInventario(idInventario)
{
	 $('#ventanaUsos').dialog('open');
	 
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerUsosInventario').html('<img src="'+ img_loader +'"/> Obteniendo detalles de uso de inventario...');
		},
		type:"POST",
		url:base_url+"inventarioProductos/obtenerUsosInventario",
		data:
		{
			"idInventario":idInventario,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerUsosInventario').html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener los detalles de uso de inventario',500,4000,"error");
			$("#obtenerUsosInventario").html("");	
		}
	});
}