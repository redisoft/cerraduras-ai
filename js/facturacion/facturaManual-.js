fila=1;

function formularioFacturaManual()
{
	$('#ventanaFacturaManual').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#formularioFacturaManual').html('<img src="'+ img_loader +'"/>Obteniendo detalles para la factura, por favor espere...');},
		type:"POST",
		url:base_url+'facturacion/formularioFacturaManual',
		data:
		{
			//"idCuenta": idCuenta,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#formularioFacturaManual").html(data);
			fila=1;
		},
		error:function(datos)
		{
			$("#formularioFacturaManual").html('');
			notify('Error al obtener los datos para la factura',500,5000,'error',30,3);
		}
	});				  	  
}

$(document).ready(function()
{
	$("#ventanaFacturaManual").dialog(
	{
		autoOpen:false,
		height:500,
		width:1000,
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
				registrarFacturaManual();
			},
		},
		close: function() 
		{
			$("#formularioFacturacion").html('');
		}
	});
});

function quitarConceptoFactura(i)
{
	$('#filaFactura'+i).remove();
}

function cargarConceptoFactura()
{
	data='<tr id="filaFactura'+fila+'">';
	data+='<td><input type="hidden" id="txtIdConcepto'+fila+'" name="txtIdConcepto'+fila+'" value="'+fila+'" />';
	data+='<img src="'+base_url+'img/borrar.png" onclick="quitarConceptoFactura('+fila+')" width="22" /></td>';
	//data+='<td align="center"><input type="text" class="cajas" id="txtConceptoFactura'+fila+'" name="txtConceptoFactura'+fila+'" style="width:320px"></td>';
	data+='<td align="center"><textarea  class="TextArea" id="txtConceptoFactura'+fila+'" name="txtConceptoFactura'+fila+'" style="width:320px; heigth: 50px"></textarea></td>';
	data+='<td align="center"><input type="text" class="cajas" id="txtUnidadFactura'+fila+'" name="txtUnidadFactura'+fila+'" style="width:100px" value="Pieza"></td>';
	data+='<td align="center"><input type="text" class="cajas" id="txtCantidadFactura'+fila+'" name="txtCantidadFactura'+fila+'" maxlength="12" style="width:50px" onkeypress="return soloDecimales(event)" onchange="calcularTotalesFactura()" ></td>';
	data+='<td align="center"><input type="text" class="cajas" id="txtPrecioFactura'+fila+'" name="txtPrecioFactura'+fila+'" maxlength="12" style="width:80px" onkeypress="return soloDecimales(event)" onchange="calcularTotalesFactura()" ></td>';
	data+='<td align="center"><input type="text" readonly="readonly" class="cajas" id="txtImporteFactura'+fila+'" name="txtImporteFactura'+fila+'" style="width:80px" ></td>';
	data+='</tr>';
	
	fila++;
	$('#txtNumeroProductos').val(fila);
	$('#tablaFacturacion').append(data);
}

function calcularTotalesFactura() //Calcular los importes por conceptos
{
	subTotal	= 0;
	iva			= 0;
	total		= 0;
	
	for(i=1;i<=fila;i++)
	{
		if(!isNaN($('#txtIdConcepto'+i).val()))
		{
			cantidad	=obtenerNumero($('#txtCantidadFactura'+i).val());
			precio		=obtenerNumero($('#txtPrecioFactura'+i).val());

			i/*f(isNaN(cantidad) || cantidad==0 || Solo_Numerico(cantidad)=="")
			{
				cantidad=0;
			}
			
			if(isNaN(precio) || precio==0 || Solo_Numerico(precio)=="")
			{
				precio=0;
			}*/
			
			subTotal+=precio*cantidad;
			
			$('#txtImporteFactura'+i).val(redondear(precio*cantidad))
		}
	}

	iva		= obtenerNumero($('#selectIva').val())/100;
	iva		= iva*subTotal;
	total	= subTotal+iva;
	
	$('#txtSubTotal').val(redondear(subTotal))
	$('#txtIva').val(redondear(iva))
	$('#txtTotal').val(redondear(total))
	
	$('#lblSubTotal').html('$'+redondear(subTotal))
	$('#lblIva').html('$'+redondear(iva))
	$('#lblTotal').html('$'+redondear(total))
	
}

function registrarFacturaManual()
{
	if($('#txtFoliosActivos').val()=="0")
	{
		notify('Los folios se han terminado, por favor consulte con el administrador',500,5000,'error',30,5);
		return;
	}
	
	var mensaje="";
	
	if($("#selectEmisores").val()=="0")
	{
		mensaje+="Seleccione el emisor <br />";										
	}
	
	if($("#txtIdClienteGlobal").val()=="0")
	{
		mensaje+="Seleccione el cliente <br />";										
	}
	
	if($("#txtSubTotal").val()=="0")
	{
		mensaje+="El importe de la factura es incorrecto <br />";										
	}
	
	b=1;
	
	for(i=1;i<=fila;i++)
	{
		if(!isNaN($('#txtIdConcepto'+i).val()))
		{
			/*cantidad	=obtenerNumero($('#txtCantidadFactura'+i).val());
			precio		=parseFloat($('#txtPrecioFactura'+i).val());*/

			if(obtenerNumero($('#txtCantidadFactura'+i).val())==0)
			{
				b=0;
				break;
			}
			
			if(obtenerNumero($('#txtPrecioFactura'+i).val())==0)
			{
				b=0;
				break;
			}
			
			if(!camposVacios($('#txtConceptoFactura'+i).val()) || !camposVacios($('#txtUnidadFactura'+i).val()))
			{
				b=0;
				break;
			}
		}
	}
	
	if(b==0)
	{
		mensaje+="Configure correctamente los conceptos de la factura <br />";
	}
	
	if(mensaje.length>0)
	{
		notify(mensaje,500,5000,'error',30,0);
		return;
	}
	
	if(confirm('¿Realmente desea registrar la factura?')==false)
	{
		return;
	}

	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#registrandoFacturaManual').html('<img src="'+ img_loader +'"/>Se esta creando la factura, por favor espere...');},
		type:"POST",
		url:base_url+"facturacion/registrarFacturaManual",
		data:
		$('#frmFacturacion').serialize(),
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#registrandoFacturaManual").html('');
			data=eval(data);
			switch(data[0])
			{
				case "0":
					notify(data[1],500,5000,'error',30,3);
				break;
				case "1":
					window.location.href=base_url+"reportes/facturacion";
				break;
				
			}
		},
		error:function(datos)
		{
			$("#registrandoFacturaManual").html('');
			notify('Error al realizar la factura',500,5000,'error',30,3);
		}
	});		
}


