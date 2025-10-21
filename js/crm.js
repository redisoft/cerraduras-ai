function opcionesSeguimiento()
{
	seguimientos	= new String($('#selectStatus').val());
	seguimiento	= seguimientos.split('|');
	
	switch(seguimiento[1])
	{
		case "4":
		$('#filaServicio').fadeOut();
		$('#filaLugar').fadeOut();
		$('#filaComentarios').fadeIn();
		$('#filaBitacora').fadeOut();
		$('#filaEnviarBitacora').fadeOut();
		$('#filaCierre').fadeIn();
		$('#filaRecordatorio').fadeIn();
		$('#txtIdCotizacionCrm').val('0');
		$('#txtIdVentaCrm').val('0');
		
		$('#txtBuscarVentaCrm').val('');
		$('#txtBuscarCotizacionCrm').val('');
		
		$('#txtIdCompraCrm').val('0');
		$('#txtBuscarCompraCrm').val('');
		$('#txtIdClienteBusquedaCrm').val('0');
		$('#txtIdProveedorBusquedaCrm').val('0');
		
		
		if($('#txtTipoRegistro').val()=='prospectos' && seguimiento[0]=='6')
		{
			$('#filaArchivosSeguimiento').fadeIn(1);
		}
		else
		{
			$('#filaArchivosSeguimiento').fadeOut(1);
		}
		
		break;
		
		case "3":
		$('#filaServicio').fadeOut();
		$('#filaComentarios').fadeOut();
		$('#filaBitacora').fadeIn();
		$('#filaLugar').fadeIn();
		$('#filaCierre').fadeOut();
		$('#filaEnviarBitacora').fadeIn();
		$('#txtComentarios').val('');
		$('#filaRecordatorio').fadeOut();
		$('#txtIdCotizacionCrm').val('0');
		$('#txtIdVentaCrm').val('0');
		
		$('#txtBuscarVentaCrm').val('');
		$('#txtBuscarCotizacionCrm').val('');
		
		$('#txtIdCompraCrm').val('0');
		$('#txtBuscarCompraCrm').val('');
		$('#txtIdClienteBusquedaCrm').val('0');
		$('#txtIdProveedorBusquedaCrm').val('0');
		
		$('#filaArchivosSeguimiento').fadeOut(1);
		
		break;
		
		default:
		$('#filaServicio').fadeIn();
		$('#filaLugar').fadeIn();
		$('#filaComentarios').fadeIn();
		$('#filaCierre').fadeIn();
		$('#filaBitacora').fadeOut();
		$('#txtBitacora').val('');
		$('#filaEnviarBitacora').fadeOut();
		$('#filaRecordatorio').fadeIn();
		$('#filaArchivosSeguimiento').fadeOut(1);
		
		break;
	}
}

function opcionesServicios()
{
	switch($('#selectServicio').val())
	{
		case "1":
			$('#txtBuscarCotizacionCrm').fadeIn();
			$('#txtBuscarVentaCrm').fadeOut();
			$('#txtBuscarCompraCrm').fadeOut();
			$('#txtIdVentaCrm').val('0');
			$('#txtBuscarCotizacionCrm').val('');
			$('#txtIdCotizacionCrm').val('0');
			$('#txtIdClienteBusquedaCrm').val('0');
			$('#txtIdProveedorBusquedaCrm').val('0');
			$('#txtIdCompraCrm').val('0');
		break;
		
		case "2":
			$('#txtBuscarCotizacionCrm').fadeOut();
			$('#txtBuscarVentaCrm').fadeIn();
			$('#txtBuscarCompraCrm').fadeOut();
			$('#txtIdVentaCrm').val('0');
			$('#txtBuscarVentaCrm').val('');
			$('#txtIdClienteBusquedaCrm').val('0');
			$('#txtIdCotizacionCrm').val('0');
			$('#txtIdProveedorBusquedaCrm').val('0');
			$('#txtIdCompraCrm').val('0');
		break;
		
		case "3":
			$('#txtBuscarCotizacionCrm').fadeOut();
			$('#txtBuscarVentaCrm').fadeOut();
			$('#txtBuscarCompraCrm').fadeIn();
			$('#txtIdCotizacionCrm').val('0');
			$('#txtIdVentaCrm').val('0');
			$('#txtBuscarCompraCrm').val('');
			$('#txtIdClienteBusquedaCrm').val('0');
			$('#txtIdProveedorBusquedaCrm').val('0');
			$('#txtIdCompraCrm').val('0');
		break;
		
		default:
			$('#txtBuscarCotizacionCrm').fadeOut();
			$('#txtBuscarVentaCrm').fadeOut();
			$('#txtBuscarCompraCrm').fadeOut();
			$('#txtIdCotizacionCrm').val('0');
			$('#txtIdVentaCrm').val('0');
			$('#txtIdClienteBusquedaCrm').val('0');
			
			$('#txtBuscarCotizacionCrm').val('');
			$('#txtBuscarVentaCrm').val('');
			$('#txtBuscarCompraCrm').val('');
			$('#txtIdClienteBusquedaCrm').val('0');
			$('#txtIdProveedorBusquedaCrm').val('0');
			$('#txtIdCompraCrm').val('0');
			
		break;
	}
	
}

function sugerirCorreo()
{
	responsables	= $('#selectResponsable').val();
	responsable		= responsables.split("|");
	
	$('#txtEmailSeguimiento').val(responsable[1])
}