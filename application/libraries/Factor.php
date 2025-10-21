<?php
class Factor
{
    protected $urlPrueba		="http://comprobantes-fiscales.com/service/timbrarCFDI.php?wsdl";
    protected $urlTimbrado		="https://pruebas.ecodex.com.mx:2045/ServicioTimbrado.svc?wsdl";//---> proxy SSL

    function  __construct() 
	{
        //echo "Se creo instancia Timbrado";
    }

	public function obtenerTimbre($wsUser,$wsPassword,$documentoXML,$url='')
	{
		$data			= array();
		#$wsUser 		= 'VE611.test_ws';   //usuario de ws
		#$wsPassword 	= 'Test-WS-611628';   //password de ws
		
		try
		{
			$soap 			= new SoapClient($url);
			$sesion 		= $soap->openSession($wsUser,$wsPassword);

			$token 			= $sesion->token;
			$errorCode 		= $sesion->errorCode;
			$ok 			= $sesion->ok;
			
			if($ok)
			{
				$respuesta 		= $soap->createCFDI(array('token'=>$token,'xml'=>$documentoXML));

				$data['estatus']		= $respuesta->ok;
				$data['mensaje']		= strlen($respuesta->errorCode)>0?'Error en el timbrado, el mensaje del servidor es el siguiente: '.codigosFactor($respuesta->errorCode).'(Código: '.$respuesta->errorCode.')':'';
				$data['uuid']			= $respuesta->uuid;
				$data['xml']			= $respuesta->xml;
				$data['cadenaTimbre']	= $respuesta->string;
				$data['fechaTimbrado']	= $respuesta->date;
				$data['certificado']	= $respuesta->certNumber;
				$data['selloSat']		= $respuesta->satStamp;
				
				$data['codigoError']	= $respuesta->errorCode;
				$data['comentarios']	= codigosFactor($respuesta->errorCode);
				
			  	$cerrar 				= $soap->closeSession($token);
			}
			else
			{
				$data['estatus']		= false;
				$data['mensaje']		= 'Error al iniciar la sesión, el mensaje del servidor es el siguiente: '.codigosFactor($sesion->errorCode).'(Código: '.$sesion->errorCode.')';
				
				$data['codigoError']	= $sesion->errorCode;
				$data['comentarios']	= codigosFactor($sesion->errorCode);
			}
			
			return $data;
		}
		catch(Exception $ex)
		{
			$data['estatus']		= false;
			$data['mensaje']		= 'Error en el timbrado el mensaje es el siguiente: '.$ex->getMessage();
			$data['codigoError']	= '';
			$data['comentarios']	= '';
			
			return $data;
		}
	}
	
	public function cancelarCfdi($wsUser,$wsPassword,$uuid,$certificado,$llave,$passwordLlave,$url='')
	{
		try
		{
			$soap 			= new SoapClient($url);
			
			$certificadoHex	= leerArchivoHexadecimal($certificado);
			$llaveHex		= leerArchivoHexadecimal($llave);
			
			if($certificadoHex!="0" and $llaveHex!="0")
			{
				$respuesta 		= $soap->cancelCFDIckpRequest_NS(array('username'=>$wsUser,'password'=>$wsPassword,'uuid'=>$uuid,'cert_hex'=>$certificadoHex,'key_hex'=>$llaveHex,'key_password'=>$passwordLlave));
				$errorCode 		= $respuesta->errorCode;
				
				if($respuesta->errorCode==-1)
				{
					$errorCode	=explode(":",$respuesta->uuid);
					$errorCode	=$errorCode[1];
				}
				
				$data['estatus']		=$respuesta->ok;
				$data['mensaje']		=strlen($respuesta->errorCode)>0?'Error en la cancelación, el mensaje del servidor es el siguiente: '.codigosFactor($errorCode).'(Código: '.$errorCode.')':'';
			}
			else
			{
				$data['estatus']		=false;
				$data['mensaje']		='Error al obtener el certificado o la llave (Error de usuario)';
			}
			
			return $data;
		}
		catch(Exception $ex)
		{
			$data['estatus']		=false;
			$data['mensaje']		='Error en la cancelación el mensaje es el siguiente: '.$ex->getMessage();
			
			return $data;
		}
	}
	
	/*public function cancelarCfdi($wsUser,$wsPassword,$uuid)
	{
		try
		{
			#$wsUser 		= 'VE223.test_ws';   //usuario de ws
			#$wsPassword 	= 'Test-WS+092013';   //password de ws
			
			$soap 			= new SoapClient('wspac.wsdl');
			$sesion 		= $soap->openSession($wsUser,$wsPassword);
	
			$token 			= $sesion->token;
			$errorCode 		= $sesion->errorCode;
			$ok 			= $sesion->ok;
			
			if($ok)
			{
				$respuesta 		= $soap->cancelCFDIRequest(array('token'=>$token,'uuid'=>$uuid));
				$cerrar 		= $soap->closeSession($token);
				$errorCode 		= $respuesta->errorCode;
				
				if($respuesta->errorCode==-1)
				{
					$errorCode	=explode(":",$respuesta->uuid);
					$errorCode	=$errorCode[1];
				}
				
				$data['estatus']		=$respuesta->ok;
				$data['mensaje']		=strlen($respuesta->errorCode)>0?'Error en la cancelación, el mensaje del servidor es el siguiente: '.codigosFactor($errorCode).'(Código: '.$errorCode.')':'';
				
				#var_dump($data);
			}
			else
			{
				$data['estatus']		=false;
				$data['mensaje']		='Error al iniciar la sesión, el mensaje del servidor es el siguiente: '.codigosFactor($sesion->errorCode).'(Código: '.$sesion->errorCode.')';
			}
			
			return $data;
		}
		catch(Exception $ex)
		{
			$data['estatus']		=false;
			$data['mensaje']		='Error en la cancelación el mensaje es el siguiente: '.$ex->getMessage();
			
			return $data;
		}
	}*/

}
?>
