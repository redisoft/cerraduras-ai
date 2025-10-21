$(document).ready(function()
{	
	//FORMULARIO PARA AGREGAR LOS INGRESOS
	$("#ventanaFormularioIngresos").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:550,
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
				registrarIngreso();
			},
		},
		close: function() 
		{
			$("#formularioCobrosPreinscritos").html('');
		}
	});
});

function formularioCobrosPreinscritos(idCliente)
{
	$('#ventanaFormularioIngresos').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioIngresos').html('<img src="'+ img_loader +'"/> Obteniendo el formulario de  ingresos, por favor espere...');
		},
		type:"POST",
		url:base_url+'produccion/formularioCobrosPreinscritos',
		data:
		{
			idCliente:idCliente
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#formularioCobrosPreinscritos').html(data);
			window.setTimeout("catalogos()",1000);
			$('#txtTotal').focus()
		},
		error:function(datos)
		{
			notify('Error al obtener el formulario de otros ingresos',500,5000,'error',2,5);
			$('#formularioCobrosPreinscritos').html('');
		}
	});					  	  
}

//REGISTRAR INGRESO
function registrarIngreso()
{
	mensaje		="";
	idNombre	=0;
	
	if($('#txtFechaIngreso').val()=="")
	{
		mensaje+="La fecha es incorrecta <br />";
	}
	
	importe	= $('#txtImporte').val();
	importe	=importe.replace(',','');
	importe	=importe.replace(',','');
	
	if(importe=="0" || Solo_Numerico(importe)=="")
	{
		mensaje+="El importe es incorrecto <br />";
	}
	
	if(Solo_Numerico($('#txtCantidad').val())=="" || $('#txtCantidad').val()=="0")
	{
		mensaje+="La cantidad es incorrecta <br />";
	}
	
	if($('#txtIdVenta').val()!="0")
	{
		if(obtenerNumeros($('#txtTotal').val()) > obtenerNumeros($('#txtSaldoVenta').val()))
		{
			mensaje+="El total del ingreso es mayor al saldo <br />";
		}
		
		if(obtenerNumeros($('#selectIva').val()) != obtenerNumeros($('#txtTasaImpuestoVenta').val()))
		{
			mensaje+="El impuesto del ingreso no es correcto <br />";
		}
	}
	
	if($('#selectFormas').val()!="4")
	{
		if($('#selectBancos').val()=="0")
		{
			mensaje+="Por favor seleccione el banco <br />";
		}
		
		if($('#selectCuentas').val()=="0")
		{
			mensaje+="Por favor seleccione la cuenta <br />";
		}
	}

	if($('#selectFormas').val()!="3" && $('#selectFormas').val()!="2")
	{
		$('#txtNumeroTransferencia').val('');
		$('#txtNumeroCheque').val('');
		$('#txtNombreReceptor').val('');
		idNombre	=0;
	}
	
	if($('#selectTipoPago').val()=="2")
	{
		$('#txtNumeroTransferencia').val('');
		idNombre	=$('#selectNombres').val();
		
		if($('#txtNumeroCheque').val()=="")
		{
			mensaje+="Número de cheque invalido <br />";
		}
	}

	if($('#selectTipoPago').val()=="3")
	{
		$('#txtNumeroCheque').val('');
		idNombre	=0;
	}

	if(mensaje.length>0)
	{
		notify(mensaje,500,6000,'error',30,5);
		return;
	}
	
	if(!confirm('¿Realmente desea registrar el ingreso?')) return;
	
	var formData = new FormData($('#frmIngresos')[0]);
	
	$.ajax(
	{
		async:false,
		beforeSend:function(objeto)
		{
			$('#agregandoIngresos').html('<img src="'+ img_loader +'"/> Registrando el ingreso, por favor espere...');
		},
		type:"POST",
		url:base_url+'produccion/registrarIngreso',
		cache: false,
		contentType: false,
		processData: false, 
		data: formData,
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#agregandoIngresos').html('');
			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
					notify(data[1],500,5000,'error',30,5);
				break;
				
				case "1":
					notify(data[1],500,5000,'',30,5);
			
					$('#ventanaFormularioIngresos').dialog('close');
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al registrar el ingreso',500,5000,'error',0,0);
			$('#agregandoIngresos').html('');
		}
	});					  	  
}


