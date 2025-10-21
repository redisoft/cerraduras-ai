
niveles	= 0;

$(document).ready(function()
{
	$("#ventanaNiveles").dialog(
	{
		autoOpen:false,     
		show: { effect: "scale", duration: 600 },                         
		height:650,
		width:1200,
		modal:true,
		resizable:false,
		buttons: 
		{
			'Aceptar': function() 
			{
				$(this).dialog('close');
			},
		},
		close: function() 
		{
			$('#obtenerNiveles').html('');
		}
	});
	
	//$('.ajax-pagNiveles > li a').live('click',function(eve)
	$(document).on("click", ".ajax-pagNiveles > li a", function(eve)
	{
		eve.preventDefault();
		var element = "#obtenerNiveles";
		var link 	= $(this).attr('href');
		
		$.ajax(
		{
			url:link,
			type:"POST",
			data:
			{
				detalle:	$('#selectTipoCuentasTabla').val()
			},
			dataType:"html",
			beforeSend:function(){$(element).html('<label><img src="'+ base_url +'img/loader.gif"/> Obteniendo detalles de cuentas'+leyendas+'</label>');},
			success:function(html,textStatus)
			{
				setTimeout(function()
				{
					$(element).html(html);},300);
			},
			error:function(datos){$(element).html('Error '+ datos).show('slow');}
		});
	});
});

function obtenerNiveles()
{
	niveles=1;
	
	$('#ventanaNiveles').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerNiveles').html('<label><img src="'+base_url+'img/loader.gif"/> Obteniendo detalles de cuentas...</label>');
		},
		type:"POST",
		url:base_url+'cuentas/obtenerNivel1',
		data:
		{
			detalle:	$('#selectTipoCuentasTabla').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerNiveles').html(data)
		},
		error:function(datos)
		{
			$('#obtenerNiveles').html('');
			notify("Error al obtener detalles de cuentas",500,4000,"error"); 
		}
	});	
}


//NIVEL 2 DE LAS CUENTAS
$(document).ready(function()
{
	$("#ventanaNivel2").dialog(
	{
		autoOpen:false,   
		show: { effect: "scale", duration: 600 },                           
		height:640,
		width:1150,
		modal:true,
		resizable:false,
		buttons: 
		{
			'Aceptar': function() 
			{
				$(this).dialog('close');
			},
		},
		close: function() 
		{
			$('#obtenerNivel2').html('');
		}
	});
});

function obtenerNivel2(idCuenta)
{
	$('#ventanaNivel2').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerNivel2').html('<label><img src="'+base_url+'img/loader.gif"/> Obteniendo detalles de subcuentas...</label>');
		},
		type:"POST",
		url:base_url+'cuentas/obtenerNivel2',
		data:
		{
			idCuenta:idCuenta
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerNivel2').html(data)
		},
		error:function(datos)
		{
			$('#obtenerNivel2').html('');
			notify("Error al obtener detalles de subcuentas",500,4000,"error"); 
		}
	});	
}

//NIVEL 3 DE LAS CUENTAS
$(document).ready(function()
{
	$("#ventanaNivel3").dialog(
	{
		autoOpen:false,   
		show: { effect: "scale", duration: 600 },                           
		height:630,
		width:1100,
		modal:true,
		resizable:false,
		buttons: 
		{
			'Registrar': function() 
			{
				registrarNivel3();
			},
		},
		close: function() 
		{
			$('#obtenerNivel3').html('');
		}
	});
});

function obtenerNivel3(idSubCuenta)
{
	$('#ventanaNivel3').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerNivel3').html('<label><img src="'+base_url+'img/loader.gif"/> Obteniendo detalles de subcuentas...</label>');
		},
		type:"POST",
		url:base_url+'cuentas/obtenerNivel3',
		data:
		{
			idSubCuenta:idSubCuenta
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerNivel3').html(data)
		},
		error:function(datos)
		{
			$('#obtenerNivel3').html('');
			notify("Error al obtener detalles de subcuentas",500,4000,"error"); 
		}
	});	
}

function registrarNivel3()
{
	alerta	= "";

	if(!camposVacios($('#txtCuentaNivel3').val()))
	{
		alerta+='La cuenta es incorrecta<br />';
	}
	
	if(!camposVacios($('#txtCodigoAgrupador3').val()))
	{
		alerta+='El código agrupador es incorrecto<br />';
	}
	
	if(alerta.length>0)
	{
		notify(alerta,500,5000,"error",30,5); 
		return;
	}
	
	if(!confirm('¿Realmente desea registrar la cuenta nivel 3?')) return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#procesandoNivel3').html('<label><img src="'+base_url+'img/loader.gif"/> Se esta registrando la cuenta...</label>');
		},
		type:"POST",
		url:base_url+'cuentas/registrarNivel3',
		data:
		{
			nombre:			$('#txtCuentaNivel3').val(),
			codigo:			$('#txtCodigoAgrupador3').val(),
			idSubCuenta:	$('#txtIdSubCuenta').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#procesandoNivel3').html('');
			
			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
				notify('Error al guardar el registro',500,4000,'error',30,5);
				break;
				
				case "1":
				notify('La cuenta se ha registrado correctamente',500,4000,'',30,5);
				
				if(niveles==0)
				{
					obtenerNivel3($('#txtIdSubCuenta').val());
				}
				
				if(niveles==1)
				{
					agregarNodo(Nivel,IdSubCuenta,data[1],data[2]);
					$("#ventanaFormularioNivel3").dialog('close');
				}
				
				break;
			}
		},
		error:function(datos)
		{
			$('#procesandoNivel3').html('');
			notify('Error al registrar la cuenta',500,4000,'error',30,5);
		}
	});	
}

