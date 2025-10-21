//function obtenerProductoCotizacion(descripcion,idProducto,precioA,precioB,precioC,precioD,precioE,servicio,idPeriodo,factor,valor) 
$(document).ready(function()
{
	$('#txtFechaEntrega,#txtFechaCotizacion').datetimepicker()
	
	$("#txtBuscarProducto").autocomplete(
	{
		source:base_url+'configuracion/obtenerProductosPedido',
		
		select:function( event, ui)
		{
			obtenerProductoCotizacion(ui.item.nombre,ui.item.idProducto,ui.item.precioA,ui.item.precioB,ui.item.precioC,ui.item.precioD,ui.item.precioE);
			
		}
	});
});

function obtenerProductoCotizacion(descripcion,idProducto,precioA,precioB,precioC,precioD,precioE) 
{
	precio			=0;
	precioActual	=0;
	servicio 		=0;
	idPeriodo		=0;
	valor			=0;
	factor			=0;
	
	if(isNaN(precioA))
	{
		return;
	}
	
	i=$('#contador').val();
	fechaServicio	='';
	
	if(servicio=="1")
	{
		fechaServicio=', Fecha inicio <input type="text" id="txtFechaInicio'+i+'" name="txtFechaInicio'+i+'" class="cajas" style="width:125px" /> ';
	}
	
	fechaServicio+='<input type="hidden" id="txtIdPeriodo'+i+'" name="txtIdPeriodo'+i+'" value="'+idPeriodo+'"/>';
	fechaServicio+='<input type="hidden" id="txtFactor'+i+'" name="txtFactor'+i+'" value="'+factor+'"/>';
	fechaServicio+='<input type="hidden" id="txtValor'+i+'" name="txtValor'+i+'" value="'+valor+'"/>';
	
	precioCliente=$('#precioCliente').val();
	
	a='';
	b='';
	c='';
	d='';
	e='';
	
	if(precioCliente=="1") {a='selected="selected"'; precioActual=precioA};
	if(precioCliente=="2") {b='selected="selected"'; precioActual=precioB};
	if(precioCliente=="3") {c='selected="selected"'; precioActual=precioC};
	if(precioCliente=="4") {d='selected="selected"'; precioActual=precioD};
	if(precioCliente=="5") {e='selected="selected"'; precioActual=precioE};
	
	preciosA=Math.round(precioA*100)/100;
	preciosB=Math.round(precioB*100)/100;
	preciosC=Math.round(precioC*100)/100;
	preciosD=Math.round(precioD*100)/100;
	preciosE=Math.round(precioE*100)/100;
	
	precios='<select style="width:100px" class="cajas" id="id_tpo_'+i+'" name="id_tpo_'+i+'" \
			  onchange="cambiarPrecioTipo('+i+')" >';
	precios+='<option value="'+precioA+'" '+a+'>'+preciosA+'</option>';
	precios+='<option value="'+precioB+'" '+b+'>'+preciosB+'</option>';
	precios+='<option value="'+precioC+'" '+c+'>'+preciosC+'</option>';
	precios+='<option value="'+precioD+'" '+d+'>'+preciosD+'</option>';
	precios+='<option value="'+precioE+'" '+e+'>'+preciosE+'</option>';
	precios+='</select>';
	
	HTML='<tr id="rows_'+i+'">';
	HTML+='<td align="center" valign="middle"><img src="'+base_url+'img/quitar.png"'+
			'onclick="RemoveFilaProducto('+i+',\'rows_\')" ';
	HTML+=' width="16" title="Quitar de la lista" style="cursor:pointer;" /> </td>';
	HTML+='<td align="center" valign="middle">';
    HTML+='<input onchange="calculandoPrecios()" type="text" name="id_canti_'+i+'" alt="'+i+'" id="id_canti_'+i+'" value="1"  ';
    HTML+='class="cajas" style="width:90%;"  alt="'+i+'"> </td>';
	HTML+='<td align="center" valign="middle">';
    
	//HTML+='<textarea name="id_descr_'+i+'" alt="'+i+'" id="id_descr_'+i+'" \
	//		class="TextArea" cols="16" rows="1">'+descripcion+'</textarea>';
    HTML+=descripcion;
	HTML+=fechaServicio;
	HTML+='</td>';
	
	precioActual=Math.round(precioActual*100)/100;
	
	HTML+='<td align="center" valign="middle">'+precios;

	HTML+='<input type="hidden" name="txtServicio'+i+'"  id="txtServicio'+i+'"   value="'+servicio+'"  />';

	HTML+='<input type="hidden" name="id_precioNormal_'+i+'" id="id_precioNormal_'+i+'"  value="'+precioActual+'"  />';
	HTML+='<input type="hidden" name="id_precio_conver_'+i+'" id="id_precio_conver_'+i+'" value="'+precioActual+'"  />';
	HTML+='</td>';
	HTML+='<td align="right" valign="middle">';
    HTML+='<label id="id_precio_t_impor_'+i+'">'+precioActual+'</label>';
    HTML+='<input type="hidden" name="id_precio_t_'+i+'"id="id_precio_t_'+i+'" alt="'+i+'" value="'+precioActual+'"/>';
    HTML+='<input type="hidden" name="id_p_'+i+'"id="id_p_'+i+'" alt="'+i+'" value="'+idProducto+'" /></td>';
    HTML+='</tr>';
	
	$('#inputString').val('');
	//$('#idProducto').val(idProducto);
	setTimeout("$('#suggestions').hide();", 200);
	
	$('#id_canti_'+i).focus();
	
	HTML+='<script>\
		$("#txtFechaInicio'+i+'").datetimepicker({ changeMonth: true });\
	</script>';
	
	i++;
	
	$('#contador').val(i);

	$('#FormularioCotizacion').append(HTML);
	
	calculandoPrecios();
}

