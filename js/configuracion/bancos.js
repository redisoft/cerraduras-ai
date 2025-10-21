$(document).ready(function()
{
	$("#ventanaBancos").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:180,
		width:650,
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
				registrarBanco();
			},
		},
		close: function() 
		{
			$("#formularioBancos").html(''); 
		}
	});
	
	$("#ventanaEditarBanco").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:180,
		width:650,
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
				editarBanco();		  	  
			},
		},
		close: function() 
		{
			$('#obtenerBanco').html('');
		}
	});
});

function editarBanco()
{
	if($('#txtNombre').val()=="")
	{
		notify('El nombre del banco es requerido',500,5000,'error',30,3);
		return;
	}
	
	if(!confirm('¿Realmente desea editar el registro del banco?'))return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#editandoBanco').html('<img src="'+ img_loader +'"/> Editando el banco, por favor espere...');
		},
		type:"POST",
		url:base_url+'bancos/editarBanco',
		data:
		{
			nombre:		$('#txtNombre').val(),
			idBanco:	$('#txtIdBanco').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			switch(data)
			{
				case "0":
				notify('El registro del banco no tiene ningun cambio',500,5000,'error',30,3);
				$('#editandoBanco').html('');
				break;
				
				case "1":
					window.location.href=base_url+'bancos';
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al editar el banco',500,5000,'error',30,3);
			$("#editandoBanco").html('');
		}
	});
}

function obtenerBanco(idBanco)
{
	$('#ventanaEditarBanco').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerBanco').html('<img src="'+ img_loader +'"/> Obteniendo los datos para editar el banco...');
		},
		type:"POST",
		url:base_url+'bancos/obtenerBanco',
		data:
		{
			idBanco:idBanco
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#obtenerBanco").html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener los datos para editar el banco',500,5000,'error',30,3);
			$("#obtenerBanco").html('');
		}
	});
}

function registrarBanco()
{
	if(!camposVacios($('#txtNombre').val()))
	{
		notify('El nombre del banco es requerido',500,5000,'error',30,5);
		return;
	}
	
	$('#registrandoBanco').html('<img src="'+ img_loader +'"/> Registrando el banco, por favor espere...');
	
	//document.forms['frmBancos'].submit();
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#registrandoBanco').html('<img src="'+ img_loader +'"/> Registrando el banco, por favor espere...');
		},
		type:"POST",
		url:base_url+'bancos/registrarBanco',
		data:$('#frmBancos').serialize(),
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#registrandoBanco').html('');
			data=eval(data);
			switch(data[0])
			{
				case "0":
					notify(data[1],500,5000,'error',30,5);
				break;
				
				case "1":
					window.location.href=base_url+'bancos';
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al registrar el banco',500,5000,'error',30,5);
			$("#registrandoBanco").html('');
		}
	});
}

function formularioBancos()
{
	$('#ventanaBancos').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioBancos').html('<img src="'+ img_loader +'"/> Obteniendo los datos para registrar el banco...');
		},
		type:"POST",
		url:base_url+'bancos/formularioBancos',
		data:
		{
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#formularioBancos").html(data);
			$('#txtNombre').focus();
		},
		error:function(datos)
		{
			notify('Error al obtener los datos para registrar el banco',500,5000,'error',30,3);
			$("#formularioBancos").html('');
		}
	});
}