Nivel		= 0;
IdSubCuenta	= 0;

function agregarNodo(nivel,idSubCuenta,nombre,idSubcuentaNueva)
{
	data='<ul id="ulNivel'+nivel+idSubcuentaNueva+'" style="display:block; margin-left: 21px">';
	data+='<li id="nivel'+(nivel+1)+idSubcuentaNueva+'">';
	data+='<a id="nodeATag'+idSubcuentaNueva+'">'+nombre+'</a>';
	data+=nivel<=4?' <img title="Agregar cuenta nivel '+(nivel+2)+'" onclick="formularioNivel'+(nivel+2)+'('+idSubcuentaNueva+','+(nivel+1)+')" src="'+base_url+'img/agregar.png" class="imgCuentasArbol" />':'';
	data+=' <img title="Editar nivel '+(nivel+1)+'" onclick="obtenerCuentaNivel'+(nivel+1)+'('+idSubcuentaNueva+','+(nivel+1)+')" src="'+base_url+'img/editar.png" class="imgCuentasArbol" />';
	data+=' <img title="Borrar nivel '+(nivel+1)+'" onclick="borrarNivel'+(nivel+1)+'('+idSubcuentaNueva+','+(nivel+1)+')" src="'+base_url+'img/borrar.png" class="imgCuentasArbol" />';
	
	data+='</li></ul>';

	$('#nivel'+nivel+idSubCuenta).append(data);
	
	treeObj = new JSDragDropTree();
	treeObj.setTreeId('ulNivel'+nivel+idSubcuentaNueva);
	treeObj.initTree();
}

$(document).ready(function()
{
	$("#ventanaFormularioNivel3").dialog(
	{
		autoOpen:false,  
		show: { effect: "scale", duration: 600 },                            
		height:280,
		width:800,
		modal:true,
		resizable:false,
		buttons: 
		{
			'Registrar': function() 
			{
				registrarNivel3();
			},
		},
		close: function() 
		{
			$('#formularioNivel3').html('');
		}
	});
});

function formularioNivel3(idSubCuenta,nivel)
{
	Nivel		= nivel;
	IdSubCuenta	= idSubCuenta;
	//agregarNodo();
	//return;
	
	$('#ventanaFormularioNivel3').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioNivel3').html('<label><img src="'+base_url+'img/loader.gif"/> Obteniendo detalles de formulario...</label>');
		},
		type:"POST",
		url:base_url+'cuentas/formularioNivel3',
		data:
		{
			idSubCuenta:idSubCuenta
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#formularioNivel3').html(data)
		},
		error:function(datos)
		{
			$('#formularioNivel3').html('');
			notify("Error al obtener detalles del formulario",500,4000,"error"); 
		}
	});	
}

$(document).ready(function()
{
	$("#ventanaEditarNivel3").dialog(
	{
		autoOpen:false,       
		show: { effect: "scale", duration: 600 },                      
		height:240,
		width:600,
		modal:true,
		resizable:false,
		buttons: 
		{
			'Editar': function() 
			{
				editarNivel3();
			},
		},
		close: function() 
		{
			$('#obtenerCuentaNivel3').html('');
		}
	});
});

function obtenerCuentaNivel3(idSubCuenta3)
{
	$('#ventanaEditarNivel3').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerCuentaNivel3').html('<label><img src="'+base_url+'img/loader.gif"/> Obteniendo detalles de subcuentas...</label>');
		},
		type:"POST",
		url:base_url+'cuentas/obtenerCuentaNivel3',
		data:
		{
			idSubCuenta3:idSubCuenta3
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerCuentaNivel3').html(data)
		},
		error:function(datos)
		{
			$('#obtenerCuentaNivel3').html('');
			notify("Error al obtener detalles de subcuentas",500,4000,"error"); 
		}
	});	
}

