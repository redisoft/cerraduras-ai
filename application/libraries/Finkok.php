<?php
class Finkok
{
	protected $wsdl;
	protected $criterio;
	
    function  __construct() 
	{
		$this->wsdl	= 'wspac.wsdl';
    }

	public function obtenerTimbre($usuario='',$password='',$url='',$xml='')
	{
		try
		{
			#echo $xml;
			$registro	= array('xml'=>$xml,'username'=>$usuario,'password'=>$password);
			
			
			$soap 				= new SoapClient($url);
			$respuesta 			= $soap->Stamp($registro);
			$registroXml		= $respuesta->stampResult;

			$resultado			= objetoArreglo($registroXml);
			
			if(isset($resultado['Incidencias']['Incidencia']['CodigoError']))
			{
				return array('estatus'=>false,'codigoError'=>$resultado['Incidencias']['Incidencia']['CodigoError'],'mensaje'=>$resultado['Incidencias']['Incidencia']['MensajeIncidencia']);
			}
			else
			{
				$valores	= procesarXmlArreglo($registroXml->xml);

				return array('estatus'=>true,'mensaje'=>'El timbrado ha sido exitoso','xml'=>trim($registroXml->xml),'valores'=>$valores);
			}
		}
		catch(Exception $ex)
		{
			return array('estatus'=>false,'codigoError'=>'0','mensaje'=>'Error en el timbrado el mensaje es el siguiente: '.$ex->getMessage());
		}
	}
	
	
	public function cancelarCfdi($usuario,$password,$uuid,$certificado,$llave,$url,$rfc,$motivos,$uuidSustitucion='')
	{
		try
		{
			#$registro	= array('username'=>$usuario,'password'=>$password,'UUIDS'=>array('uuids'=>array($uuid)),'taxpayer_id'=>$rfc,'cer'=>$certificado,'key'=>$llave,'store_pending'=>true);
			$registro	= array('username'=>$usuario,'password'=>$password,'UUIDS'=>array('UUID'=>array('UUID'=>$uuid,'FolioSustitucion'=>$uuidSustitucion,'Motivo'=>$motivos)),'taxpayer_id'=>$rfc,'cer'=>$certificado,'key'=>$llave,'store_pending'=>true);

			$soap 				= new SoapClient($url);
			$respuesta 			= $soap->Cancel($registro);
			$registroXml		= $respuesta->cancelResult;
			$resultado			= objetoArreglo($registroXml);

			if(isset($resultado['Folios']['Folio']['EstatusUUID']))
			{
				if($resultado['Folios']['Folio']['EstatusUUID']=="201" or $resultado['Folios']['Folio']['EstatusUUID']=="202")
				{
					if($resultado['Folios']['Folio']['EstatusUUID']=="201")
					{
						return array('estatus'=>true,'codigoError'=>$resultado['Folios']['Folio']['EstatusUUID'],'mensaje'=>'La factura se ha cancelado correctamente','acuse'=>$resultado['Acuse']);
					}
					else
					{
						return array('estatus'=>true,'codigoError'=>$resultado['Folios']['Folio']['EstatusUUID'],'mensaje'=>'La factura se ha cancelado correctamente','acuse'=>'');
					}
				}
				else
				{
					return array('estatus'=>false,'codigoError'=>$resultado['Folios']['Folio']['EstatusUUID'],'mensaje'=>'Error al cancelar el CFDI, revise el código de error. '.$resultado['Folios']['Folio']['EstatusUUID'],'acuse'=>'');
				}
			}
			else
			{
				return array('estatus'=>false,'codigoError'=>'0','mensaje'=>'Error al cancelar el CFDI'.(isset($resultado['CodEstatus'])?': '.$resultado['CodEstatus']:''),'acuse'=>'');
			}
		}
		catch(Exception $ex)
		{
			return array('estatus'=>false,'codigoError'=>'','mensaje'=>'Error en la cancelación el mensaje es el siguiente: '.$ex->getMessage().'. Revise que el comprobante no se haya cancelado con anterioridad','acuse'=>'');
		}
	}
	
}
?>
