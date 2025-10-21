<?php
class Timbrado
{
    protected $urlPrueba		="http://pruebas.ecodex.com.mx:2044/ServicioTimbrado.svc?wsdl";
    protected $urlTimbrado		="https://pruebas.ecodex.com.mx:2045/ServicioTimbrado.svc?wsdl";//---> proxy SSL
    private static $timbrado;
    private static $timbre;    
    private static $codigo;
    private static $descripcion;
    private static $cancelacionResultado;
    private static $qr;

    function  __construct() 
	{
        //echo "Se creo instancia Timbrado";
    }

    public function getTimbrado()
    {
    	return self::$timbrado;
    }

    public function getCodigoEstatus()
    {
    	return self::$codigo;
    }

    public function getDescripcionEstatus()
    {
    	return self::$descripcion;
    }
	
    public function resultadoCancelar()
    {
        return self::$cancelacionResultado;
    }

	public function setTimbrado($ComprobanteXML,$rfc,$trsID,$token)
	{
		try
		{
			#$client=new SoapClient("http://pruebas.ecodex.com.mx:2044/ServicioTimbrado.svc?wsdl");
			#$client 	= new SoapClient("http://servicios.ecodex.com.mx:4040/ServicioTimbrado.svc?wsdl");
			$client 		= new SoapClient($this->urlPrueba);
			$parametros 	= array("ComprobanteXML" => array("DatosXML"=>$ComprobanteXML) ,"RFC" => $rfc,"Token" => $token,"TransaccionID"=>$trsID);
			$timbre 		= $client->TimbraXML($parametros);
			
			if (isset($timbre->ComprobanteXML->DatosXML))
			{
				self::$timbrado =$timbre->ComprobanteXML->DatosXML;
				return true;
			}
			else
			{
				self::$timbrado= "Error al procesar el CFDI ";
				return false;
			}
		}
		catch(Exception $ex)
		{
			self::$timbrado= "Error al procesar el CFDI el mensaje del servidor es el siguiente: ".$ex->getMessage();
			return false;
		}
	}
	
	public function setCancela($rfc,$token,$trsID,$UUID)
	{
		try
		{
			#$client = new nusoap_client(self::$url_wsTimbrado,self::$proxy_wsTimbrado);
			#$client = new SoapClient("http://pruebas.ecodex.com.mx:2044/ServicioTimbrado.svc?wsdl");
			$client = new SoapClient($this->urlPrueba);

			$parametros=array("RFC"=>$rfc,"Token"=>$token,"TransaccionID"=>$trsID,"UUID"=>$UUID);                
			
			#$aRespuesta = $client->call("CancelaTimbrado",$aParametros);
			$cancelar = $client->CancelaTimbrado($parametros);
			
			if(isset ($cancelar->Cancelada))
			{
				self::$cancelacionResultado=$cancelar->Cancelada;
				return 1;
			}
			
			return 1;
			
			/*if(isset ($aRespuesta["Cancelada"]))
			{
				self::$cancelaResult=$aRespuesta["Cancelada"];
			}*/
		}
		catch (Exception $ex)
		{
			self::$cancelacionResultado = "Error al cancelar el CFDI, el mensaje del servidor es el siguiente: ".$ex->getMessage();
			return 0;
		}
	}
	
    public function setStatusTimbre($rfc,$token,$trsIDN,$trsID,$UUID)
    {
        try
        {            
            $client = new nusoap_client(self::$url_wsTimbrado,self::$proxy_wsTimbrado);
            $err = $client->getError();
            if ($err)
            {                
                self::$descripcion = 'No se pudo acceder al WebService de Timbrado ' . $err;
            }
			else
			{
                $aParametros = array("RFC" =>$rfc,"Token"=>$token,"TransaccionID"=>$trsIDN,"TransaccionOriginal"=>$trsID,"UUID"=>$UUID);
                $aRespuesta = $client->call("EstatusTimbrado",$aParametros);

                if(isset($aRespuesta["Estatus"]["Codigo"])&&isset($aRespuesta["Estatus"]["Descripcion"]))
                {
                self::$codigo = $aRespuesta["Estatus"]["Codigo"];
                self::$descripcion = $aRespuesta["Estatus"]["Descripcion"];

                }elseif (isset ($aRespuesta["detail"]["ExceptionDetail"]["InnerException"]["Message"])) {
                    //Error SOAP InnerException Message
                    self::$descripcion = $aRespuesta["detail"]["ExceptionDetail"]["InnerException"]["Message"];
                }
                elseif(isset ($aRespuesta["faultstring"]))
                    {
                        // Error Generico
                        $arr = $aRespuesta["faultstring"];
                        $arr = array_values($arr);
                        self::$codigo=$arr[1];
                        self::$descripcion=$arr[1];
                    }
                else{
                    self::$descripcion = "Ocurrio un Error al Obtener el Estado del Timbrado";
                }
                /*LOG*/
                //echo '<h2>Request</h2><pre>' . htmlspecialchars($client->request, ENT_QUOTES) . '</pre>';
                //echo '<h2>Response</h2><pre>' . htmlspecialchars($client->response, ENT_QUOTES) . '</pre>';
            }
        }catch(Exception $ex){self::$descripcion = $ex->getMessage();}
    }
    
    