function editarNivel3()
{
	alerta	= "";

	if(!camposVacios($('#txtEditarCuentaNivel3').val()))
	{
		alerta+='La cuenta es incorrecta<br />';
	}
	
	if(!camposVacios($('#txtEditarCodigoAgrupador3').val()))
	{
		alerta+='El código agrupador es incorrecto<br />';
	}
	
	if(alerta.length>0)
	{
		notify(alerta,500,5000,"error",30,5); 
		return;
	}
	
	if(!confirm('¿Realmente desea editar la cuenta nivel 3?')) return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#editandoNivel3').html('<label><img src="'+base_url+'img/loader.gif"/> Se esta editando la cuenta...</label>');
		},
		type:"POST",
		url:base_url+'cuentas/editarNivel3',
		data:
		{
			nombre:			$('#txtEditarCuentaNivel3').val(),
			codigo:			$('#txtEditarCodigoAgrupador3').val(),
			idSubCuenta3:	$('#txtIdSubCuenta3').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#editandoNivel3').html('');
			
			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
				notify('El registro no tuvo cambios',500,4000,'error',30,5);
				break;
				
				case "1":
				notify('La cuenta se ha editado correctamente',500,4000,'',30,5);
				
				if(niveles==0)
				{
					obtenerNivel3($('#txtIdSubCuenta').val());
				}
				
				if(niveles==1)
				{
					$('#subNivel3'+$('#txtIdSubCuenta3').val()).html($('#txtEditarCuentaNivel3').val()+'('+$('#txtEditarCodigoAgrupador3').val()+')');
				}
				
				$('#ventanaEditarNivel3').dialog('close');
				break;
			}
		},
		error:function(datos)
		{
			$('#editandoNivel3').html('');
			notify('Error al registrar la cuenta',500,4000,'error',30,5);
		}
	});	
}

function borrarNivel3(idSubCuenta3)
{
	if(!confirm('¿Realmente desea borrar la cuenta nivel 3?')) return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#borrandoNivel3').html('<label><img src="'+base_url+'img/loader.gif"/> Se esta borrando la cuenta...</label>');
		},
		type:"POST",
		url:base_url+'cuentas/borrarNivel3',
		data:
		{
			idSubCuenta3:	idSubCuenta3
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#borrandoNivel3').html('');
			
			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
				notify('Error al borrar la cuenta, esta asociada a el catálogo',500,4000,'error',30,5);
				break;
				
				case "1":
				notify('La cuenta se ha borrado correctamente',500,4000,'',30,5);
				
				if(niveles==0)
				{
					obtenerNivel3($('#txtIdSubCuenta').val());
				}
				
				if(niveles==1)
				{
					$('#nivel3'+idSubCuenta3).remove();
				}
				
				break;
			}
		},
		error:function(datos)
		{
			$('#borrandoNivel3').html('');
			notify('Error al borrar la cuenta',500,4000,'error',30,5);
		}
	});	
}

//------------------------------------------------------------------------------------------------//
//NIVEL 4 DE LAS CUENTAS
//------------------------------------------------------------------------------------------------//
$(document).ready(function()
{
	$("#ventanaNivel4").dialog(
	{
		autoOpen:false,    
		show: { effect: "scale", duration: 600 },                          
		height:630,
		width:1050,
		modal:true,
		resizable:false,
		buttons: 
		{
			'Registrar': function() 
			{
				registrarNivel4();
			},
		},
		close: function() 
		{
			$('#obtenerNivel4').html('');
		}
	});
});

function obtenerNivel4(idSubCuenta3)
{
	$('#ventanaNivel4').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerNivel4').html('<label><img src="'+base_url+'img/loader.gif"/> Obteniendo detalles de subcuentas...</label>');
		},
		type:"POST",
		url:base_url+'cuentas/obtenerNivel4',
		data:
		{
			idSubCuenta3:idSubCuenta3
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerNivel4').html(data)
		},
		error:function(datos)
		{
			$('#obtenerNivel4').html('');
			notify("Error al obtener detalles de subcuentas",500,4000,"error"); 
		}
	});	
}

function registrarNivel4()
{
	alerta	= "";

	if(!camposVacios($('#txtCuentaNivel4').val()))
	{
		alerta+='La cuenta es incorrecta<br />';
	}
	
	if(!camposVacios($('#txtCodigoAgrupador4').val()))
	{
		alerta+='El código agrupador es incorrecto<br />';
	}
	
	if(alerta.length>0)
	{
		notify(alerta,500,5000,"error",30,5); 
		return;
	}
	
	if(!confirm('¿Realmente desea registrar la cuenta nivel 4?')) return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#procesandoNivel4').html('<label><img src="'+base_url+'img/loader.gif"/> Se esta registrando la cuenta...</label>');
		},
		type:"POST",
		url:base_url+'cuentas/registrarNivel4',
		data:
		{
			nombre:			$('#txtCuentaNivel4').val(),
			codigo:			$('#txtCodigoAgrupador4').val(),
			idSubCuenta3:	$('#txtIdSubCuenta3').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#procesandoNivel4').html('');
			
			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
				notify('Error al guardar el registro',500,4000,'error',30,5);
				break;
				
				case "1":
				notify('La cuenta se ha registrado correctamente',500,4000,'',30,5);
				
				if(niveles==0)
				{
					obtenerNivel4($('#txtIdSubCuenta3').val());
				}
				
				if(niveles==1)
				{
					agregarNodo(Nivel,IdSubCuenta,data[1],data[2]);
					$("#ventanaFormularioNivel4").dialog('close');
				}
				
				break;
			}
		},
		error:function(datos)
		{
			$('#procesandoNivel4').html('');
			notify('Error al registrar la cuenta',500,4000,'error',30,5);
		}
	});	
}

