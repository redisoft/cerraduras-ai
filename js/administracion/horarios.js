//HORARIOS
$(document).ready(function()
{
	$("#ventanaHorarios").dialog(
	{
		autoOpen:false,  
		show: { effect: "scale", duration: 600 },                            
		height:550,
		width:950,
		modal:true,
		resizable:false,
		buttons: 
		{
			'Registrar': function() 
			{
				registrarHorario();
			}
		},
		close: function() 
		{
			$('#obtenerHorarios').html('');
		}
	});
});

function obtenerHorarios(idPersonal)
{
	$('#ventanaHorarios').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerHorarios').html('<label><img class="ajax-loader" src="'+base_url+'img/ajax-loader.gif"/> Obteniendo detalles de horarios</label>');
		},
		type:"POST",
		url:base_url+'administracion/obtenerHorarios',
		data:
		{
			idPersonal:idPersonal
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerHorarios').html(data)
		},
		error:function(datos)
		{
			$('#obtenerHorarios').html('');
		}
	});	
}

function registrarHorario()
{
	mensaje = "";
	
	if(!document.getElementById('chkLunes').checked && !document.getElementById('chkMartes').checked
	&& !document.getElementById('chkMiercoles').checked && !document.getElementById('chkJueves').checked
	&& !document.getElementById('chkViernes').checked && !document.getElementById('chkSabado').checked
	&& !document.getElementById('chkDomingo').checked)
	{
		mensaje+=" Seleccione al menos un día de la semana <br />";
	}
	
	if(parseInt($('#txtHoraInicial').val())>parseInt($('#txtHoraFinal').val()))
	{
		mensaje+=" El horario es incorrecto<br />";
	}

	if(mensaje.length>0)
	{
		notify(mensaje,500,5000,'error',30,5);
		return;
	}

	if(!confirm('¿Realmente desea registrar el horario?')) return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#procesandoHorarios').html('<label><img class="ajax-loader" src="'+base_url+'img/ajax-loader.gif"/>Registrando horario</label>');
		},
		type:"POST",
		url:base_url+'administracion/registrarHorario',
		data:$('#frmHorario').serialize(),
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#procesandoHorarios').html('');
			
			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
					notify(data[1],500,5000,'error',30,5);
				break;
				
				default:
					notify('El horario se ha registrado correctamente ',500,5000,'',30,5);
					obtenerHorarios($('#txtIdPersonal').val());
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al registrar el horario',500,5000,'error',30,5);
			$('#procesandoHorarios').html('');
		}
	});	
}

function editarHorario(idHorario,i)
{
	mensaje = "";
	
	if(!document.getElementById('chkLunes'+i).checked && !document.getElementById('chkMartes'+i).checked
	&& !document.getElementById('chkMiercoles'+i).checked && !document.getElementById('chkJueves'+i).checked
	&& !document.getElementById('chkViernes'+i).checked && !document.getElementById('chkSabado'+i).checked
	&& !document.getElementById('chkDomingo'+i).checked)
	{
		mensaje+=" Seleccione al menos un día de la semana <br />";
	}
	
	if(parseInt($('#txtHoraInicial'+i).val())>parseInt($('#txtHoraFinal'+i).val()))
	{
		mensaje+=" El horario es incorrecto<br />";
	}

	if(mensaje.length>0)
	{
		notify(mensaje,500,5000,'error',30,3);
		return;
	}

	if(!confirm('¿Realmente desea editar el horario?')) return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#procesandoHorarios').html('<label><img class="ajax-loader" src="'+base_url+'img/ajax-loader.gif"/>Editando horario</label>');
		},
		type:"POST",
		url:base_url+'administracion/editarHorario',
		data:
		{
			idHorario: idHorario,
			horaInicial: $('#txtHoraInicial'+i).val(),
			horaFinal: $('#txtHoraFinal'+i).val(),
			lunes: document.getElementById('chkLunes'+i).checked?'1':'0',
			martes: document.getElementById('chkMartes'+i).checked?'1':'0',
			miercoles: document.getElementById('chkMiercoles'+i).checked?'1':'0',
			jueves: document.getElementById('chkJueves'+i).checked?'1':'0',
			viernes: document.getElementById('chkViernes'+i).checked?'1':'0',
			sabado: document.getElementById('chkSabado'+i).checked?'1':'0',
			domingo: document.getElementById('chkDomingo'+i).checked?'1':'0',
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#procesandoHorarios').html('');
			
			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
					notify('El registro no tuvo cambios',500,5000,'error',30,3);
				break;
				
				default:
					notify('El horario se ha editado correctamente ',500,5000,'',30,3);
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al editar el horario',500,5000,'error',30,3);
			$('#procesandoHorarios').html('');
		}
	});	
}

function borrarHorario(idHorario)
{
	if(!confirm('¿Realmente desea borrar el horario?')) return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#procesandoHorarios').html('<label><img class="ajax-loader" src="'+base_url+'img/ajax-loader.gif"/>Borrando horario</label>');
		},
		type:"POST",
		url:base_url+'administracion/borrarHorario',
		data:
		{
			idHorario: idHorario,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#procesandoHorarios').html('');
			
			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
					notify('Error al borrar el horario',500,5000,'error',30,3);
				break;
				
				default:
					notify('El horario se ha borrado correctamente ',500,5000,'',30,3);
					$('#filaHorario'+idHorario).remove();
					$('#ventanaConfirmacion').dialog('close');
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al borrar el horario',500,5000,'error',30,3);
			$('#procesandoHorarios').html('');
		}
	});	
}