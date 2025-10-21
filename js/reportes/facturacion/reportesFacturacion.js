function obtenerFacturaCancelar(idFactura)
{
	$('#ventanaCancelarFactura').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#cargandoCancelacion').html('<img src="'+ img_loader +'"/> Obteniendo detalles de factura...');},
		type:"POST",
		url:base_url+"facturacion/motivosCancelacionFactura",
		data:
		{
			"idFactura":idFactura
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#cargarFolio").html(data);
			$('#cargandoCancelacion').html("");
		},
		error:function(datos)
		{
			$("#cargandoCancelacion").html('');	
		}
	});//Ajax	
}

$(document).ready(function()
{
	$("#ventanaCancelarFactura").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:350,
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
				cancelarCFDI()					  	  
			},
			
		},
		close: function() 
		{
			$("#cargandoCancelacion").html('');	
		}
	});

	//$('.ajax-pagFactu > li a').live('click',function(eve)
	$(document).on("click", ".ajax-pagFactu > li a", function(eve)
	{
		eve.preventDefault();
		var element 	= "#obtenerFacturas";
		var link 		= $(this).attr('href');
		
		$.ajax(
		{
			url:link,
			type:"POST",
			data:
			{
				fecha:		$('#txtMes').val(),
				idCliente:		$('#txtBuscarCliente').val(),
				idFactura:		$('#txtBuscarFactura').val(),
				idEmisor:	$('#selectEmisoresBusqueda').val(),
				tipo:		$('#selectTipo').val(),
				canceladas:		$('#selectCanceladas').val(),
				idEstacion:  	$('#selectEstaciones').val(),
			},
			dataType:"html",
			beforeSend:function()
			{
				$('#obtenerFacturas').html('<img src="'+ img_loader +'"/>Obteniendo las facturas, por favor tenga paciencia...');
			},
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

function cancelarCFDI()
{
	mensaje="";
	
	if($('#selectMotivoCancelacion').val()=="01" && $('#txtIdFacturaSustitucion').val()=="0")
	{
		mensaje+=" Seleccione el folio de sustitución<br />";
	}
	
	if(mensaje.length>0)
	{
		notify(mensaje,500,5000,'error',30,3);
		return;
	}
	
	if(!confirm('¿Realmente desea cancelar el CFDI?')) return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#cargandoCancelacion').html('<img src="'+ img_loader +'"/> Se esta cancelando el CFDI, por favor tenga paciencia...');
		},
		type:"POST",
		url:base_url+"facturacion/cancelarCFDI",
		data:$('#frmCancelar').serialize()+'&motivosCancelacionSat='+$('#selectMotivoCancelacion option:selected').text(),
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#cargandoCancelacion').html("");
			
			data=eval(data);
			
			switch(data[0])
			{
				case "0":
					notify(data[1],500,5000,'error',30,3);
				break;
					
				case "1":
					location.reload(true);
				break;
			}
		},
		error:function(datos)
		{
			$('#cargandoCancelacion').html("");
			notify('Error al cancelar el CFDI, por favor verifique que el CFDI no haya sido previamente cancelado',500,5000,'error',30,3);
		}
	});//Ajax	
}

function opcionesCancelacionCfdi()
{
	$('#txtIdFacturaSustitucion').val("0");
	$('#txtBuscarFolioSustitución,#txtUuidSustitucion').val("");
	
	if($('#selectMotivoCancelacion').val()=="01")
	{
		$('#filaFolioSustitucion').fadeIn();
		$('#txtBuscarFolioSustitución').focus();
	}
	else
	{
		$('#filaFolioSustitucion').fadeOut()
	}
}
