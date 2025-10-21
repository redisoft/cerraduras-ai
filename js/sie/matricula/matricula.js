//MATRICULA
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
$(document).ready(function()
{
	/*$('#txtBuscarNivel1').keypress(function(e)
	 {
		if(e.which == 13) 
		{
			obtenerNiveles1();
		}
	});*/
	
	$("#ventanaRegistroMatriculaSie").dialog(
	{
		autoOpen:false,
		height:280,
		width:700,
		modal:true,
		resizable:false,
		buttons: 
		{
			Cancelar: function() 
			{
				$(this).dialog('close');				 
			},
			'Registrar': function() 
			{
				registrarMatriculaSie();
			},
		},
		close: function() 
		{
			$('#formularioMatriculaSie').html('');
		}
	});

	$(document).on("click", ".ajax-pagMatriculaSie > li a", function(eve)
	{
		eve.preventDefault();
		var element 	= "#obtenerMatriculaSie";
		var link 		= $(this).attr('href');
		
		$.ajax(
		{
			url:link,
			type:"POST",
			data:
			{
				licenciatura:	$('#txtLicenciatura').val(),
			},
			dataType:"html",
			beforeSend:function(){$(element).html('<img src="'+ img_loader +'"/> Obteniendo registros...');},
			success:function(html,textStatus)
			{
				setTimeout(function()
				{
					$(element).html(html);},300);
				},
				error:function(datos){$(element).html('Error '+ datos).show('slow');
			}
		});
	});
});

function obtenerMatriculaSie()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerMatriculaSie').html('<img src="'+base_url+'img/ajax-loader.gif"/> Obteniendo registros');
		},
		type:"POST",
		url:base_url+'matricula/obtenerRegistros',
		data:
		{
			licenciatura:	$('#txtLicenciatura').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#obtenerMatriculaSie").html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener los registros',500,5000,'error',30,3);
			$("#obtenerMatriculaSie").html('');
		}
	});		
}

function formularioMatriculaSie()
{
	$("#ventanaRegistroMatriculaSie").dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioMatriculaSie').html('<img src="'+base_url+'img/ajax-loader.gif"/> Obteniendo el formulario');
		},
		type:"POST",
		url:base_url+'matricula/formularioRegistro',
		data:
		{
			//idUsuario:idUsuario
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#formularioMatriculaSie").html(data);
			$('#txtIngresosSie').focus();
		},
		error:function(datos)
		{
			notify('Error al obtener el formulario',500,5000,'error',30,3);
			$("#formularioMatriculaSie").html('');
		}
	});		
}


function registrarMatriculaSie()
{
	if(!camposVacios($('#txtIngresosSie').val()) || !camposVacios($('#txtActualSie').val()) || !camposVacios($('#txtMetaSie').val()))
	{
		notify('Todos los valores son requeridos',500,5000,'error',30,3);
		return;
	}
	
	if(obtenerNumeros($('#txtMetaSie').val())>99)
	{
		notify('El porcentaje es incorrecto',500,5000,'error',30,3);
		return;
	}
	
	$.ajax(
	{
		async:false,
		beforeSend:function(objeto)
		{
			$('#registrandoMatriculaSie').html('<img src="'+base_url+'img/ajax-loader.gif"/> Registrando');
		},
		type:"POST",
		url:base_url+'matricula/registrarInformacion',
		data:$('#frmRegistroMatriculaSie').serialize()+'&licenciatura='+$('#txtLicenciatura').val(),
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#registrandoMatriculaSie').html('');
			
			switch(data)
			{
				case "0":
					notify('El registro esta duplicado',500,5000,'error',30,3);
				break;
	
				case "1":
					notify('El registro ha sido correcto',500,5000,'',30,3);
					obtenerMatriculaSie();
					$("#ventanaRegistroMatriculaSie").dialog('close');
				break;
			}
		},
		error:function(datos)
		{
			notify('Error en el registro',500,5000,'error',30,3);
			$("#registrandoMatriculaSie").html('');
		}
	});		
}


function borrarMatriculaSie(idMatricula)
{
	if(!confirm('¿Realmente desea borrar el registro?'))return;
		
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#procesandoMatriculaSie').html('<img src="'+base_url+'img/ajax-loader.gif"/> Borrando el registro');
		},
		type:"POST",
		url:base_url+"matricula/borrarRegistro",
		data:
		{
			"idMatricula":	idMatricula,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#procesandoMatriculaSie').html('');
			
			switch(data)
			{
				case "0":
					notify('Error al borrar el registro',500,5000,'error',30,3);
				break;
				
				case "1":
					obtenerMatriculaSie();
					notify('El registro se ha borrado correctamente',500,5000,'',30,3);
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al borrar el registro',500,5000,'error',30,3);
			$('#procesandoMatriculaSie').html('');
		}
	});				  	  
}

function editarMatricula(i,campo,input)
{
	valor	= obtenerNumeros($('#'+input+i).val());
	
	if(valor==0)
	{
		notify('Revise que el valor sea correcto',500,5000,'error',30,3);
		return;
	}
	
	if(campo=='meta' && valor>100)
	{
		notify('La meta es incorrecta',500,5000,'error',30,3);
		return;
	}
		
	$.ajax(
	{
		async:false,
		beforeSend:function(objeto)
		{
			//$('#procesandoMatriculaSie').html('<img src="'+base_url+'img/ajax-loader.gif"/> Borrando el registro');
		},
		type:"POST",
		url:base_url+"matricula/editarMatricula",
		data:
		{
			"campo":			campo,
			"valor":			valor,
			"idMatricula":		$('#txtIdMatricula'+i).val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			//$('#procesandoMatriculaSie').html('');
			
			actual						= obtenerNumeros($('#txtActual'+i).val());
			ingresos					= obtenerNumeros($('#txtIngresos'+i).val());
			meta						= obtenerNumeros($('#txtMeta'+i).val());
			desercion					= (1-(actual/ingresos))*100;
			
			
			$('#lblDesercion'+i).html(redondear(desercion)+'%')
		},
		error:function(datos)
		{
			notify('Error al borrar el registro',500,5000,'error',30,3);
			$('#procesandoMatriculaSie').html('');
		}
	});				  	  
}