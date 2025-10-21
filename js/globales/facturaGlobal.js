function formularioFacturaGlobal()
{
	$('#ventanaFacturaGlobal').dialog('open');
	
	$.ajax(
	{
		async:false,
		beforeSend:function(objeto){$('#formularioFacturaGlobal').html('<img src="'+ img_loader +'"/>Obteniendo detalles para la factura, por favor espere...');},
		type:"POST",
		url:base_url+'globales/formularioFacturaGlobal',
		data:
		{
			inicio:		$('#FechaDia').val(),
			fin:		$('#FechaDia2').val(),
			criterio:	$('#txtCriterio').val(),
			idLicencia:	$('#selectLicencias').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#formularioFacturaGlobal").html(data);
		},
		error:function(datos)
		{
			$("#formularioFacturaGlobal").html('');
			notify('Error al obtener los datos para la factura',500,5000,'error',30,3);
		}
	});				  	  
}

$(document).ready(function()
{
	$("#ventanaFacturaGlobal").dialog(
	{
		autoOpen:false,
		height:500,
		width:900,
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
				registrarFacturaGlobal();
			},
		},
		close: function() 
		{
			$("#formularioFacturaGlobal").html('');
		}
	});
});


function registrarFacturaGlobal()
{
	/*if($('#txtFoliosActivos').val()=="0")
	{
		notify('Los folios se han terminado, por favor consulte con el administrador',500,5000,'error',30,5);
		return;
	}*/
	
	var mensaje="";
	
	if($("#selectEmisoresGlobal").val()=="0")
	{
		mensaje+="Seleccione el emisor <br />";										
	}
	
	if($("#txtIdClienteGlobal").val()=="0")
	{
		mensaje+="Seleccione el cliente <br />";										
	}
	
	if(parseFloat($("#txtTotalesFacturaGlobal").val())=="0")
	{
		mensaje+="El importe de la factura es incorrecto <br />";										
	}
	
	/*if(!camposVacios($("#txtConceptoGlobal").val()))
	{
		mensaje+="El concepto es incorrecto <br />";										
	}*/
	
	b=1;
	
	
	if(mensaje.length>0)
	{
		notify(mensaje,500,5000,'error',30,0);
		return;
	}
	
	if(!confirm('¿Realmente desea registrar la factura?')) return;

	$.ajax(
	{
		async:false,
		beforeSend:function(objeto){$('#registrandoFacturaGlobal').html('<img src="'+ img_loader +'"/>Se esta creando la factura, por favor espere...');},
		type:"POST",
		url:base_url+"globales/registrarFacturaGlobal",
		data:
		//$('#frmFacturacion').serialize(),
		$('#frmFacturacion').serialize()
		+'&metodoPagoTexto='+$("#selectMetodoPago option:selected").text()+'&formaPagoTexto='+$("#selectFormaPago option:selected").text()+'&usoCfdiTexto='+$("#selectUsoCfdi option:selected").text()
		+'&inicio='+$("#FechaDia").val()+'&fin='+$("#FechaDia2").val()+'&criterio='+$("#txtCriterio").val()+'&idLicencia='+$("#selectLicencias").val(),
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#registrandoFacturaGlobal").html('');
			data=eval(data);
			
			switch(data[0])
			{
				case "0":
					notify(data[1],500,5000,'error',30,5);
				break;
				case "1":
					$('#ventanaFacturaGlobal').dialog('close');
					notify('La factura se ha registrado corractamente',500,5000,'error',30,5);
					obtenerVentasGlobal();
				break;
				
			}
		},
		error:function(datos)
		{
			$("#registrandoFacturaGlobal").html('');
			notify('Error al realizar la factura',500,5000,'error',30,3);
		}
	});		
}