$(document).ready(function()
{
	$("#ventanaFormularioNivel4").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },                              
		height:280,
		width:800,
		modal:true,
		resizable:false,
		buttons: 
		{
			'Registrar': function() 
			{
				registrarNivel4();
			},
		},
		close: function() 
		{
			$('#formularioNivel4').html('');
		}
	});
});

function formularioNivel4(idSubCuenta,nivel)
{
	Nivel		= nivel;
	IdSubCuenta	= idSubCuenta;

	$('#ventanaFormularioNivel4').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioNivel4').html('<label><img src="'+base_url+'img/loader.gif"/> Obteniendo detalles de formulario...</label>');
		},
		type:"POST",
		url:base_url+'cuentas/formularioNivel4',
		data:
		{
			idSubCuenta3:idSubCuenta
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#formularioNivel4').html(data)
		},
		error:function(datos)
		{
			$('#formularioNivel4').html('');
			notify("Error al obtener detalles del formulario",500,4000,"error"); 
		}
	});	
}

$(document).ready(function()
{
	$("#ventanaEditarNivel4").dialog(
	{
		autoOpen:false,          
		show: { effect: "scale", duration: 600 },                    
		height:240,
		width:600,
		modal:true,
		resizable:false,
		buttons: 
		{
			'Editar': function() 
			{
				editarNivel4();
			},
		},
		close: function() 
		{
			$('#obtenerCuentaNivel4').html('');
		}
	});
});

function obtenerCuentaNivel4(idSubCuenta4)
{
	$('#ventanaEditarNivel4').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerCuentaNivel4').html('<label><img src="'+base_url+'img/loader.gif"/> Obteniendo detalles de subcuenta...</label>');
		},
		type:"POST",
		url:base_url+'cuentas/obtenerCuentaNivel4',
		data:
		{
			idSubCuenta4:idSubCuenta4
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerCuentaNivel4').html(data)
		},
		error:function(datos)
		{
			$('#obtenerCuentaNivel4').html('');
			notify("Error al obtener detalles de subcuenta",500,4000,"error"); 
		}
	});	
}

function editarNivel4()
{
	alerta	= "";

	if(!camposVacios($('#txtEditarCuentaNivel4').val()))
	{
		alerta+='La cuenta es incorrecta<br />';
	}
	
	if(!camposVacios($('#txtEditarCodigoAgrupador4').val()))
	{
		alerta+='El código agrupador es incorrecto<br />';
	}
	
	if(alerta.length>0)
	{
		notify(alerta,500,5000,"error",30,5); 
		return;
	}
	
	if(!confirm('¿Realmente desea editar la cuenta nivel 4?')) return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#editandoNivel4').html('<label><img src="'+base_url+'img/loader.gif"/> Se esta editando la cuenta...</label>');
		},
		type:"POST",
		url:base_url+'cuentas/editarNivel4',
		data:
		{
			nombre:			$('#txtEditarCuentaNivel4').val(),
			codigo:			$('#txtEditarCodigoAgrupador4').val(),
			idSubCuenta4:	$('#txtIdSubCuenta4').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#editandoNivel4').html('');
			
			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
				notify('El registro no tuvo cambios',500,4000,'error',30,5);
				break;
				
				case "1":
				notify('La cuenta se ha editado correctamente',500,4000,'',30,5);
				
				if(niveles==0)
				{
					obtenerNivel4($('#txtIdSubCuenta3').val());
				}
				
				if(niveles==1)
				{
					$('#subNivel4'+$('#txtIdSubCuenta4').val()).html($('#txtEditarCuentaNivel4').val()+'('+$('#txtEditarCodigoAgrupador4').val()+')');
				}
				
				$('#ventanaEditarNivel4').dialog('close');
				break;
			}
		},
		error:function(datos)
		{
			$('#editandoNivel4').html('');
			notify('Error al registrar la cuenta',500,4000,'error',30,5);
		}
	});	
}

function borrarNivel4(idSubCuenta4)
{
	if(!confirm('¿Realmente desea borrar la cuenta nivel 4?')) return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#borrandoNivel4').html('<label><img src="'+base_url+'img/loader.gif"/> Se esta borrando la cuenta...</label>');
		},
		type:"POST",
		url:base_url+'cuentas/borrarNivel4',
		data:
		{
			idSubCuenta4:	idSubCuenta4
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#borrandoNivel4').html('');
			
			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
				notify('Error al borrar la cuenta, esta asociada a el catálogo',500,4000,'error',30,5);
				break;
				
				case "1":
				notify('La cuenta se ha borrado correctamente',500,4000,'',30,5);
				
				if(niveles==0)
				{
					obtenerNivel4($('#txtIdSubCuenta3').val());
				}
				
				if(niveles==1)
				{
					$('#nivel4'+idSubCuenta4).remove();
				}
				
				break;
			}
		},
		error:function(datos)
		{
			$('#borrandoNivel4').html('');
			notify('Error al borrar la cuenta',500,4000,'error',30,5);
		}
	});	
}

