//PARA AGREGAR MATERIA PRIMA A LOS PRODUCTOS
$(document).ready(function()
{
	/*$("#btnAgregarMaterial").click(function(e)
	{
		$('#ventanaProductoMateriales').dialog('open');
	});*/
	
	$("#ventanaProductoMateriales").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:300,
		width:800,
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
				agregarMateriaPrimaProduccion();
			},
			
		},
		close: function() 
		{
		}
	});
});

function formularioAgregarMateriales(idProducto)
{
	$('#ventanaProductoMateriales').dialog('open');

	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioAgregarMateriales').html('<img src="'+ img_loader +'"/> Obteniendo el detalle del producto...');
		},
		type:"POST",
		url:base_url+'produccion/formularioAgregarMateriales',
		data:
		{
			"idProducto":idProducto,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#formularioAgregarMateriales').html(data)
			$('#txtBusquedaMaterial').focus()
		},
		error:function(datos)
		{
			notify('Error al obtener los detalles del producto',500,5000,'error',2,5);
			$('#formularioAgregarMateriales').html('')
		}
	});					  	  
}


function agregarMateriaPrimaProduccion()
{
	var mensaje		="";
	var T44			=$("#T44").val();

	if($("#txtIdProduccion").val()=="0")
	{
		mensaje+="Por favor seleccione un producto<br />";										
	}
	
	if($("#selectMateriales").val()=="0")
	{
		mensaje+="Por favor seleccione la materia prima <br />";										
	}

	if($("#txtCantidad").val()=="" || isNaN($("#txtCantidad").val()) || parseFloat($("#txtCantidad").val())<0.00001)
	{
		mensaje+="La cantidad es incorrecta"
	}
	
	if(mensaje.length>0)
	{
		notify(mensaje,500,4000,"error",30,5); 
		return;
	}
	
	if(!confirm('¿Realmente desea continuar con el registro?')) return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#agregandoMateriaProducto').html('<img src="'+ img_loader +'"/> Se esta asociando el material con el producto...');},
		type:"POST",
		url:base_url+"produccion/agregarMaterialProducto",
		data:
		{
			"idProducto":	$("#txtIdProduccion").val(),
			"idMaterial":	$("#selectMateriales").val(),
			"cantidad":		$("#txtCantidad").val(),
			"idUnidad":		$("#txtIdUnidad").val(),
			"idConversion":	$("#selectConversiones").val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			data=eval(data);
			$('#agregandoMateriaProducto').html('');
			
			switch(data[0])
			{
				case "0":
					notify(data[1],500,4000,"error",30,5); 
				break;
				case "1":
					location.reload(true);
				break;
			}//switch
		},
		error:function(datos)
		{
			$('#agregandoMateriaProducto').html('');
		}
	});				  	  
}


function obtenerMaterialEditar(idProducto,idMaterial)
{
	$('#ventanaEditarMaterial').dialog('open');
	
	produc		=idProducto;
	materiales	=idMaterial;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerProductoMaterial').html('<img src="'+ img_loader +'"/> Obteniendo el detalle de materia prima...');
		},
		type:"POST",
		//url:base_url+'materiales/obtenerMermas',
		url:base_url+'produccion/obtenerMaterialEditar/'+idProducto+"/"+idMaterial,
		data:
		{
			//"idMaterial":idMaterial,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerMaterialEditar').html(data)
		},
		error:function(datos)
		{
			notify('Error al obtener los detalles de materia prima',500,5000,'error',2,5);
			$('#obtenerProductoMaterial').html('')
		}
	});					  	  
}

$(document).ready(function()
{
	$("#ventanaEditarMaterial").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:200,
		width:600,
		modal:true,
		resizable:false,
		buttons: 
		{
			Cancelar: function() 
			{
				$("#obtenerProductoMaterial").html(''); 
				$(this).dialog('close');				 
			},
			'Aceptar': function() 
			{
				editarMaterialProduccion();
			},
		},
		close: function() 
		{
			$("#ErrorEditar").fadeOut();
		}
	});
});

function editarMaterialProduccion()
{
	var mensaje	="";

	if($("#material").val()=="")
	{
		mensaje+="Error en el material <br />";
	}
	
	if(Solo_Numerico($("#cantidad").val())=="" || $("#cantidad").val()=="0") 
	{
		mensaje+="La cantidad es incorrecta";										
	}
	
	if(mensaje.length>0)
	{
		notify(mensaje,500,5000,'error',2,5);
		return;
	}
	
	if(confirm('¿Realmente desea editar el registro de materia prima?')==false)
	{
		return;
	}
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#editandoProductoMaterial').html('<img src="'+ img_loader +'"/> Se esta editando el registro de materia prima...');
		},
		type:"POST",
		url:base_url+"produccion/editarMaterialProduccion",
		data:
		{
			"cantidad":		$("#cantidad").val(),
			"idProducto":	$("#txtIdProductoEditar").val(),
			"idMaterial":	materiales
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			switch(data)
			{
				case "0":
				$("#editandoProductoMaterial").html('');
				notify('Error al editar el registro de materia prima',500,5000,'error',2,5);
				break;
				
				case "1":
				window.location.href=base_url+"produccion/index/"+$('#pag2').val();
				break;
			}
		},
		error:function(datos)
		{
			$("#editandoProductoMaterial").html('');
			notify('Error al editar el registro de materia prima',500,5000,'error',2,5);
		}
	});			  	  
}