function RemoveFilaProducto(No,Row)
{
	var _No= parseInt($("#T").val());
	
	$("#"+Row+No).remove();
	
	_No=_No-1;
	$("#T").val(_No);
	
	calculandoPrecios();
}

function calculandoPrecios()
{
	$("#actualizandoPrecios").fadeIn();
	window.setTimeout("calcularPreciasos()",100);
}

function cambiarPrecioTipo(n)
{
	$("#id_precio_"+n).val(Math.round($("#id_tpo_"+n).val()*100)/100);
	$("#id_precioNormal_"+n).val($("#id_tpo_"+n).val());
	$("#id_precio_conver_"+n).val($("#id_tpo_"+n).val());
	
	cantidad	=parseInt($("#id_canti_"+n).val())
	total		=cantidad*parseFloat($("#id_tpo_"+n).val());
	
	totalito	=Math.round(total*100)/100;

	$("#id_precio_t_impor_"+n).html(number_format(total,"2","",""));
	$("#id_precio_t_"+n).val(total);	
	
	obtener_importesN();
}

function calcularPreciasos()
{
	var i=parseInt($("#contador").val());
	
	for(cont=1;cont<i;cont++)
	{
		var unitario = parseFloat($("#id_precio_conver_"+cont).val());
		var cant = parseInt($("#id_canti_"+cont).val());

		if(isNaN(unitario))
		{
			unitario = 0;
		}
		
		if(isNaN(cant))
		{
			//alert("Ingresa valor");
			//$("#id_precio_"+cont).focus();
			//return;
		}
	
		var importe = unitario * cant;
	
		$("#id_precio_t_impor_"+cont).html("$ "+number_format(importe,"2","",""));
		$("#id_precio_t_"+cont).val(importe);
	}
	 
	 obtener_importesN();//Obtiene el total del Importe = SubTotal
}

function obtener_importesN()
{
	var contador_init	=parseInt($("#contador").val());
	var subTotal_real 	=parseFloat($("#TSubTotal").val());//El subtotal
	
	var total=0;
	
	for(var i=0; i<= contador_init; i++)
	{
		var importe = parseFloat($("#id_precio_t_"+i).val());
		
		if(isNaN(importe))
		{
			importe = 0;
		}
		total = total + importe;
	}
	
	$("#TSubTotal").val(total);
	$("#TLSubTotal").html(number_format(total,"2","",""));
	
	calcular_total(0);
}

function calcular_total(importe_parm)
{
	_iva 	= $("#TIVA").val();
	_iva 	= parseFloat(_iva);
	
	_desc 	= $("#TDesc").val();
	_desc 	= (parseFloat(_desc)/100);
	
	var importe_base 	= parseFloat($("#TSubTotal").val());
	var subtotal 		= importe_base + parseFloat(importe_parm);
	
	$("#TLSubTotal").html(number_format(subtotal,"2","",""));
	$("#TSubTotal").val(subtotal);
	
	iva 	= (subtotal * _iva);      
	iva 	= redondeo2decimales(iva);
	
	total 	= subtotal + iva;
	total 	= redondeo2decimales(total);
	
	todo 	= total*_desc;
	todo 	= redondeo2decimales(todo);
	todo 	= total -todo;
	todo 	= redondeo2decimales(todo);
	
	$("#TLTotal").html(number_format(todo,"2","",""));
	$("#TTotal").val(todo);
	
	$("#actualizandoPrecios").fadeOut();
	$('#txtBuscarProducto').val('');
}

function checarCotizacion()
{
	mensaje="";
	
	if($('#txtFechaCotizacion').val()=="")
	{
		mensaje+='La fecha de la cotización es incorrecta <br />';
	}
	
	if($('#txtFechaEntrega').val()=="")
	{
		mensaje+='La fecha de entrega es incorrecta <br />';
	}

	if(Solo_Numerico($('#txtDias').val())=="")
	{
		mensaje+='Los días de crédito son incorrectos <br />';
	}
	
	if(parseFloat($("#TTotal").val())<=0)
	{
		mensaje+='La cotización no  tiene productos o es incorrecta <br />';
	}
	
	if(mensaje.length>0)
	{
		notify(mensaje,500,6000,"error",30,5);
		return;	
	}
	
	
	document.forms['formcotizar'].submit();
}