//CHECADOR
//-------------------------------------------------------------------------------------------------------------------------------------
$(document).ready(function()
{
	obtenerAsistencias()
	
	$('#txtBuscarPersonal').change(function() 
	{
		obtenerInformacionPersonal();
	    $('#ventanaEntradasSalidas').dialog('open');
	});
	
	$("#ventanaEntradasSalidas").dialog(
	{
		autoOpen:false,
		height:400,
		width:700,
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
				registrarChequeo();
			},
		},
		close: function() 
		{
			$('#txtBuscarPersonal').val('')
			$('#txtBuscarPersonal').focus();
		}
	});
});

function obtenerAsistencias()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#cargarEntradas').html('<label><img src="'+base_url+'img/loader.gif"/>Obteniendo el registro del personal, por favor tenga paciencia...</label>');
		},
		type:"POST",
		url:base_url+'administracion/obtenerAsistencias',
		data:
		{
			//'idPersonal': $('#txtTrabajador').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerAsistencias').html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener el registro de entradas y salidas',600,6000,"error")
			$('#obtenerAsistencias').html('');
		}
	});
}

function obtenerInformacionPersonal()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#cargarInformacionEntrada').html('<label><img src="'+base_url+'img/loader.gif"/>Se esta registrando el personal, por favor tenga paciencia...</label>');
		},
		type:"POST",
		url:base_url+'administracion/obtenerInformacionPersonal',
		data:
		{
			'numeroEmpleado': $('#txtBuscarPersonal').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#cargarInformacionEntrada').html(data);
		},
		error:function(datos)
		{
			$('#cargarInformacionEntrada').html('');
		}
	});
}

function registrarChequeo()
{
	if($('#txtRegistro').val()=="0")
	{
		$("#ventanaEntradasSalidas").dialog('close');
		return;
	}
	
	if($('#txtConfigurado').val()=="0")
	{
		notify('El horario del personal no esta configurado correctamente',600,4000,'error',30,5);
		return;
	}

	if($('#txtCompletado').val()=="1")
	{
		notify('Ya se han hechos los registros de entrada y salida de este día',600,4000,'error',30,5);
		return;
	}
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#registrandoChequeo').html('<label><img src="'+base_url+'img/loader.gif"/>Registrando, por favor tenga paciencia...</label>');
		},
		type:"POST",
		url:base_url+'administracion/registrarChequeo',
		data:
		{
			'idPersonal': $('#txtIdPersonal').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#registrandoChequeo').html('');
			notify('Registro correcto',600,4000,30,5);
			obtenerAsistencias();
			obtenerInformacionPersonal();
			$('#ventanaEntradasSalidas').dialog('close');	
		},
		error:function(datos)
		{
			$('#registrandoChequeo').html('');
		}
	});
}

$(document).ready(function()
{
	show5(); //Arrancar el reloj
});

function show5()
{
	try
	{
		if (!document.layers&&!document.all&&!document.getElementById)
		return
		
		var Digital		=new Date()
		var hours		=Digital.getHours()
		var minutes		=Digital.getMinutes()
		var seconds		=Digital.getSeconds()
		
		var dn="PM"
		if (hours<12)
		dn="AM"
		if (hours>12)
		hours=hours-12
		if (hours==0)
		hours=12
		
		if (minutes<=9)
		minutes="0"+minutes
		if (seconds<=9)
		seconds="0"+seconds
		//change font size here to your desire
		myclock="<font size='5' face='Arial' ><b><font size='1'>Hora actual:</font></br>"+hours+":"+minutes+":"
		+seconds+" "+dn+"</b></font>"
		
		if (document.layers)
		{
			document.layers.liveclock.document.write(myclock)
			document.layers.liveclock.document.close()
		}
		else if (document.all)
		liveclock.innerHTML=myclock
		else if (document.getElementById)
		document.getElementById("liveclock").innerHTML=myclock
		document.getElementById("relojDigital").innerHTML=myclock
		
		setTimeout("show5()",1000)
	}
	catch(error)
	{
		//alert(error)
	}
}