//------------------------------------------------------------------------------------------------//
//NIVEL 5 DE LAS CUENTAS
//------------------------------------------------------------------------------------------------//
$(document).ready(function()
{
	$("#ventanaNivel5").dialog(
	{
		autoOpen:false,             
		show: { effect: "scale", duration: 600 },                 
		height:620,
		width:1000,
		modal:true,
		resizable:false,
		buttons: 
		{
			'Registrar': function() 
			{
				registrarNivel5();
			},
		},
		close: function() 
		{
			$('#obtenerNivel5').html('');
		}
	});
});

function obtenerNivel5(idSubCuenta4)
{
	$('#ventanaNivel5').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerNivel5').html('<label><img src="'+base_url+'img/loader.gif"/> Obteniendo detalles de subcuentas...</label>');
		},
		type:"POST",
		url:base_url+'cuentas/obtenerNivel5',
		data:
		{
			idSubCuenta4:idSubCuenta4
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerNivel5').html(data)
		},
		error:function(datos)
		{
			$('#obtenerNivel5').html('');
			notify("Error al obtener detalles de subcuentas",500,5000,"error"); 
		}
	});	
}

function registrarNivel5()
{
	alerta	= "";

	if(!camposVacios($('#txtCuentaNivel5').val()))
	{
		alerta+='La cuenta es incorrecta<br />';
	}
	
	if(!camposVacios($('#txtCodigoAgrupador5').val()))
	{
		alerta+='El código agrupador es incorrecto<br />';
	}
	
	if(alerta.length>0)
	{
		notify(alerta,500,5000,"error",30,5); 
		return;
	}
	
	if(!confirm('¿Realmente desea registrar la cuenta nivel 5?')) return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#procesandoNivel5').html('<label><img src="'+base_url+'img/loader.gif"/> Se esta registrando la cuenta...</label>');
		},
		type:"POST",
		url:base_url+'cuentas/registrarNivel5',
		data:
		{
			nombre:			$('#txtCuentaNivel5').val(),
			codigo:			$('#txtCodigoAgrupador5').val(),
			idSubCuenta4:	$('#txtIdSubCuenta4').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#procesandoNivel5').html('');
			
			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
				notify('Error al guardar el registro',500,5000,'error',30,5);
				break;
				
				case "1":
				notify('La cuenta se ha registrado correctamente',500,5000,'',30,5);
				
				if(niveles==0)
				{
					obtenerNivel5($('#txtIdSubCuenta4').val());
				}
				
				if(niveles==1)
				{
					agregarNodo(Nivel,IdSubCuenta,data[1],data[2]);
					$("#ventanaFormularioNivel5").dialog('close');
				}
				
				break;
			}
		},
		error:function(datos)
		{
			$('#procesandoNivel5').html('');
			notify('Error al registrar la cuenta',500,5000,'error',30,5);
		}
	});	
}

$(document).ready(function()
{
	$("#ventanaFormularioNivel5").dialog(
	{
		autoOpen:false,         
		show: { effect: "scale", duration: 600 },                     
		height:280,
		width:800,
		modal:true,
		resizable:false,
		buttons: 
		{
			'Registrar': function() 
			{
				registrarNivel5();
			},
		},
		close: function() 
		{
			$('#formularioNivel5').html('');
		}
	});
});

function formularioNivel5(idSubCuenta,nivel)
{
	Nivel		= nivel;
	IdSubCuenta	= idSubCuenta;

	$('#ventanaFormularioNivel5').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioNivel5').html('<label><img src="'+base_url+'img/loader.gif"/> Obteniendo detalles de formulario...</label>');
		},
		type:"POST",
		url:base_url+'cuentas/formularioNivel5',
		data:
		{
			idSubCuenta4:idSubCuenta
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#formularioNivel5').html(data)
		},
		error:function(datos)
		{
			$('#formularioNivel5').html('');
			notify("Error al obtener detalles del formulario",500,4000,"error"); 
		}
	});	
}

$(document).ready(function()
{
	$("#ventanaEditarNivel5").dialog(
	{
		autoOpen:false,                    
		show: { effect: "scale", duration: 600 },          
		height:240,
		width:600,
		modal:true,
		resizable:false,
		buttons: 
		{
			'Editar': function() 
			{
				editarNivel5();
			},
		},
		close: function() 
		{
			$('#obtenerCuentaNivel5').html('');
		}
	});
});

function obtenerCuentaNivel5(idSubCuenta5)
{
	$('#ventanaEditarNivel5').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerCuentaNivel5').html('<label><img src="'+base_url+'img/loader.gif"/> Obteniendo detalles de subcuenta...</label>');
		},
		type:"POST",
		url:base_url+'cuentas/obtenerCuentaNivel5',
		data:
		{
			idSubCuenta5:idSubCuenta5
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerCuentaNivel5').html(data)
		},
		error:function(datos)
		{
			$('#obtenerCuentaNivel5').html('');
			notify("Error al obtener detalles de subcuenta",500,5000,"error"); 
		}
	});	
}

