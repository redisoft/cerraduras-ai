
function cancelarCotizacion(idCotizacion)
{
	if(!confirm('¿Realmente desea cancelar la cotización?')) return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#cancelandoCotizacion').html('<img src="'+ img_loader +'"/>Se esta cancelando la cotización');},
		type:"POST",
		url:base_url+'ventas/cancelarCotizacion',
		data:
		{
			idCotizacion: idCotizacion
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#cancelandoCotizacion').html('');
			
			switch(data)
			{
				case "0":
					notify('Error al cancelar la cotización',500,5000,'error',30,5);
				break;
				case "1":
					notify('La cotización se ha cancelado correctamente',500,5000,'',30,5);
					
					if($('#txtRecargar').val()=="0")
					{
						obtenerCotizaciones();
					}
					else
					{
						location.reload();
					}
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al cancelar la cotización',500,4000,"error"); 
			$('#cancelandoCotizacion').html('');
		}
	}); 	  
}