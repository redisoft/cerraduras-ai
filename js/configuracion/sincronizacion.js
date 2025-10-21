$(document).ready(function()
{
	$("#ventanaSincronizacion").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:370,
		width:700,
		modal:true,
		resizable:false,
		buttons: 
		{
			"cancelar" : 
			{
				text: "Cancelar",
				id: "btnCancelarSincronizacion",
				click: function()
				{
					$(this).dialog('close');
				}   
		  	},
			"Ejecutar": 
			{
				text: "Ejecutar sincronización",
				id: "btnEjecutarSincronizacion",
				click: function()
				{
					if(obtenerNumeros($("#txtTipoSincronizacion").val())==0)
					{
						actualizacionLocalNube()
					}
					else
					{
						actualizacionNubeLocal()
					}
				}   
		  	},
			
		},
		close: function() 
		{
			$("#formularioSincronizacion").html(''); 
		}
	});
});

function formularioSincronizacion(tipo)
{
	texto	= tipo==0?'Registro de ventas desde el equipo local hacia el servidor, el tiempo dependara de los recursos de su sistema y la velocidad de la conexión':'Descarga de inventarios desde el servidor hacia el equipo local,  el tiempo dependara de los recursos de su sistema y la velocidad de la conexión';
	$("#txtTipoSincronizacion").val(tipo);
	
	$('#formularioSincronizacion').html(texto+'<span id="spnInformacion"></span>');
	
	$("#ventanaSincronizacion").dialog('open');
}

function actualizacionLocalNube()
{
	$("#btnCancelarSincronizacion,#btnEjecutarSincronizacion").button("disable");
	
	if(!confirm('¿Realmente desea registrar las ventas en el servidor?')) 
	{
		$("#btnCancelarSincronizacion,#btnEjecutarSincronizacion").button("enable");
		return;
	}

	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#procesandoSincronizacion').html('<img src="'+ img_loader +'"/> El sistema esta registrando las ventas al servidor, por favor no cancele la ejecución ni cierre el navegador.');
		},
		type:"POST",
		url:base_url+"sincronizacion/actualizacionLocalNube",
		data: 
		{
			'ejecucion': 'local'
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#procesandoSincronizacion').html('');
			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
					notify(data[1],500,5000,'error',30,5);
					
					$('#spnInformacion').html('<br /><label style="color: red">'+data[1]+'</label>');
					
					$("#btnCancelarSincronizacion,#btnEjecutarSincronizacion").button("enable");
				break;
				
				case "1":
					notify(data[1],500,5000,'',30,5);
					
					$('#spnInformacion').html('<br /><label style="color: green">'+data[1]+'</label>');
					$("#btnCancelarSincronizacion,#btnEjecutarSincronizacion").button("enable");
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al ejecutar la actualización',500,5000,'error',30,5);
			$('#procesandoSincronizacion').html('');
			$("#btnCancelarSincronizacion,#btnEjecutarSincronizacion").button("enable");
		}
	});				  	  
}

function actualizacionNubeLocal()
{
	$("#btnCancelarSincronizacion,#btnEjecutarSincronizacion").button("disable");
	
	if(!confirm('El tiempo de ejecución dependera de su conexión, ¿Realmente desea descargar los inventarios del servidor?'))
	{
		$("#btnCancelarSincronizacion,#btnEjecutarSincronizacion").button("enable");
		return;
	}

	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#procesandoSincronizacion').html('<img src="'+ img_loader +'"/> El sistema esta descargando los inventarios del servidor, por favor no cancele la ejecución ni cierre el navegador.');
		},
		type:"POST",
		url:base_url+"sincronizacion/actualizacionNubeLocal",
		data: 
		{
			'ejecucion': 'remota'
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#procesandoSincronizacion').html('');
			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
					notify(data[1],500,5000,'error',30,5);
					
					$('#spnInformacion').html('<br /><label style="color: red">'+data[1]+'</label>');
					$("#btnCancelarSincronizacion,#btnEjecutarSincronizacion").button("enable");
				break;
				
				case "1":
					notify(data[1],500,5000,'',30,5);
					
					$('#spnInformacion').html('<br /><label style="color: green">'+data[1]+'</label>');
					$("#btnCancelarSincronizacion,#btnEjecutarSincronizacion").button("enable");
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al ejecutar la actualización',500,5000,'error',30,5);
			$('#procesandoSincronizacion').html('');
			$("#btnCancelarSincronizacion,#btnEjecutarSincronizacion").button("enable");
		}
	});				  	  
}