function editarNivel5()
{
	alerta	= "";

	if(!camposVacios($('#txtEditarCuentaNivel5').val()))
	{
		alerta+='La cuenta es incorrecta<br />';
	}
	
	if(!camposVacios($('#txtEditarCodigoAgrupador5').val()))
	{
		alerta+='El código agrupador es incorrecto<br />';
	}
	
	if(alerta.length>0)
	{
		notify(alerta,500,5000,"error",30,5); 
		return;
	}
	
	if(!confirm('¿Realmente desea editar la cuenta nivel 5?')) return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#editandoNivel5').html('<label><img src="'+base_url+'img/loader.gif"/> Se esta editando la cuenta...</label>');
		},
		type:"POST",
		url:base_url+'cuentas/editarNivel5',
		data:
		{
			nombre:			$('#txtEditarCuentaNivel5').val(),
			codigo:			$('#txtEditarCodigoAgrupador5').val(),
			idSubCuenta5:	$('#txtIdSubCuenta5').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#editandoNivel5').html('');
			
			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
				notify('El registro no tuvo cambios',500,5000,'error',30,5);
				break;
				
				case "1":
				notify('La cuenta se ha editado correctamente',500,5000,'',30,5);
				
				if(niveles==0)
				{
					obtenerNivel5($('#txtIdSubCuenta4').val());
				}
				
				if(niveles==1)
				{
					$('#subNivel5'+$('#txtIdSubCuenta5').val()).html($('#txtEditarCuentaNivel5').val()+'('+$('#txtEditarCodigoAgrupador5').val()+')');
				}
				
				$('#ventanaEditarNivel5').dialog('close');
				break;
			}
		},
		error:function(datos)
		{
			$('#editandoNivel5').html('');
			notify('Error al registrar la cuenta',500,5000,'error',30,5);
		}
	});	
}

function borrarNivel5(idSubCuenta5)
{
	if(!confirm('¿Realmente desea borrar la cuenta nivel 5?')) return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#borrandoNivel5').html('<label><img src="'+base_url+'img/loader.gif"/> Se esta borrando la cuenta...</label>');
		},
		type:"POST",
		url:base_url+'cuentas/borrarNivel5',
		data:
		{
			idSubCuenta5:	idSubCuenta5
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#borrandoNivel5').html('');
			
			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
				notify('Error al borrar la cuenta, esta asociada a el catálogo',500,5000,'error',30,5);
				break;
				
				case "1":
				notify('La cuenta se ha borrado correctamente',500,5000,'',30,5);
				
				if(niveles==0)
				{
					obtenerNivel5($('#txtIdSubCuenta4').val());
				}
				
				if(niveles==1)
				{
					$('#nivel5'+idSubCuenta5).remove();
				}
				
				break;
			}
		},
		error:function(datos)
		{
			$('#borrandoNivel5').html('');
			notify('Error al borrar la cuenta',500,5000,'error',30,5);
		}
	});	
}

//------------------------------------------------------------------------------------------------//
//NIVEL 6 DE LAS CUENTAS
//------------------------------------------------------------------------------------------------//
$(document).ready(function()
{
	$("#ventanaNivel6").dialog(
	{
		autoOpen:false,              
		show: { effect: "scale", duration: 600 },                
		height:610,
		width:950,
		modal:true,
		resizable:false,
		buttons: 
		{
			'Cerrar todo': function() 
			{
				$("#ventanaNivel6").dialog('close');
				$("#ventanaNivel5").dialog('close');
				$("#ventanaNivel4").dialog('close');
				$("#ventanaNivel3").dialog('close');
				$("#ventanaNivel2").dialog('close');
				$("#ventanaNiveles").dialog('close');
			},
			'Registrar': function() 
			{
				registrarNivel6();
			},
		},
		close: function() 
		{
			$('#obtenerNivel6').html('');
		}
	});
});

function obtenerNivel6(idSubCuenta5)
{
	$('#ventanaNivel6').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerNivel6').html('<label><img src="'+base_url+'img/loader.gif"/> Obteniendo detalles de subcuentas...</label>');
		},
		type:"POST",
		url:base_url+'cuentas/obtenerNivel6',
		data:
		{
			idSubCuenta5:idSubCuenta5
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerNivel6').html(data)
		},
		error:function(datos)
		{
			$('#obtenerNivel6').html('');
			notify("Error al obtener detalles de subcuentas",600,6000,"error"); 
		}
	});	
}

