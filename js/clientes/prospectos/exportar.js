function exportarProspectos()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#exportandoDatos').html('<img src="'+ img_loader +'"/> Se estan exportando los datos...');},
		type:"POST",
		url:base_url+'importar/exportarProspectos',
		data:
		{
			criterio:		$('#txtBusquedas').val(),
			idResponsable:	$('#selectResponsableBusqueda').val(),
			idStatus:		$('#selectStatusBusqueda').val(),
			idZona:			$('#selectZonasBuscar').val(),
			idServicio:		$('#selectServicioBusqueda').val(),
			fecha:			$('#FechaDia2').val()==""?"fecha":$('#FechaDia2').val(),
			mes:			$('#txtFechaMes').val()==""?"mes":$('#txtFechaMes').val(),
			idTipo:			$('#selectBusquedaTipo').val(),
			
			
			idPromotor:		$('#selectPromotorBusqueda').val(),
			idEstatus:		$('#selectEstatusBuscar').val(),
				
			tipoRegistro: 		$('#txtTipoRegistro').val(),
			criterioSeccion:	$('#txtCriterioSeccion').val(),
			
			fechaFin:			$('#txtFechaFin').val()==""?"fecha":$('#txtFechaFin').val(),
			
			numeroSeguimientos:	$('#selectNumeroSeguimientos').val(),
			idCampana:			$('#selectCampanasBusqueda').val(),
			idPrograma:			$('#selectProgramaBusqueda').val(),
			diaPago:			$('#selectDiaPago').val(),
			
			idFuente:			$('#selectFuentesBusqueda').val(),
			
			tipoFecha:			$('#selectTipoFecha').val(),
			inicial:			$('#txtFechaProspectosInicio').val(),
			final:				$('#txtFechaProspectosFin').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#exportandoDatos').html('');
			
			window.location.href=base_url+'importar/descargarExportar/Prospectos'
		},
		error:function(datos)
		{
			$("#exportandoDatos").html('');
			notify('Error al generar el reporte en excel',500,5000,'error',2,5);
		}
	});//Ajax		
}