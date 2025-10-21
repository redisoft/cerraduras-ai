function excelIngresos()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#cargandoIngresos').html('<img src="'+ img_loader +'"/> Se estan exportando los datos...');},
		type:"POST",
		url:base_url+'administracion/excelIngresos',
		data:
		{
			criterio:	$('#txtBuscarIngreso').val(),
			inicio:		$('#txtInicioIngresoFecha').val(),
			fin:		$('#txtFinIngresoFecha').val(),
			idCuenta:	$('#selectCuentaIngresos').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#cargandoIngresos').html('');
			
			window.location.href=base_url+'importar/descargarExportar/Ingresos'
		},
		error:function(datos)
		{
			$("#cargandoIngresos").html('');
			notify('Error al generar el reporte en excel',500,5000,'error',30,5);
		}
	});//Ajax		
}
