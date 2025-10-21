function editarProgramaProspecto()
{
	if($('#selectProgramas').val()=="0")
	{
		$('#filaDiplomados').fadeOut();
		return
	}
	
	Programa = new String($('#selectProgramas').val())
	programa = Programa.split('|');
	
	if(document.getElementById('chkPreinscrito').checked)
	{
		if(programa[1]=="2")
		{
			$('#filaDiplomados').fadeIn();
		}
		else
		{
			$('#filaDiplomados').fadeOut();
		}
	}
	
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			
		},
		type:"POST",
		url:base_url+'crm/editarProgramaProspecto',
		data:
		{
			//"idPrograma":		$('#selectProgramas').val(),
			"idPrograma":		programa[0],
			"idCliente":		$('#txtClienteId').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			data	= eval(data);
			
			$('#txtCantidadInscripcion').val(data[0])
			$('#txtCantidadColegiatura').val(data[1])
			$('#txtCantidadReinscripcion').val(data[2])
			
			calcularTotalesAcademicosProspecto()
		},
		error:function(datos)
		{
			
		}
	});		
}

function revisarDiplomadosPrograma()
{
	if($('#selectProgramas').val()=="0")
	{
		$('#filaDiplomados').fadeOut();
		return
	}
	
	Programa = new String($('#selectProgramas').val())
	programa = Programa.split('|');
	
	if(document.getElementById('chkPreinscrito').checked)
	{
		$('#filaMesPreinscrito').fadeIn();
		
		
		if(programa[1]=="2")
		{
			$('#filaDiplomados').fadeIn();
		}
		else
		{
			$('#filaDiplomados').fadeOut();
		}
	}
	else
	{
		$('#filaDiplomados,#filaMesPreinscrito').fadeOut();
	}
}

function editarFuenteProspecto()
{
	if($('#selectFuentesContacto').val()=="0") return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			
		},
		type:"POST",
		url:base_url+'crm/editarFuenteProspecto',
		data:
		{
			"idFuente":			$('#selectFuentesContacto').val(),
			"idCliente":		$('#txtClienteId').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			notify('El registro se ha guardado correctamente',500,5000,'',30,5);
		},
		error:function(datos)
		{
			
		}
	});		
}

function editarCampanaProspecto()
{
	$('#filaDiplomados').fadeOut();
	
	if($('#selectCampanasEditar').val()=="0") return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			
		},
		type:"POST",
		url:base_url+'crm/editarCampanaProspecto',
		data:
		{
			"idCampana":		$('#selectCampanasEditar').val(),
			"idCliente":		$('#txtClienteId').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			
		},
		error:function(datos)
		{
			
		}
	});		
}

function obtenerProgramasCampana()
{
	if($('#selectCampanasEditar').val()=="0") return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			
		},
		type:"POST",
		url:base_url+'crm/obtenerProgramasCampana',
		data:
		{
			"idCampana":		$('#selectCampanasEditar').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerProgramasCampana').html(data)
		},
		error:function(datos)
		{
			
		}
	});		
}