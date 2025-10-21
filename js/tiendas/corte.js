$(document).ready(function()
{
	$("#ventanaCortes").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:370,
		width:750,
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
				registrarCorte()		  	  
			},
			
		},
		close: function() 
		{
			$("#formularioCorte").html('');
		}
	});
});

function formularioCorte()
{
	$('#ventanaCortes').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioCorte').html('<img src="'+ img_loader +'"/> Obteniendo detalles de ventas para el corte...');
		},
		type:"POST",
		url:base_url+'tiendas/formularioCorte',
		data:
		{
			corte: '1'
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#formularioCorte').html(data);
			$('#txtEfectivo').focus();
		},
		error:function(datos)
		{
			$('#formularioCorte').html('');
		}
	});		
}

function registrarCorte()
{
	mensaje			="";
	
	if(!comprobarNumeros($("#txtTotalCorte").val()) || parseFloat($("#txtTotalCorte").val())==0 )
	{
		mensaje+="No existen registros de ventas para el corte <br />";
	}
	
	if(!comprobarNumeros($("#txtEfectivo").val()) || parseFloat($("#txtEfectivo").val())==0 )
	{
		mensaje+="El efectivo es incorrecto <br />";
	}

	if(mensaje.length>0)
	{
		notify(mensaje,500,5000,'error',30,5);
		return;
	}
	
	if(!confirm('¿Realmente deseea registrar el corte de caja?')) return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#registrandoCorte').html('<img src="'+ img_loader +'"/> Se esta registrando el corte de caja...');},
		type:"POST",
		url:base_url+'tiendas/registrarCorte',
		data:$('#frmCorte').serialize(),
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#registrandoCorte").html('');
			
			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
				notify(data[1],500,5000,'error',30,3);
				break;
				
				case "1":
					notify('El corte de caja se ha registrado correctamente',500,5000,'',30,5);
					formularioCorte();
				break;
				
				break;
			}
		},
		error:function(datos)
		{
			$("#registrandoCorte").html('');
			notify('Error al registrar el corte de caja',500,5000,'error',30,3);
		}
	});		
}
