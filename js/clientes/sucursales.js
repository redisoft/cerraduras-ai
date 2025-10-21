//FICHEROS

var s=0;
$(document).ready(function()
{
	
	$("#ventanaSucursalesCliente").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:400,
		width:800,
		modal:true,
		resizable:false,
		
		buttons: 
		{
			'Cancelar': function() 
			{
				$(this).dialog('close');				 
			},
			'Aceptar': function() 
			{
				registrarSucursalesCliente()
			},
		},
		close: function()
		{
			$("#obtenerSucursalesCliente").html('');
		}
	});
});

function obtenerSucursalesCliente(idCliente)
{
	$('#ventanaSucursalesCliente').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerSucursalesCliente').html('<img src="'+ img_loader +'"/> Obteniendo registro, por favor espere...');
		},
		type:"POST",
		url:base_url+'clientes/obtenerSucursalesCliente',
		data:
		{
			"idCliente":idCliente,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerSucursalesCliente').html(data);
			
			s=obtenerNumeros($('#txtNumeroSucursales').val());
		},
		error:function(datos)
		{
			$('#obtenerSucursalesCliente').html('');
			notify('Error al obtener el registro',500,5000,'error',0,0);
		}
	});		
}

function comprobarSucursal()
{
	numeroSucursales	= obtenerNumeros($('#txtNumeroSucursales').val());
	
	for(i=0;i<=numeroSucursales;i++)
	{
		idLicencia	= obtenerNumeros($('#txtIdLicencia'+i).val())
		
		if(idLicencia>0)
		{
			return false;
			
			if(idLicencia==obtenerNumeros($('#selectSucursalesRegistro').val())) return false;
		}
	}
	
	return true;
}

function quitarSucursal(r)
{
	$('#filaSucursal'+r).remove()
}

function cargarSucursalCliente()
{
	if(!comprobarSucursal())
	{
		notify('¡Solo puede cargar una sucursal!',500,5000,'error',30,5);
		return;
	}
	
	data='<tr id="filaSucursal'+s+'">';
	data+='<td>'+$("#selectSucursalesRegistro option:selected").text()+'</td>';
	data+='<td align="center"><img src="'+base_url+'img/borrar.png" onclick="quitarSucursal('+s+')" title="Borrar" width="22" /><br><a>Borrar</a></td>';
	data+='<input type="hidden" name="txtIdLicencia'+s+'" id="txtIdLicencia'+s+'" value="'+$('#selectSucursalesRegistro').val()+'"/>';
	data+='</tr>';
	
	$('#tablaSucursales').append(data);
	
	s++;
	
	$('#txtNumeroSucursales').val(s)
}

function registrarSucursalesCliente()
{
	if(!confirm('¿Realmente desea continuar con el registro?'))return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#registrandoSucursales').html('<img src="'+ img_loader +'"/> Registrando, por favor espere...');
		},
		type:"POST",
		url:base_url+'clientes/registrarSucursalesCliente',
		data: $('#frmSucursales').serialize(),
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#registrandoSucursales').html('')
			data= eval(data);
			
			switch(data[0])
			{
				case "0":
					notify('¡Error em el registro!',500,5000,'error',30,5);
				break;
				
				case "1":
					notify('¡El registro ha sido correcto!',500,5000,'',30,5);
					obtenerSucursalesCliente($('#txtIdClienteSucursal').val());
				break;
				
			}//switch
		},
		error:function(datos)
		{
			$('#registrandoSucursales').html('');
			notify('¡Error en el registro!',500,5000,'error',0,0);
		}
	});		
}