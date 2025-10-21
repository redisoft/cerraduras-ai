<?php
class Seguridad 
{    
 	protected $urlPrueba		= "http://pruebas.ecodex.com.mx:2044/ServicioSeguridad.svc?wsdl"; 		//LA URL DE PRUEBA
    protected $urlSeguridad 	= 'https://servicios.ecodex.com.mx:2045/ServicioSeguridad.svc?wsdl'; 	//LA URL DE SEGURIDAD
    private static $errores 		= false;
	private static $token;


    public function  __construct() 
	{
        //echo "Se creo instancia de Token...<br>";
    }

    public function getToken()
	{
        return self::$token;
    }

    public function getError()
	{
        return self::$errores;
    }

	public function setToken($rfc,$trsID,$integrador)
	{
		try
		{
			#$client=new SoapClient("http://pruebas.ecodex.com.mx:2044/ServicioSeguridad.svc?wsdl");
			#$client=new SoapClient("http://servicios.ecodex.com.mx:4040/ServicioSeguridad.svc?wsdl");
			$client	=new SoapClient($this->urlPrueba);
			
			$parametros = array("RFC" => $rfc,"TransaccionID"=>$trsID);
			
			$aRespuesta=$client->ObtenerToken($parametros);
			
			#var_dump($aRespuesta);
				
			if (isset($aRespuesta->Token))
			{
				$tohash 		= $integrador."|".$aRespuesta->Token;
				$tohash 		= utf8_encode($tohash);
				$toHash2 		= sha1($tohash);
				self::$token 	= $toHash2;
			}
		}
		catch(Exception $ex)
		{
			self::$token= "Error al obtener el token, el mensaje del servidor es el siguiente: ".$ex->getMessage();
			self::$errores = true;            
		}
	}
}
?>