function registrarNivel6()
{
	alerta	= "";

	if(!camposVacios($('#txtCuentaNivel6').val()))
	{
		alerta+='La cuenta es incorrecta<br />';
	}
	
	if(!camposVacios($('#txtCodigoAgrupador6').val()))
	{
		alerta+='El código agrupador es incorrecto<br />';
	}
	
	if(alerta.length>0)
	{
		notify(alerta,600,6000,"error",30,6); 
		return;
	}
	
	if(!confirm('¿Realmente desea registrar la cuenta nivel 6?')) return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#procesandoNivel6').html('<label><img src="'+base_url+'img/loader.gif"/> Se esta registrando la cuenta...</label>');
		},
		type:"POST",
		url:base_url+'cuentas/registrarNivel6',
		data:
		{
			nombre:			$('#txtCuentaNivel6').val(),
			codigo:			$('#txtCodigoAgrupador6').val(),
			idSubCuenta5:	$('#txtIdSubCuenta5').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#procesandoNivel6').html('');
			
			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
				notify('Error al guardar el registro',600,6000,'error',30,6);
				break;
				case "1":
				
				notify('La cuenta se ha registrado correctamente',600,6000,'',30,6);

				if(niveles==0)
				{
					obtenerNivel6($('#txtIdSubCuenta5').val());
				}
				
				if(niveles==1)
				{
					agregarNodo(Nivel,IdSubCuenta,data[1],data[2]);
					$("#ventanaFormularioNivel6").dialog('close');
				}
				
				break;
			}
		},
		error:function(datos)
		{
			$('#procesandoNivel6').html('');
			notify('Error al registrar la cuenta',600,6000,'error',30,6);
		}
	});	
}

$(document).ready(function()
{
	$("#ventanaFormularioNivel6").dialog(
	{
		autoOpen:false,  
		show: { effect: "scale", duration: 600 },                            
		height:280,
		width:800,
		modal:true,
		resizable:false,
		buttons: 
		{
			'Registrar': function() 
			{
				registrarNivel6();
			},
		},
		close: function() 
		{
			$('#formularioNivel6').html('');
		}
	});
});

function formularioNivel6(idSubCuenta,nivel)
{
	Nivel		= nivel;
	IdSubCuenta	= idSubCuenta;

	$('#ventanaFormularioNivel6').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioNivel6').html('<label><img src="'+base_url+'img/loader.gif"/> Obteniendo detalles de formulario...</label>');
		},
		type:"POST",
		url:base_url+'cuentas/formularioNivel6',
		data:
		{
			idSubCuenta5:idSubCuenta
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#formularioNivel6').html(data)
		},
		error:function(datos)
		{
			$('#formularioNivel6').html('');
			notify("Error al obtener detalles del formulario",500,4000,"error"); 
		}
	});	
}

$(document).ready(function()
{
	$("#ventanaEditarNivel6").dialog(
	{
		autoOpen:false,   
		show: { effect: "scale", duration: 600 },                           
		height:240,
		width:600,
		modal:true,
		resizable:false,
		buttons: 
		{
			'Editar': function() 
			{
				editarNivel6();
			},
		},
		close: function() 
		{
			$('#obtenerCuentaNivel6').html('');
		}
	});
});

function obtenerCuentaNivel6(idSubCuenta6)
{
	$('#ventanaEditarNivel6').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerCuentaNivel6').html('<label><img src="'+base_url+'img/loader.gif"/> Obteniendo detalles de subcuenta...</label>');
		},
		type:"POST",
		url:base_url+'cuentas/obtenerCuentaNivel6',
		data:
		{
			idSubCuenta6:idSubCuenta6
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerCuentaNivel6').html(data)
		},
		error:function(datos)
		{
			$('#obtenerCuentaNivel6').html('');
			notify("Error al obtener detalles de subcuenta",600,6000,"error"); 
		}
	});	
}

function editarNivel6()
{
	alerta	= "";

	if(!camposVacios($('#txtEditarCuentaNivel6').val()))
	{
		alerta+='La cuenta es incorrecta<br />';
	}
	
	if(!camposVacios($('#txtEditarCodigoAgrupador6').val()))
	{
		alerta+='El código agrupador es incorrecto<br />';
	}
	
	if(alerta.length>0)
	{
		notify(alerta,600,6000,"error",30,6); 
		return;
	}
	
	if(!confirm('¿Realmente desea editar la cuenta nivel 6?')) return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#editandoNivel6').html('<label><img src="'+base_url+'img/loader.gif"/> Se esta editando la cuenta...</label>');
		},
		type:"POST",
		url:base_url+'cuentas/editarNivel6',
		data:
		{
			nombre:			$('#txtEditarCuentaNivel6').val(),
			codigo:			$('#txtEditarCodigoAgrupador6').val(),
			idSubCuenta6:	$('#txtIdSubCuenta6').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#editandoNivel6').html('');
			
			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
				notify('El registro no tuvo cambios',600,6000,'error',30,6);
				break;
				
				case "1":
				notify('La cuenta se ha editado correctamente',600,6000,'',30,6);
				
				if(niveles==0)
				{
					obtenerNivel6($('#txtIdSubCuenta5').val());
				}
				
				if(niveles==1)
				{
					$('#subNivel6'+$('#txtIdSubCuenta6').val()).html($('#txtEditarCuentaNivel6').val()+'('+$('#txtEditarCodigoAgrupador6').val()+')');
				}
				
				$('#ventanaEditarNivel6').dialog('close');
				break;
			}
		},
		error:function(datos)
		{
			$('#editandoNivel6').html('');
			notify('Error al registrar la cuenta',600,6000,'error',30,6);
		}
	});	
}

