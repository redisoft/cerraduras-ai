<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

function xmlPoliza($poliza,$conceptos,$transacciones,$cheques,$transferencias,$comprobantes,$metodos)
{
	$xml='<?xml version="1.0" encoding="UTF-8"?>';
	$xml.="\n".'<PLZ:Polizas xmlns:PLZ="www.sat.gob.mx/esquemas/ContabilidadE/1_1/PolizasPeriodo" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="www.sat.gob.mx/esquemas/ContabilidadE/1_1/PolizasPeriodo http://www.sat.gob.mx/esquemas/ContabilidadE/1_1/PolizasPeriodo/PolizasPeriodo_1_1.xsd" Version="'.$poliza->version.'" RFC="'.$poliza->rfc.'" Mes="'.substr($poliza->fecha,5,2).'" Anio="'.substr($poliza->fecha,0,4).'" TipoSolicitud="'.$poliza->tipoSolicitud.'"';
	
	if($poliza->tipoSolicitud=='AF' or $poliza->tipoSolicitud=='FC')
	{
		$xml.=' NumOrden="'.$poliza->numeroOrden.'"';
	}
	
	if($poliza->tipoSolicitud=='DE' or $poliza->tipoSolicitud=='CO')
	{
		$xml.=' NumTramite="'.$poliza->numeroTramite.'"';
	}
	
	$xml.='>';
	
	foreach($conceptos as $row)
	{
		#$xml.= "\n".'    <PLZ:Poliza Tipo="'.$row->tipo.'" Num="'.$row->numero.'" Fecha="'.$row->fecha.'" Concepto="'.$row->concepto.'"';
		$xml.= "\n".'    <PLZ:Poliza NumUnIdenPol="'.$row->numero.'" Fecha="'.$row->fecha.'" Concepto="'.$row->concepto.'"';
		
		$con=false;
		foreach($transacciones as $trans)
		{
			if($row->idConcepto==$trans->idConcepto)
			{
				$con=true;
			}
		}
		
		if(!$con)			
		{
			$xml.='/>';
		}
		else
		{
			$xml.='>';
		}
		
		foreach($transacciones as $trans)
		{
			if($row->idConcepto==$trans->idConcepto)
			{
				#$xml.= "\n".'        <PLZ:Transaccion NumCta="'.$trans->numeroCuenta.'" Concepto="'.$trans->concepto.'" Debe="'.$trans->debe.'" Haber="'.$trans->haber.'" Moneda="'.$trans->moneda.'"'.($trans->tipoCambio>0?' TipCamb="'.$trans->tipoCambio.'"':'').'';
				$xml.= "\n".'        <PLZ:Transaccion NumCta="'.$trans->numeroCuenta.'" DesCta="'.$trans->descripcionCuenta.'" Concepto="'.$trans->concepto.'" Debe="'.$trans->debe.'" Haber="'.$trans->moneda.'"';
				
				$c = false;
				foreach($cheques as $che)
				{
					if($che->idTransaccion==$trans->idTransaccion)
					{
						$c=true;
					}
				}
				foreach($transferencias as $tran)
				{
					if($tran->idTransaccion==$trans->idTransaccion)
					{
						$c=true;
					}
				}
				
				foreach($comprobantes as $com)
				{
					if($com->idTransaccion==$trans->idTransaccion)
					{
						$c=true;
					}
				}
				
				foreach($metodos as $met)
				{
					if($met->idTransaccion==$trans->idTransaccion)
					{
						$c=true;
					}
				}

				
				if(!$c)			
				{
					$xml.='/>';
				}
				else
				{
					$xml.='>';
				}
					
				
				foreach($cheques as $che)
				{
					if($che->idTransaccion==$trans->idTransaccion)
					{
						$xml.= "\n".'            <PLZ:Cheque Num="'.$che->numero.'" BanEmisNal="'.$che->banco.'" CtaOri="'.$che->cuentaOrigen.'" Fecha="'.$che->fecha.'" Benef="'.$che->beneficiario.'" RFC="'.$che->rfc.'" Monto="'.$che->monto.'"/>';
					}
				}
				
				foreach($transferencias as $tran)
				{
					if($tran->idTransaccion==$trans->idTransaccion)
					{
						$xml.= "\n".'            <PLZ:Transferencia CtaOri="'.$tran->cuentaOrigen.'" BancoOriNal="'.$tran->bancoOrigen.'" CtaDest="'.$tran->cuentaDestino.'" BancoDestNal="'.$tran->bancoDestino.'" Fecha="'.$tran->fecha.'" Benef="'.$tran->beneficiario.'" RFC="'.$tran->rfc.'" Monto="'.$tran->monto.'"/>';
					}
				}
				
				foreach($metodos as $metodo)
				{
					if($metodo->idTransaccion==$trans->idTransaccion)
					{
						$xml.= "\n".'            <PLZ:OtrMetodoPago MetPagoPol="'.$metodo->metodoPago.'" Fecha="'.$metodo->fecha.'" Benef="'.$metodo->beneficiario.'" RFC="'.$metodo->rfc.'" Monto="'.$metodo->monto.'"/>';
					}
				}
				
				foreach($comprobantes as $com)
				{
					if($com->idTransaccion==$trans->idTransaccion)
					{
						#$xml.= "\n".'            <PLZ:Comprobantes UUID_CFDI="'.$com->uuid.'" Monto="'.$com->monto.'" RFC="'.$com->rfc.'" />';
						$xml.= "\n".'            <PLZ:CompNal UUID_CFDI="'.$com->uuid.'" RFC="'.$com->rfc.'" MontoTotal="'.$com->monto.'"/>';
					}
				}
				
				if($c){$xml.="\n".'        </PLZ:Transaccion>';}
			}
		}
		
		if($con){$xml.="\n".'    </PLZ:Poliza>';}
	}
	
	
	
	$xml.="\n".'</PLZ:Polizas>';
	
	return $xml;
}
?>