	public function getTimbre($rfc,$token,$trsIDN,$trsID,$UUID)
	{
		try
		{
			#$client = new nusoap_client(self::$url_wsTimbrado,self::$proxy_wsTimbrado);
			#$client=new SoapClient(self::$url_wsTimbrado,self::$proxy_wsTimbrado);
			$client=new SoapClient(self::$url_wsTimbrado,self::$proxy_wsTimbrado);
			
			/*$err = $client->getError();
			
			if ($err)
			{
				self::$timbre = 'No se pudo acceder al WebService de Timbrado ' . $err;
			}
			else
			{*/
				$aParametros = array("RFC" => $rfc,"Token" => $token,"TransaccionID"=>$trsIDN,"TransaccionOriginal"=>$trsID,"UUID"=>$UUID);
				
				#$aRespuesta = $client->call("ObtenerTimbrado", $aParametros);
				#$aRespuesta = $client->__call("ObtenerTimbrado", $aParametros);
				$aRespuesta = $client->ObtenerTimbrado($aParametros);
				
				if (isset($aRespuesta ["ComprobanteXML"]["DatosXML"]))
				{
					self::$timbre =$aRespuesta ["ComprobanteXML"]["DatosXML"];
				}
				elseif(isset ($aRespuesta["detail"]["FallaSesion"]["Descripcion"]))
				{
					// En caso de que falle la sesion Muestra el Error
					self::$timbre = $aRespuesta["detail"]["FallaSesion"]["Descripcion"];
				}
				elseif (isset ($aRespuesta["detail"]["ExceptionDetail"]["InnerException"]["Message"])) 
				{
					//Error SOAP InnerException
					self::$timbre = $aRespuesta["detail"]["ExceptionDetail"]["InnerException"]["Message"];
				}
				else
				{
					if(isset ($aRespuesta["faultstring"]))
					{
						// Error Generico
						$arr = $aRespuesta["faultstring"];
						$arr = array_values($arr);
						self::$timbre=$arr[1];
					}
					else
					{
						self::$timbre= "Ocurrio algun error...";
					}
				}
			#}
		}
		catch(Exception $ex)
		{
			self::$timbre = $ex->getMessage();
		}
		
		return self::$timbre;
	}

    public function getQR($rfc, $token, $trsID, $UUID)
    {
         try
            {
                //$ExceptionWs = new WebServiceException();
                $client = new nusoap_client(self::$url_wsTimbrado,self::$proxy_wsTimbrado);
                $err = $client->getError();
                if ($err)
                {
                   self::$qr = 'No se pudo acceder al WebService de Timbrado ' . $err;

                }else{
                        $aParametros = array("RFC" => $rfc,"Token" => $token,"TransaccionOriginal"=>$trsID,"UUID"=>$UUID);
                        $aRespuesta = $client->call("ObtenerQRTimbrado", $aParametros);
                        
                        if (isset($aRespuesta ["QR"]["Imagen"]))
                        {
                        self::$qr =$aRespuesta ["QR"]["Imagen"];
                        }
                        elseif(isset ($aRespuesta["detail"]["FallaSesion"]["Descripcion"]))
                        {
                            /*self::$err = true;
                            self::$err_Descripcion = $aRespuesta["detail"]["FallaSesion"]["Descripcion"];
                            return self::$err;*/
                            // En caso de que falle la sesion Muestra el Error
                            self::$qr = $aRespuesta["detail"]["FallaSesion"]["Descripcion"];
                        }
                        elseif (isset ($aRespuesta["detail"]["ExceptionDetail"]["InnerException"]["Message"])) {
                            //Error SOAP InnerException
                            self::$qr= $aRespuesta["detail"]["ExceptionDetail"]["InnerException"]["Message"];
                        }
                        else{
                            if(isset ($aRespuesta["faultstring"]))
                            {
                            // Error Generico
                            $arr = $aRespuesta["faultstring"];
                            $arr = array_values($arr);
                            self::$qr=$arr[1];
                            }else{
                            self::$qr= "Ocurrio algun error...";
                            }
                        }
                        /*LOG*/
                        //echo '<h2>Request</h2><pre>' . htmlspecialchars($client->request, ENT_QUOTES) . '</pre>';
                        //echo '<h2>Response</h2><pre>' . htmlspecialchars($client->response, ENT_QUOTES) . '</pre>';
                }
                }catch(Exception $ex)
                {
                    self::$qr = $ex->getMessage();
                }
                return self::$qr;
    }
}
?>
