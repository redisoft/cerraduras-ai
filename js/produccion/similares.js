$(document).ready(function()
{
	$("#ventanaSimilares").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:520,
		width:700,
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
				registrarSimilares();
			},
			
		},
		close: function() 
		{
			$("#ErrorSimilares").fadeOut();
		}
	});
});

function formularioSimilares()
{
	$('#ventanaSimilares').dialog('open');
}

function registrarSimilares()
{
	var mensaje			="";
	var productos 		= new Array();
	var codigoInterno 	= new Array();
	ban=0;
	
	if($('#productoBase').val()=="0")
	{
		mensaje+="Es necesario seleccionar el producto base <br />";
	}
	
	for(i=0;i<10;i++)
	{
		productos[i]		=$('#txtNombreSimilar'+i).val();
		codigoInterno[i]	=$('#txtCodigoSimilar'+i).val();
		
		if(productos[i].length>2)
		{
			ban=1;
		}
	}
	
	if (ban==0)
	{
		mensaje+="Es necesario escribir al menos el nombre de un producto similar";
	}
	
	if(mensaje.length>0)
	{
		notify(mensaje,500,5000,'error',30,5);
		return;
	}
	
	if(!confirm('¿Realmente desea continuar con el registro?')) return;

	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#agregandoSimilares').html('<img src="'+ img_loader +'"/>Se estan registrando los productos similares, por favor espere...');},
		type:"POST",
		url:base_url+"produccion/registrarSimilares",
		data:
		{
			"idProducto":		$("#productoBase").val(),
			"productos":		productos,
			"codigoInterno":	codigoInterno
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			data=eval(data);
			$('#agregandoSimilares').html('');
			
			switch(data[0])
			{
				case "0":
					notify(data[1],500,5000,'error',30,5);
				break;
				case "1":
					location.reload(true);
				break;
			}//switch
		},
		error:function(datos)
		{
			$('#agregandoSimilares').html('');
			notify(mensaje,500,5000,'error',1,1);	
		}
	});	
}