function borrarNivel6(idSubCuenta6)
{
	if(!confirm('¿Realmente desea borrar la cuenta nivel 6?')) return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#borrandoNivel6').html('<label><img src="'+base_url+'img/loader.gif"/> Se esta borrando la cuenta...</label>');
		},
		type:"POST",
		url:base_url+'cuentas/borrarNivel6',
		data:
		{
			idSubCuenta6:	idSubCuenta6
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#borrandoNivel6').html('');
			
			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
				notify('Error al borrar la cuenta, esta asociada a el catálogo',600,6000,'error',30,6);
				break;
				
				case "1":
				notify('La cuenta se ha borrado correctamente',600,6000,'',30,6);
				
				if(niveles==0)
				{
					obtenerNivel6($('#txtIdSubCuenta5').val());
				}
				
				if(niveles==1)
				{
					$('#nivel6'+idSubCuenta6).remove();
				}
				
				break;
			}
		},
		error:function(datos)
		{
			$('#borrandoNivel6').html('');
			notify('Error al borrar la cuenta',600,6000,'error',30,6);
		}
	});	
}

//PARA EL ARBOL
$(document).ready(function()
{
	$("#ventanaArbol").dialog(
	{
		autoOpen:false,   
		show: { effect: "scale", duration: 600 },                           
		height:650,
		width:900,
		modal:true,
		resizable:false,
		buttons: 
		{
			'Aceptar': function() 
			{
				$(this).dialog('close');
			},
		},
		close: function() 
		{
			$('#obtenerArbol').html('');
		}
	});
});

function obtenerArbol()
{
	niveles	= 1;
	
	$('#ventanaArbol').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerArbol').html('<label><img src="'+base_url+'img/loader.gif"/> Obteniendo cuentas</label>');
		},
		type:"POST",
		url:base_url+'cuentas/obtenerArbol',
		data:
		{
			detalle:	$('#selectTipoCuentasArbol').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerArbol').html(data)
		},
		error:function(datos)
		{
			$('#obtenerArbol').html('');
			notify("Error al obtener las cuentas",600,6000,"error"); 
		}
	});	
}

//SALDO INICIAL
$(document).ready(function()
{
	$("#ventanaSaldoInicial").dialog(
	{
		autoOpen:false,          
		show: { effect: "scale", duration: 600 },                    
		height:240,
		width:800,
		modal:true,
		resizable:false,
		buttons: 
		{
			'Registrar': function() 
			{
				registrarSaldoInicial();
			},
		},
		close: function() 
		{
			$('#formularioSaldoInicial').html('');
		}
	});
});

function formularioSaldoInicial(idSubCuenta)
{
	$('#ventanaSaldoInicial').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioSaldoInicial').html('<label><img src="'+base_url+'img/loader.gif"/> Obteniendo detalles de saldo inicial...</label>');
		},
		type:"POST",
		url:base_url+'cuentas/formularioSaldoInicial',
		data:
		{
			idSubCuenta:idSubCuenta
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#formularioSaldoInicial').html(data);
			$('#txtSaldoInicial').focus()
		},
		error:function(datos)
		{
			$('#formularioSaldoInicial').html('');
			notify("Error al obtener detalles de saldo inicial",500,5000,"error",30,5); 
		}
	});	
}

function registrarSaldoInicial()
{
	alerta	= "";

	if(!comprobarNumeros($('#txtSaldoInicial').val()))
	{
		alerta+='El saldo inicial es incorrecto<br />';
	}
	
	if(alerta.length>0)
	{
		notify(alerta,600,6000,"error",30,6); 
		return;
	}
	
	if(!confirm('¿Realmente desea registrar el saldo inicial?')) return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#procesandoSaldoInicial').html('<label><img src="'+base_url+'img/loader.gif"/> Se esta registrando el saldo inicial...</label>');
		},
		type:"POST",
		url:base_url+'cuentas/registrarSaldoInicial',
		data:
		{
			saldoInicial:		$('#txtSaldoInicial').val(),
			idSubCuenta:		$('#txtIdCuenta').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#procesandoSaldoInicial').html('');
			
			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
					notify('El saldo inicial no tuvo cambios',600,6000,'error',30,6);
				break;
				case "1":
					notify('El saldo inicial se ha registrado correctamente',600,6000,'',30,6);
					document.getElementById('imgSaldoInicial'+$('#txtIdCuenta').val()).src=base_url+'img/saldoIniciado.png';
					$("#ventanaSaldoInicial").dialog('close');
				break;
			}
		},
		error:function(datos)
		{
			$('#procesandoSaldoInicial').html('');
			notify('Error al registrar el saldo inicial',600,6000,'error',30,6);
		}
	});	
}