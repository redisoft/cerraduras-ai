function revisarMetodo(m)
{
	if(!document.getElementById('chkMetodo'+m).checked)
	{
		document.getElementById('rdMetodoEmbudo0'+m).checked=false;
		document.getElementById('rdMetodoEmbudo1'+m).checked=false;
		
		$('#rdMetodoEmbudo0'+m).attr('disabled','disabled');
		$('#rdMetodoEmbudo1'+m).attr('disabled','disabled');
	}
	else
	{
		document.getElementById('rdMetodoEmbudo0'+m).checked=true;
		
		$('#rdMetodoEmbudo0'+m).attr('disabled',false);
		$('#rdMetodoEmbudo1'+m).attr('disabled',false);
	}
	
	revisarMetodos()
}

function desactivarTodo()
{
	$('#filaCualificado').fadeOut();
	$('#filaCausasCualificado').fadeOut();
	$('#filaInteresado').fadeOut();
	$('#filaDetallesCualificado').fadeOut();
	$('#txtTextoDetalle').fadeOut();
	
	$('#selectCualificado').val('2');
	$('#selectEstatusCualificado').val('1');
	$('#selectInteresado').val('2');
	$('#selectDetallesCualificado').val('1|0');
	$('#txtTextoDetalle').val('');
	
	$('#txtTextoCualificado').val('');
	$('#txtTextoCualificado').fadeOut();
}

function desactivarNoCualificados()
{
	if(obtenerNumeros($('#txtNumeroDetalles').val())>=8)
	{
		$('#filaNoCualificado').fadeIn();
		$('#filaCausasNoCualificado').fadeIn();
		
		$('#selectNoCualificado').val('0');
		$('#selectEstatusNoCualificado').val('1');
	}
	else
	{
		$('#filaNoCualificado').fadeOut();
		$('#filaCausasNoCualificado').fadeOut();
		
		$('#selectNoCualificado').val('0');
		$('#selectEstatusNoCualificado').val('1');
	}
}

function revisarMetodos(revisar)
{
	numero	= obtenerNumeros($('#txtNumeroMetodos').val())
	ban		= false;
	
	for(i=0;i<numero;i++)
	{
		if(document.getElementById('chkMetodo'+i).checked)
		{
			ban=true;
			break;
		}
	}
	
	if(!ban)
	{
		desactivarTodo();
		
		//desactivarNoCualificados()
		
		if(revisar!=1)
		{
			if(obtenerNumeros($('#txtNumeroDetalles').val())>=8)
			{
				$('#filaNoCualificado').fadeIn();
				$('#filaCausasNoCualificado').fadeIn();
				
				$('#selectNoCualificado').val('0');
				$('#selectEstatusNoCualificado').val('1');
				
				$('#txtTextoNoCualificado').val('');
				$('#txtTextoNoCualificado').fadeOut();
				
				//alert('Checkbox false');
			}
		}
		
		
		return false;
	}
	
	revisarRadiosMetodos()
	
	if(obtenerNumeros($('#txtNumeroDetalles').val())>=8)
	{
		$('#filaNoCualificado').fadeOut();
		$('#filaCausasNoCualificado').fadeOut();
		
		$('#selectNoCualificado').val('0');
		$('#selectEstatusNoCualificado').val('1');
		
		$('#txtTextoNoCualificado').val('');
		$('#txtTextoNoCualificado').fadeOut();
		
		//alert('Checkbox true');
	}
	
	return true;
}

function revisarRadiosMetodos()
{
	ban		= false;
	
	for(i=0;i<numero;i++)
	{
		if(document.getElementById('rdMetodoEmbudo1'+i).checked)
		{
			ban=true;
			break;
		}
	}
	
	if(!ban)
	{
		desactivarTodo();
		
		return false;
	}
	else
	{
		$('#filaCualificado').fadeIn();
		
		return true;
	}
}

function sugerirSelectorCualificado(m)
{
	if(m==1)
	{
		$('#filaCualificado').fadeIn();
	}
	else
	{
		$('#filaCualificado').fadeOut();
	}
}


function sugerirOpcionesCualificado()
{
	$('#selectEstatusCualificado').val('1');
	
	$('#filaDetallesCualificado').fadeOut();
	$('#selectDetallesCualificado').val('1|0');
	$('#txtTextoDetalle').fadeOut();
	$('#txtTextoDetalle').val('');
	
	$('#selectInteresado').val('2')
	
	$('#txtTextoCualificado').val('');
	$('#txtTextoCualificado').fadeOut();
	
	switch($('#selectCualificado').val())
	{
		case "0":
			$('#filaCausasCualificado').fadeIn();
			$('#filaInteresado').fadeOut();
		break;
		
		case "1":
			$('#filaCausasCualificado').fadeOut();
			$('#filaInteresado').fadeIn();
		break;
		
		default:
			$('#filaCausasCualificado').fadeOut();
			$('#filaInteresado').fadeOut();
			
		break;
	}
}

function sugerirOpcionesInteresado()
{
	$('#selectDetallesCualificado').val('1|0');
	$('#txtTextoDetalle').fadeOut();
	$('#txtTextoDetalle').val('');
	
	switch($('#selectInteresado').val())
	{
		case "0":
			$('#filaDetallesCualificado').fadeIn();
		break;
		
		case "1":
			$('#filaDetallesCualificado').fadeOut();
		break;
		
		default:
			$('#filaDetallesCualificado').fadeOut();
		break;
	}
}

function sugerirTextoCualificado()
{
	Detalles	= new String($('#selectDetallesCualificado').val());
	detalles 	= Detalles.split('|');

	if(detalles[1]==0) 
	{
		$('#txtTextoDetalle').fadeOut();
		$('#txtTextoDetalle').val('');
	}
	else
	{
		$('#txtTextoDetalle').fadeIn();
		$('#txtTextoDetalle').val('');
	}
}

//PROSPECTOS
function opcionesSelectorProspectos()
{
	$('#selectDetallesProspecto').val('1|0');
	$('#txtTextoProspecto').fadeOut();
	$('#txtTextoProspecto').val('');
	
	switch($('#selectProspectos').val())
	{
		case "5":
			$('#filaDetallesProspecto').fadeIn();
		break;
		
		default:
			
			$('#filaDetallesProspecto').fadeOut();
			
		break;
	}
}

function sugerirTextoProspecto()
{
	Detalles	= new String($('#selectDetallesProspecto').val());
	detalles 	= Detalles.split('|');

	if(detalles[1]==0) 
	{
		$('#txtTextoProspecto').fadeOut();
		$('#txtTextoProspecto').val('');
	}
	else
	{
		$('#txtTextoProspecto').fadeIn();
		$('#txtTextoProspecto').val('');
	}
}

function sugerirTextoCualificadoCausa()
{
	switch($('#selectEstatusCualificado').val())
	{
		case "5":
			$('#txtTextoCualificado').val('');
			$('#txtTextoCualificado').fadeIn();
		break;
		
		default:
			$('#txtTextoCualificado').val('');
			$('#txtTextoCualificado').fadeOut();
		break;
	}
}


//CUANDO HAYA AL MENOS 8 SEGUIMIENTOS
function sugerirTextoNoCualificado()
{
	switch($('#selectEstatusNoCualificado').val())
	{
		case "5":
			$('#txtTextoNoCualificado').val('');
			$('#txtTextoNoCualificado').fadeIn();
		break;
		
		default:
			$('#txtTextoNoCualificado').val('');
			$('#txtTextoNoCualificado').fadeOut();
		break;
	}
}
