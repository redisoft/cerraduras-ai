<?php
if (!defined('BASEPATH')) exit('Sin permisos para acceder al script');
	
	function ReemplazarCadena($Cadena)
	{
		$Regresa	= str_replace("&","&amp;",$Cadena);
		$Regresa2	= str_replace('"',"&quot",$Cadena);
		$Regresa3	= str_replace("<","&lt;",$Regresa2);
		$Regresa4	= str_replace(">","&gt;",$Regresa3);
		$Regresa5	= str_replace("\"","&apos;",$Regresa4);
		
		return $Regresa5;
	}

	function validarPrecioProducto($precio,$producto)
	{
		$ban		= false;

		$precioA	= $producto->precioA;
		$precioB	= $producto->precioC;
		$precioC	= $producto->precioC;

		if($precio>$precioA)
		{
			if(($precio-$precioA)<0.5) $ban=true;
		}

		if($precio<$precioA)
		{
			if(($precioA-$precio)<0.5) $ban=true;
		}

		if($precio==$precioA)
		{
			$ban=true;
		}

		if(!$ban)
		{
			if($precio>$precioB)
			{
				if(($precio-$precioB)<0.5) $ban=true;
			}

			if($precio<$precioB)
			{
				if(($precioB-$precio)<0.5) $ban=true;
			}

			if($precio==$precioB)
			{
				$ban=true;
			}
		}

		if(!$ban)
		{
			if($precio>$precioC)
			{
				if(($precio-$precioC)<0.5) $ban=true;
			}

			if($precio<$precioC)
			{
				if(($precioC-$precio)<0.5) $ban=true;
			}

			if($precio==$precioC)
			{
				$ban=true;
			}
		}


		#return $ban;

		return true;

	}
	
	/*function sustituir($Cadena)
	{
		$Regresa	= str_replace("\"","&quot",$Cadena);
		$Regresa2	= str_replace("&","&amp;",$Regresa);
		$Regresa3	= str_replace("<","&lt;",$Regresa2);
		$Regresa4	= str_replace(">","&gt;",$Regresa3);
		$Regresa5	= str_replace("'","&apos;",$Regresa4);
		
		return sustituirSaltosFactura(trim($Regresa5));
	}*/

	function sustituir($Cadena)
	{
		$Regresa	= str_replace("&","&amp;",$Cadena);
		$Regresa2	= str_replace('"',"&quot;",$Regresa);
		$Regresa3	= str_replace("<","&lt;",$Regresa2);
		$Regresa4	= str_replace(">","&gt;",$Regresa3);
		$Regresa5	= str_replace("'","&apos;",$Regresa4);
		
		$Regresa5	= trim($Regresa5);
		
		return sustituirSaltosFactura($Regresa5);
	}

	function escaparCaracteres($cadena)
	{
		$regresa	= str_replace("<","\<",$cadena);
		$cadena2	= str_replace(">","\>;",$regresa);

		
		return sustituirSaltosFactura(trim($cadena2));
	}//

	function obtenerTipoDireccion($tipo)
	{
		switch($tipo)
		{
			case 0:$tipo="Envío";break;
			case 1:$tipo="Fiscal";break;
			case 2:$tipo="Envío y fiscal";break;
		}
		
		return $tipo;
	}

	function obtenerTipoRegistro($tipo)
	{
		switch($tipo)
		{
			case 1:$tipo="Retiro";break;
			case 2:$tipo="Vale";break;
		}
		
		return $tipo;
	}

	function obtenerFolioRegistro($tipo)
	{
		switch($tipo)
		{
			case 1:$tipo="R-";break;
			case 2:$tipo="V-";break;
		}
		
		return $tipo;
	}

	function configurarFolioTipo($folio)
	{
		switch(strlen($folio))
		{
			case 1:
				return '000'.$folio;
			break;
			
			case 2:
				return '00'.$folio;
			break;
				
			case 3:
				return '0'.$folio;
			break;
				
			default:
				return $folio;
				break;
			
		}
	}
	
	function procesarArreglo($data)
	{
		$array	=array_map('trim',$data);
		
		foreach ($array as $key => $value)
		{
		   $array[$key]  = str_replace("'", "", $value);
		}

		return $array;
	}
	
	function obtenerEscenarioIngreso($row)
	{
		$escenario='';
		
		switch($row->idEscenario)
		{
			case 0:$escenario="";break;
			case 1:$escenario='<br /><span style="color: green">'.$row->escenario.'</span>';break;
			case 2:$escenario='<br /><span style="color: #FF0">'.$row->escenario.'</span>';break;
			
			/*case 1:$escenario='<br /><span style="color: #000">'.$row->escenario.'</span>';break;
			case 2:$escenario='<br /><span style="color: #000">'.$row->escenario.'</span>';break;*/
		}
		
		return $escenario;
	}
	
	function obtenerTipoAlumnoIxe($tipo)
	{
		switch($tipo)
		{
			case 0:$tipo="Alumno";break;
			case 1:$tipo="Prospecto";break;
			case 2:$tipo="Cliente";break;
		}
		
		return $tipo;
	}

	function obtenerCondicionPago($tipo)
	{
		switch($tipo)
		{
			case 'Crédito':$tipo="CREDITO";break;
			case 'Credito':$tipo="CREDITO";break;
			default:$tipo="CONTADO";break;
		}
		
		return $tipo;
	}
	
	function obtenerContactado($registro)
	{
		switch($registro)
		{
			case 0:$registro="Sin respuesta";break;
			case 1:$registro="Contactado";break;
			default:$registro="";break;
		}
		
		return $registro;
	}
	
	function obtenerCualificado($registro)
	{
		switch($registro)
		{
			case 0:$registro="No cualificado";break;
			case 1:$registro="Cualificado";break;
			default:$registro="";break;
		}
		
		return $registro;
	}
	
	function obtenerInteresado($registro)
	{
		switch($registro)
		{
			case 0:$registro="No interesado";break;
			case 1:$registro="Interesado";break;
			default:$registro="";break;
		}
		
		return $registro;
	}

function obtenerTipoComprobante($registro)
	{
		switch($registro)
		{
			case 'ingreso':$registro="I - Ingreso";break;
			case 'egreso':$registro="E - Egreso";break;
			case 'pago':$registro="P - Pago";break;
		}
		
		return $registro;
	}
	
	function obtenerCausaRegistro($registro,$detalle)
	{
		if(strlen($registro)==0 and strlen($detalle)==0) return '';
		
		if(strlen($registro)>0 and strlen($detalle)==0)
		{
			$registro	= explode('|',$registro);
			
			if($registro[0]!=5) return $registro[1];
			
			if($registro[0]==5)
			{
				 return strlen($registro[2])>0?$registro[2]:$registro[1];
			}
		}
		
		if(strlen($registro)>0 and strlen($detalle)>0)
		{
			$registro	= explode('|',$registro);
			
			return $registro[1];
		}
	}
	
	function obtenerDetalleCausaRegistro($detalle)
	{
		if(strlen($detalle)==0) return '';
		
		$detalle	= explode('|',$detalle);
		
		if($detalle[0]!=6) return $detalle[1];
			
		if($detalle[0]==6)
		{
			 return strlen($detalle[2])>0?$detalle[2]:$detalle[1];
		}
	}
	
	
	function reemplazarApostrofe($cadena)
	{
		$cadena	= trim(str_replace("'","",$cadena));
		
		return $cadena;
	}
	
	function obtenerNumerosCadena($cadena)
	{
		return			 preg_replace('/[^0-9]+/', '', $cadena);
	}
	
	function obtenerImpuestoPinata($impuesto)
	{
		$impuesto	= explode(" ",$impuesto);
		$tipo		= $impuesto[0];
		
		switch($tipo)
		{
			case 'IEPS':
				return array('003','IEPS');
			break;
			
			case 'IVA':
				return array('002','IVA');
			break;
		}
	}
	
	function obtenerFolioSeguimiento($folio=0)
	{
		$longitud		= strlen($folio);
		$consecutivo	= '';
		
		switch($longitud)
		{
			case 0:$consecutivo='';break;
			
			case 1:$consecutivo='CRM-00000'.$folio;break;
			case 2:$consecutivo='CRM-0000'.$folio;break;
			case 3:$consecutivo='CRM-000'.$folio;break;
			case 4:$consecutivo='CRM-00'.$folio;break;
			case 5:$consecutivo='CRM-0'.$folio;break;
			default:$consecutivo='CRM-'.$folio;break;
		}
		
		return $consecutivo;
	}
	
	function obtenerFormato($formato)
	{
		#$color	= "Formato clientes.xls"; 
		
		switch($formato)
		{
			case 'clientes':$formato="Formato clientes";break; 
			case 'proveedores':$formato="Formato proveedores";break; 
			case 'materiales':$formato="Formato materia prima";break; 
			case 'prospectos':$formato="Formato prospectos";break; 
			
			case 'productos':
					$formato="Formato productos";
					
					if(sistemaActivo=='pinata')
					{
						$formato="Formato productospinata";
					}
					
				break; 
			
			
			case 'produccion':$formato="Formato produccion";break; 
		}
		
		return $formato;
	}
	
	function obtenerDiaActual($fecha)
	{
		$dia 	= date ( "l", strtotime ($fecha)); //Obtener el dia actual
		$dias	= substr($dia,0,3);
		
		switch($dias)
		{
			case 'Sun':$dia="Domingo";break;
			case 'Mon':$dia="Lunes";break;
			case 'Tue':$dia="Martes";break;
			case 'Wed':$dia="Miercoles";break;
			case 'Thu':$dia="Jueves";break;
			case 'Fri':$dia="Viernes";break;
			case 'Sat':$dia="Sabado";break;
		}
		
		return $dia;
	}
	
	function obtenerColorStatus($idStatus) //LOS COLORES SON DE GOOGLE
	{
		$color	= "1"; 
		
		switch($idStatus)
		{
			case '1':$color="2";break; //Verde
			case '2':$color="5";break; //Amarillo
			case '3':$color="6";break; //Naranja
			case '11':$color="3";break;//Morado 
			default:$color="1";break;
		}
		
		return $color;
	}
	
	function obtenerColorEmbudo($color=1) //LOS COLORES SON DE GOOGLE
	{
		switch($color)
		{
			case '1':$color="#FF6384";break; //Verde
			case '2':$color="#36A2EB";break; //Amarillo
			case '3':$color="#FFCE56";break; //Naranja
			case '4':$color="#008000";break; //Naranja
			case '5':$color="#4B0082";break; //Naranja
			case '6':$color="#FF6347";break; //Naranja
		}
		
		return $color;
	}
	
	function obtenerColorGraficaRgb() //LOS COLORES SON DE GOOGLE
	{
		return 'rgb('.rand(0,255).', '.rand(0,255).', '.rand(0,255).')';
	}
	
	function obtenerNombrePrecio($numero) //PRECIO DE BEEVERGREEN
	{
		$precio	= "Precio Sucursal"; 
		
		switch($numero)
		{
			/*case '1':$precio="Precio Sucursal";break; 
			case '2':$precio="Precio Foráneo";break;
			case '3':$precio="Precio Distribuidor Foráneo";break; 
			case '4':$precio="Precio Distribuidor Querétaro";break;
			case '5':$precio="Otro precio";break;
			default: $precio="Precio Sucursal";break;*/
			
			case '1':$precio="Precio Público";break; 
			case '2':$precio="Precio Mayoreo";break; 
			case '3':$precio="Precio 1";break; 
			case '4':$precio="Precio de venta D";break;
			case '5':$precio="Precio de venta E";break;
			default: $precio="Precio de venta A";break;
		}
		
		return $precio;
	}
	
	/*function obtenerNombrePrecio($numero) //PRECIOS NORMALES
	{
		$precio	= "Precio A"; 
		
		switch($numero)
		{
			case '1':$precio="Precio de venta A";break; //Verde
			case '2':$precio="Precio de venta B";break; //Amarillo
			case '3':$precio="Precio de venta C";break; //Naranja
			case '4':$precio="Precio de venta D";break;//Morado 
			case '5':$precio="Precio de venta E";break;//Morado 
		}
		
		return $precio;
	}*/
	
	function comprobarCorreoGmail($correo) //EL CORREO DEBE SER DE GMAIL
	{
		if(strlen($correo)<4) return false;
		
		$correo	= explode('@',$correo);
		
		if(isset($correo[1]))
		{
			if($correo[1]=='gmail.com')
			{ 
				return true;
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}
	
	function obtenerHora($fecha,$completa=1)
	{
		return $completa==1?substr($fecha,11,5):substr($fecha,0,5);
	}
	
	function obtenerHoraCompleta($fecha,$completa=1)
	{
		return $completa==1?substr($fecha,11,8):substr($fecha,0,8);
	}
	
	function generarCodigoBidimensional($factura,$configuracion)
	{
		$partes			= explode(".",$factura->total);
		$entero			= $partes[0];
		$decimal		= $partes[1];
		$valor			= strlen($entero);
		$ceros			= 10-$valor;
		$ceroEntero		= "";
		
		for($i=1;$i<=$ceros;$i++)
		{
			$ceroEntero.="0";
		}
		
		$ceroEntero.=$entero;
		
		$valor			=strlen($decimal);
		$ceros			=6-$valor;
		$ceroDecimal	="";
		
		for($i=1;$i<=$ceros;$i++)
		{
			$ceroDecimal.="0";
		}
		
		$ceroDecimal			= $decimal.$ceroDecimal;
		$sello					= substr($factura->selloDigital,(strlen($factura->selloDigital)-8),8);
		#$codigoBidimensional 	= "?re=".$configuracion->rfc."&rr=".$factura->rfc."&tt=".$ceroEntero.".".$ceroDecimal."&id=".$factura->UUID."";
		
		$codigoBidimensional	= "https://verificacfdi.facturaelectronica.sat.gob.mx/default.aspx?id=".$factura->UUID."&re=".$configuracion->rfc."&rr=".$factura->rfc."&tt=".round($factura->total,4)."&fe=".$sello;
		
		
		$carpeta				= carpetaCfdi.$configuracion->rfc.'/folio'.$factura->serie.$factura->folio.'/';
		$codigo					= $carpeta.'codigo'.$factura->folio.'.png';
		
		if(!file_exists($carpeta))
		{
			crearDirectorio($carpeta);
		}
		
		$xml					= $carpeta.$configuracion->rfc.'_'.$factura->serie.$factura->folio.'.xml';
		
		if(!file_exists($xml))
		{
			guardarArchivoXML($xml,$factura->xml);
		}
		
		#if(!file_exists($codigo))
		{
			QRcode::png($codigoBidimensional, $codigo, 'L', 3, 2);
		}
	}
	
	function obtenerHoraFormato($fecha)
	{
		if(strlen($fecha)==0) return '';
		
		$hora = substr($fecha,11,5);
		$Hora = explode(':',$hora);
		
		if(isset($Hora[1]))
		{
			return $Hora[0]>=12?' pm':' am';
		}
		else
		{
			return '';
		}
	}
	
	function obtenerFormatoHora($fecha)
	{
		if(strlen($fecha)==0) return '';
		
		$hora = substr($fecha,0,5);
		$Hora = explode(':',$hora);
		
		if(isset($Hora[0]))
		{
			return $Hora[0]>=12?$hora.'pm':$hora.'am';
		}
		else
		{
			return '';
		}
	}

	function obtenerFechaMesCortoHoraFormato($fecha)
	{
		$anio	=substr($fecha,0,4);
		$mes	=substr($fecha,5,2);
		$dia	=substr($fecha,8,2);
		
		return $dia.'-'.obtenerNombreMesCorto($mes).'-'.$anio.' '.substr($fecha,11,5).' '.obtenerHoraFormato($fecha);
	}
	
	function obtenerFechaMesLargo($fecha,$hora=1)
	{
		$anio	= substr($fecha,0,4);
		$mes	= substr($fecha,5,2);
		$dia	= substr($fecha,8,2);
		
		return obtenerDiaNombre(date ("l",strtotime($fecha))).' '. $dia.' de '.obtenerNombreMes($mes).' de '.$anio.($hora==1?' | '.date('h:i').obtenerHoraFormato($fecha):'');
	}
	
	function obtenerFechaMysql($fecha)
	{
		$dia	= substr($fecha,0,2);
		$mes	= substr($fecha,3,2);
		$anio	= substr($fecha,6,4);

		$hora = substr($fecha,11,5);
		
		return array($anio.'-'.$mes.'-'.$dia,$hora);
	}

	function obtenerFechaFormato($fecha)
	{
		$anio	= substr($fecha,0,4);
		$mes	= substr($fecha,5,2);
		$dia	= substr($fecha,8,2);
		
		return $dia.'/'.obtenerNombreMesCorto($mes).'/'.$anio;
	}
	
	function obtenerFechaMysqlProspectos($fecha)
	{
		$mes	= substr($fecha,0,2);
		$dia	= substr($fecha,3,2);
		$anio	= substr($fecha,6,4);

		$hora = substr($fecha,11,5);
		
		return array($anio.'-'.$mes.'-'.$dia,$hora);
	}
		
	
	function obtenerDiaNombre($dia)
	{
		$dias	= substr($dia,0,3);
		
		switch($dias)
		{
			case 'Sun':$dia="Domingo";break;
			case 'Mon':$dia="Lunes";break;
			case 'Tue':$dia="Martes";break;
			case 'Wed':$dia="Miércoles";break;
			case 'Thu':$dia="Jueves";break;
			case 'Fri':$dia="Viernes";break;
			case 'Sat':$dia="Sábado";break;
		}
		
		return $dia;
	}
	
	function obtenerNombreDia($dia) #Dias habiles en CIEUD
	{
		switch($dia)
		{
			case 1:$dia="Lunes";break;
			case 2:$dia="Martes";break;
			case 3:$dia="Miércoles";break;
			case 4:$dia="Jueves";break;
			case 5:$dia="Viernes";break;
		}
		
		return $dia;
	}
	
	function obtenerNombreDiaCorto($dia)
	{
		switch($dia)
		{
			case 1:$dia="Lun";break;
			case 2:$dia="Mar";break;
			case 3:$dia="Mié";break;
			case 4:$dia="Jue";break;
			case 5:$dia="Vie";break;
		}
		
		return $dia;
	}
	
	function obtenerNombreMes($mes)
	{
		switch($mes)
		{
			case '01':$mes="Enero";break;
			case '02':$mes="Febrero";break;
			case '03':$mes="Marzo";break;
			case '04':$mes="Abril";break;
			case '05':$mes="Mayo";break;
			case '06':$mes="Junio";break;
			case '07':$mes="Julio";break;
			case '08':$mes="Agosto";break;
			case '09':$mes="Septiembre";break;
			case '10':$mes="Octubre";break;
			case '11':$mes="Noviembre";break;
			case '12':$mes="Diciembre";break;
		}
		
		return $mes;
	}
	
	function obtenerNombreMesCorto($mes)
	{
		switch($mes)
		{
			case '01':$mes="ENE";break;
			case '02':$mes="FEB";break;
			case '03':$mes="MAR";break;
			case '04':$mes="ABR";break;
			case '05':$mes="MAY";break;
			case '06':$mes="JUN";break;
			case '07':$mes="JUL";break;
			case '08':$mes="AGO";break;
			case '09':$mes="SEP";break;
			case '10':$mes="OCT";break;
			case '11':$mes="NOV";break;
			case '12':$mes="DIC";break;
		}
		
		return $mes;
	}
	
	function obtenerNumeroMes($mes)
	{
		$mes	=strtolower($mes);

		switch ($mes)
		{
			case "ene": $mes="01";break;
			case "jan": $mes = "01";break;
			case "feb": $mes = "02"; break;
			case "mar": $mes = "03"; break;
			case "abr": $mes = "04"; break;
			case "apr": $mes = "04"; break;
			case "may": $mes = "05"; break;
			case "jun": $mes = "06"; break;
			case "jul": $mes = "07"; break;
			case "ago": $mes = "08"; break;
			case "aug": $mes = "08"; break;
			case "sep": $mes = "09"; break;
			case "oct": $mes = "10"; break;
			case "nov": $mes = "11"; break;
			case "dec": $mes = "12"; break;
		}
		
		return $mes;
	}
	
	function obtenerNombreFecha($fecha)
	{
		$anio	=substr($fecha,0,4);
		$mes	=substr($fecha,5,2);
		$dia	=substr($fecha,8,2);
		
		return $dia.' DE '.obtenerNombreMes($mes).' DEL '.$anio;
	}
	
	function obtenerFechaAnio($mes,$anio)
	{
		return obtenerNombreMes($mes).' DEL '.$anio;
	}
	
	function obtenerFechaMesAnio($fecha)
	{
		return obtenerNombreMes(substr($fecha,5,2)).' DEL '.substr($fecha,0,4);
	}
	
	function obtenerMesAnio($fecha)
	{
		return obtenerNombreMes(substr($fecha,5,2)).' '.substr($fecha,0,4);
	}
	
	function obtenerFechaMesCorto($fecha)
	{
		$anio	=substr($fecha,0,4);
		$mes	=substr($fecha,5,2);
		$dia	=substr($fecha,8,2);
		
		return $dia.'-'.obtenerNombreMesCorto($mes).'-'.$anio;
	}

	function obtenerFechaMesNumero($fecha)
	{
		$anio	=substr($fecha,0,4);
		$mes	=substr($fecha,5,2);
		$dia	=substr($fecha,8,2);
		
		return $dia.'-'.$mes.'-'.$anio;
	}
	
	function obtenerFechaSeparada($fecha,$criterio='')
	{
		$anio	=substr($fecha,0,4);
		$mes	=substr($fecha,5,2);
		$dia	=substr($fecha,8,2);
		
		switch($criterio)
		{
			
			case 'dia':
				return $dia;
			break;
			
			case 'mes':
				return $mes;
			break;
			
			case 'anio':
				return $anio;
			break;
			
			default:
				return array($dia,$mes,$anio);
			break;
		}
	}
	
	function obtenerFechaMesCortoHora($fecha)
	{
		$anio	=substr($fecha,0,4);
		$mes	=substr($fecha,5,2);
		$dia	=substr($fecha,8,2);
		
		return $dia.'-'.obtenerNombreMesCorto($mes).'-'.$anio.' '.substr($fecha,11,5);
	}
	
	function obtenerMayusculas($cadena)
	{
		return mb_strtoupper($cadena,'utf-8');
	}
	
	function obtenerMinusculas($cadena)
	{
		return mb_strtolower($cadena,'utf-8');
	}
	
	function espaciosFactura($cadena)
	{
		$cadena	=str_replace("-","",$cadena);
		$cadena	=str_replace(" ","",$cadena);
		
		return $cadena;
	}
	
	function sustituirSaltosFactura($cadena)
	{
		$sustituye 	= array("(\r\n)", "(\n\r)", "(\n)", "(\r)");
		$valor		=preg_replace($sustituye," ",$cadena);
		
		return $valor;
	}
	
	function sustituirSaltos($cadena)
	{
		$sustituye 	= array("(\r\n)", "(\n\r)", "(\n)", "(\r)");
		$valor		=preg_replace($sustituye,"<br />",$cadena);
		
		return $valor;
	}
	
	function codigosFactor($codigo)
	{
		switch($codigo)
		{
			case 5100; $codigo="Caracteres inválidos en campo username (fuera del rango UTF8)";break;
			case 5101; $codigo="Caracteres inválidos en campo password (fuera del rango UTF8)";break;
			case 5102; $codigo="Caracteres inválidos en campo token (fuera del rango UTF8)";break;
			case 5103; $codigo="Caracteres inválidos en campo xml (fuera del rango UTF8)";break;
			case 5104; $codigo="Caracteres inválidos en campo uuid (fuera del rango UTF8)";break;
			case 5105; $codigo="ID de paquete de timbres inválido";break;
			case 5200; $codigo="Error de autenticación, la combinación username y password son inválidas";break;
			case 5201; $codigo="Token de sesión inválido";break;
			case 5202; $codigo="Sesión previamente cerrada";break;
			case 5203; $codigo="Sesión expirada";break;
			case 5300; $codigo="No más timbres disponibles";break;
			case 5400; $codigo="RFC en CFD no autorizado para timbrar";break;
			case 200; $codigo="UUID en proceso de cancelación";break;
			case 202; $codigo="UUID previamente cancelado";break;
			case 203; $codigo="UUID consultado no pertenece a contribuyente";break;
			case 205; $codigo="UUID consultado desconocido";break;
			case 206; $codigo="UUID no solicitado para cancelación";break;
			case 207; $codigo="UUID en fecha inválida para cancelación (debe ser cancelado al menos 48 horas depués de su emisión)";break;
			case 208; $codigo="UUID inválido";break;
			case 301; $codigo="Error en la estructura del XML con respecto al ANEXO 20 de la Resolución Miscelánea Fiscal 2010";break;
			case 302; $codigo="Sello mal formado o inválido";break;
			case 303; $codigo="Sello de firma no corresponde a CSD del emisor";break;
			case 304; $codigo="CSD del contribuyente vencido o inválido";break;
			case 305; $codigo="La fecha de emisión no esta dentro de la vigencia del CSD del Emisor";break;
			case 306; $codigo="El certificado no es de tipo CSD";break;
			case 307; $codigo="El CFDI contiene un timbre previo";break;
			case 308; $codigo="Certificado no expedido por el SAT";break;
			case 309; $codigo="CFD duplicado";break;
			case 401; $codigo="CFD fuera de fecha (emitido hace más de 72 horas)";break;
			case 402; $codigo="RFC del emisor no se encuentra en el régimen de contribuyentes";break;
			case 403; $codigo="La fecha de emisión no es posterior al 01 de enero 2012";break;
			case 404; $codigo="La fecha de emisión está en el futuro";break;
			case 3001; $codigo="Falta estado y municipio en la dirección donde se emite el comprobante";break;
			case 3002; $codigo="Falta forma de pago";break;
			case 3003; $codigo="Falta método de pago";break;
			case 3004; $codigo="Falta subtotal del comprobante";break;
			case 3005; $codigo="Falta total del comprobante";break;
			case 3006; $codigo="Falta tipo de comprobante";break;
			case 3007; $codigo="Falta nombre / RFC del emisor";break;
			case 3008; $codigo="Falta calle en domicilio fiscal";break;
			case 3009; $codigo="Falta código postal en domicilio fiscal";break;
			case 3010; $codigo="Falta estado en domicilio fiscal";break;
			case 3011; $codigo="Falta municipio en domicilio fiscal";break;
			case 3012; $codigo="Falta país en domicilio fiscal";break;
			case 3013; $codigo="Falta calle en dirección de expedición del comprobante";break;
			case 3014; $codigo="Falta código postal en dirección de expedición del comprobante";break;
			case 3015; $codigo="Falta estado en dirección de expedición del comprobante";break;
			case 3016; $codigo="Falta municipio en dirección de expedición del comprobante";break;
			case 3017; $codigo="Falta país en dirección de expedición del comprobante";break;
			case 3018; $codigo="Falta régimen del emisor";break;
			case 3019; $codigo="Falta nombre / RFC del receptor";break;
			case 3020; $codigo="Falta calle en dirección del receptor";break;
			case 3021; $codigo="Falta código postal en dirección del receptor";break;
			case 3022; $codigo="Falta estado en dirección del receptor";break;
			case 3023; $codigo="Falta municipio en dirección del receptor";break;
			case 3024; $codigo="Falta país en dirección del receptor";break;
			case 3025; $codigo="Falta descripción en un concepto";break;
			case 3026; $codigo="Falta cantidad en un concepto";break;
			case 3027; $codigo="Faltan unidades en un concepto";break;
			case 3028; $codigo="Falta valor unitario en un concepto";break;
			case 3029; $codigo="Falta importe en un concepto";break;
			case 3030; $codigo="Falta nombre de retención / importe en una retención";break;
			case 3031; $codigo="Falta nombre de impuesto / importe / tasa en un impuesto";break;
			case 3032; $codigo="No hay certificado disponible para sellar el CFD";break;
			case 3033; $codigo="No hay número de certificado disponible";break;
			case 3034; $codigo="No hay llave disponible para sellar el CFD";break;
			case 3035; $codigo="Error al sellar el CFD";break;
			
			case 3036; $codigo="Falta Lugar de expedicion";break;
			case 3037; $codigo="Falta Uso del CFDI";break;
			case 3038; $codigo="Falta Clave de Producto o Servicio";break;
			case 3039; $codigo="Falta Clave unidad";break;
			case 3040; $codigo="Falta nombre de factor / impuesto / importe / tasa o cuota en un impuesto";break;
			case 3041; $codigo="Falta nombre de factor / impuesto / importe / tasa o cuota / base en un impuesto retenido del concepto";break;
			case 3042; $codigo="Falta nombre de factor / impuesto / base en un impuesto trasladado del concepto";break;
			case 3043; $codigo="Falta Moneda";break;
			
			case 6000; $codigo="Error en la sesion";break;
			
			case 5501; $codigo="Certificado inválido";break;
			case 5502; $codigo="Llave inválida";break;
			case 5503; $codigo="El UUID está fuera del tiempo permitido para solicitar su cancelación";break;
			case 5504; $codigo="La llave no corresponde al certificado";break;
			case 5505; $codigo="El RFC del emisor del UUID no corresponde al RFC del certificado";break;
			
			case "8100": $codigo = "El CFDI contiene mas de un Error"; break;

			case "8101": $codigo = "El atributo fecha no cumple con el patrón requerido."; break;
			case "8102": $codigo = "El atributo metodoDePagodebetenerel valor NA"; break;
			case "8103": $codigo = "El atributo noCertificado no cumple con el patrón requerido."; break;
			case "8104": $codigo = "El atributo Moneda debe tener el valor MXN."; break;
			case "8105": $codigo = "El atributo TipoCambio no tiene el valor = 1"; break;
			case "8106": $codigo = "El valor del atributo subTotal no coincide con la suma de Nomina12:TotalPercepciones más Nomina12:TotalOtrosPagos."; break;
			case "8107": $codigo = "El valor de descuento no es igual a Nomina12:TotalDeducciones."; break;
			case "8108": $codigo = "El atributo total no cumple con el patrón requerido."; break;
			case "8109": $codigo = "El valor del atributo total no coincide con la suma Nomina12:TotalPercepciones más Nomina12:TotalOtrosPagos menos Nomina12:TotalDeducciones."; break;
			case "8110": $codigo = "El atributo tipoDeComprobante no tiene el valor = egreso."; break;
			case "8111": $codigo = "El valor del atributo LugarExpedicion no cumple con un valordel catálogo c_CodigoPostal."; break;
			case "8112": $codigo = "Los atributos motivoDescuento, NumCtaPago, condicionesDePago, SerieFolioFiscalOrig, FechaFolioFiscalOrig, MontoFolioFiscalOrig  no deben existir."; break;
			case "8113": $codigo = "El atributo Nomina12:Emisor:Curp.noaplica para persona moral."; break;
			case "8114": $codigo = "El atributo Nomina12:Emisor:Curp. Debe aplicar para persona física."; break;
			case "8115": $codigo = "El nodo Subcontratacion se debe registrar."; break;
			case "8116": $codigo = "Los elementos cfdi:Comprobante.Emisor.DomicilioFiscal y ExpedidoEn No deben existir."; break;
			case "8117": $codigo = "Solo debe existir un solo nodo RegimenFiscal."; break;
			case "8118": $codigo = "El valor del atributo Regimen no cumple con un valor del catálogo c_RegimenFiscal."; break;
			case "8119": $codigo = "El atributo Regimen no cumple con un valor de acuerdo al tipo de persona moral."; break;
			case "8120": $codigo = "El atributo Regimen no cumple con un valor de acuerdo al tipo de persona física."; break;
			case "8121": $codigo = "El atributo cfdi:Comprobante.Receptor.rfc debe ser persona física (13 caracteres)."; break;
			case "8122": $codigo = "El atributo cfdi:Comprobante.Receptor.rfc no es válido según la lista de RFC inscritos no cancelados en el SAT (l_RFC)."; break;
			case "8123": $codigo = "El nodo cfdi:Comprobante.Receptor.Domicilio, No debe existir."; break;
			case "8124": $codigo = "El nodo concepto solo debe existir uno, sin elementos hijo."; break;
			case "8125": $codigo = "Si versión del CFDI = 3.2 entonces en el atributo cfdi:Comprobante.Conceptos.Concepto.noIdentificacion, No debe registrarse."; break;
			case "8126": $codigo = "El atributo cfdi:Comprobante.Conceptos.Concepto.cantidad no tiene el valor = 1."; break;
			case "8127": $codigo = "El atributo cfdi:Comprobante.Conceptos.Concepto.unidad no tiene el valor =  ACT"; break;
			case "8128": $codigo = "El atributo cfdi:Comprobante.Conceptos.Concepto.descripcion,  no tiene el valor Pago de nómina "; break;
			case "8129": $codigo = "El valor del atributo.cfdi:Comprobante.Conceptos.Concepto.valorUnitario no coincide con la suma TotalPercepciones más TotalOtrosPagos."; break;
			case "8130": $codigo = "El valor del atributo.cfdi:Comprobante.Conceptos.Concepto.Importe no coincide con la suma TotalPercepciones más TotalOtrosPagos."; break;
			case "8131": $codigo = "El nodo cfdi:Comprobante.Impuestos no cumple la estructura."; break;
			case "8150": $codigo = "El nodo Nomina no se puede utilizar dentro del elemento ComplementoConcepto."; break;
			case "8151": $codigo = "El nodo Nomina no tiene TotalPercepciones y/o TotalOtrosPagos."; break;
			case "8152": $codigo = "El valor del atributo Nomina.TipoNomina no cumple con un valor del catálogo c_TipoNomina."; break;
			case "8153": $codigo = "El valor del atributo tipo de periodicidad no se encuentra entre 01 al 09."; break;
			case "8154": $codigo = "El valor del atributo tipo de periodicidad no es 99. Si el atributo Nomina.TipoNomina es extraordinaria el tipo de periodicidad de pago debe ser 99. "; break;
			case "8155": $codigo = "El valor del atributo FechaInicialPago no es menor o igual al valor del atributo FechaFinalPago."; break;
			case "8156": $codigo = "El atributo Nomina.TotalPercepciones, no debe existir."; break;
			case "8157": $codigo = "El valor del atributo Nomina.TotalPercepciones no coincide con la suma TotalSueldos más TotalSeparacionIndemnizacion más TotalJubilacionPensionRetiro del  nodo Percepciones."; break;
			case "8158": $codigo = "El atributo Nomina.TotalDeducciones, no debe existir."; break;
			case "8159": $codigo = "El valor del atributo Nomina.TotalDeducciones no coincide con la suma de los atributos TotalOtrasDeducciones más TotalImpuestosRetenidos del elemento Deducciones."; break;
			case "8160": $codigo = "El valor del atributo Nomina.TotalOtrosPagos no está registrado o  no coincide con la suma de los atributos Importe de los nodos nomina12:OtrosPagos:OtroPago."; break;
			case "8161": $codigo = "El atributo Nomina.Emisor.RfcPatronOrigen no está inscrito en el SAT (l_RFC)."; break;
			case "8162": $codigo = "El atributo Nomina.Emisor.RegistroPatronalse debe registrar. Sielatributo TipoContrato está entre  01 al 08, el atributo Nomina.Emisor.RegistroPatronal debe existir."; break;
			case "8163": $codigo = "El atributo Nomina.Emisor.RegistroPatronal  no se debe registrar. Si el atributo TipoContrato tiene el valor 09, 10 ó 99, el atributo Nomina.Emisor.RegistroPatronal no debe existir."; break;
			case "8164": $codigo = "Si atributo Nomina.Emisor.RegistroPatronal existe,entoncesdeben existir los atributos nomina12:Receptor: NumSeguridadSocial,  nomina12:Receptor:FechaInicioRelLaboral, nomina12:Receptor:Antigüedad,  nomina12:Receptor:RiesgoPuesto y nomina12:Receptor:SalarioDiarioIntegrado."; break;
			case "8165": $codigo = "El nodo Nomina.Emisor.EntidadSNCF debe existir."; break;
			case "8166": $codigo = "El nodo Nomina.Emisor.EntidadSNCF no debe existir."; break;
			case "8167": $codigo = "El valor del atributo Nomina.Emisor.EntidadSNCF.OrigenRecurso no cumple con un valor del catálogo c_OrigenRecurso."; break;
			case "8168": $codigo = "El atributo Nomina.Emisor.EntidadSNCF.MontoRecursoPropio debe existir."; break;
			case "8169": $codigo = "El atributo Nomina.Emisor.EntidadSNCF.MontoRecursoPropio no debe existir."; break;
			case "8170": $codigo = "El valor del atributo Nomina.Emisor.EntidadSNCF.MontoRecursoPropio no es menor a la suma de los valores de los atributos TotalPercepciones y TotalOtrosPagos."; break;
			case "8171": $codigo = "El valor del atributo Nomina.Receptor.TipoContrato no cumple con un valor del catálogo c_TipoContrato."; break;
			case "8172": $codigo = "El valor del atributo Nomina.Receptor.TipoJornada no cumple con un valor del catálogo c_TipoJornada."; break;
			case "8173": $codigo = "El valor del atributo Nomina.Receptor.FechaInicioRelLaboral no es menor o igual al atributo a FechaFinalPago."; break;
			case "8174": $codigo = "El valor numérico del atributo Nomina.Receptor.Antigüedad no es menor o igual al cociente de (la suma del número de días transcurridos entre la FechaInicioRelLaboral y la FechaFinalPago más uno) dividido entre siete."; break;
			case "8175": $codigo = "El valor del atributo Nomina.Receptor.Antigüedad. no cumple con el número de años, meses y días transcurridos entre la FechaInicioRelLaboral y la FechaFinalPago."; break;
			case "8176": $codigo = "El valor del atributo Nomina.Receptor.TipoRegimen no cumple con un valor del catálogo c_TipoRegimen."; break;
			case "8177": $codigo = "El atributo Nomina.Receptor.TipoRegimen no es 02, 03 ó 04."; break;
			case "8178": $codigo = "El atributo Nomina.Receptor.TipoRegimen no está entre 05 a 99."; break;
			case "8179": $codigo = "El valor del atributo Nomina.Receptor.RiesgoPuesto no cumple con un valor del catálogo c_RiesgoPuesto."; break;
			case "8180": $codigo = "El valor del atributo Nomina.Receptor.PeriodicidadPago no cumple con un valor del catálogo c_PeriodicidadPago."; break;
			case "8181": $codigo = "El valor del atributo Nomina.Receptor.Banco no cumple con un valor del catálogo c_Banco."; break;
			case "8182": $codigo = "El atributo CuentaBancaria no cumple con la longitud de 10, 11, 16 ó 18 posiciones."; break;
			case "8183": $codigo = "ElatributoBanco no debe existir. Si se registra una cuenta CLABE (número con 18 posiciones), el atributo Banco no debe existir."; break;
			case "8184": $codigo = "El dígito de control del atributo CLABE no es correcto."; break;
			case "8185": $codigo = "El atributo Banco debe existir. Si se registra una cuenta de tarjeta de débito a 16 posiciones o una cuenta bancaria a 11 posiciones o un número de teléfono celular a 10 posiciones, debe existir el banco."; break;
			case "8186": $codigo = "El valor del atributo ClaveEntFed no cumple con un valor del catálogo c_Estado."; break;
			case "8187": $codigo = "El valor del atributo Nomina.Receptor.SubContratacion.RfcLabora no está en la lista de RFC (l_RFC)."; break;
			case "8188": $codigo = "La suma de los valores registrados en el atributo Nomina.Receptor.SubContratacion.PorcentajeTiempo no es igual a 100."; break;
			case "8189": $codigo = "La suma de los valores de los atributos TotalSueldos más TotalSeparacionIndemnizacion más TotalJubilacionPensionRetirono esigual a la suma de los valores de los atributos TotalGravado más TotalExento."; break;
			case "8190": $codigo = "El valor del atributo Nomina.Percepciones.TotalSueldos , no es igual a la suma de los atributos ImporteGravado e ImporteExento donde la clave expresada en el atributo TipoPercepcion es distinta de 022 Prima por Antigüedad, 023 Pagos por separación, 025 Indemnizaciones, 039 Jubilaciones, pensiones o haberes de retiro en una exhibición y 044 Jubilaciones, pensiones o haberes de retiro en parcialidades."; break;
			case "8191": $codigo = "El valor del atributo Nomina.Percepciones.TotalSeparacionIndemnizacion, no es igual a la suma de los atributos ImporteGravado e ImporteExento donde la clave en el atributo TipoPercepcion es igual a 022 Prima por Antigüedad, 023 Pagos por separación ó 025 Indemnizaciones."; break;
			case "8192": $codigo = "El valor del atributo Nomina.Percepciones.TotalJubilacionPensionRetiro, no es igual a la suma de los atributos ImporteGravado e importeExento donde la clave expresada en el atributo TipoPercepcion es igual a 039(Jubilaciones, pensiones o haberes de retiro en una exhibición)  ó 044 (Jubilaciones, pensiones o haberes de retiro en parcialidades)."; break;
			case "8193": $codigo = "El valor del atributo Nomina.Percepciones.TotalGravado, no es igual a la suma de los atributos ImporteGravado de los nodos Percepcion."; break;
			case "8194": $codigo = "El valor del atributo Nomina.Percepciones.TotalExento, no es igual a la suma de los atributos ImporteExento de los nodos Percepcion."; break;
			case "8195": $codigo = "La suma de los importes de los atributos ImporteGravado e ImporteExento no es mayor que cero."; break;
			case "8196": $codigo = "El valor del atributo Nomina.Percepciones.Percepcion.TipoPercepcion no cumple con un valor del catálogo c_TipoPercepcion."; break;
			case "8197": $codigo = "TotalSueldos, debe existir. Ya que la clave expresada en TipoPercepcion es distinta de 022, 023, 025, 039 y 044."; break;
			case "8198": $codigo = "TotalSeparacionIndemnizacion y el elemento SeparacionIndemnizacion, debe existir. Ya que la clave expresada en TipoPercepcion es 022 ó 023 ó 025."; break;
			case "8199": $codigo = "TotalJubilacionPensionRetiro y el elemento JubilacionPensionRetiro debe existir,  ya que la clave expresada en el atributo TipoPercepcion es 039 ó 044,"; break;
			case "8200": $codigo = "TotalUnaExhibicion debe existir y no deben existir TotalParcialidad, MontoDiario. Ya que la clave expresada en el atributo TipoPercepcion es 039."; break;
			case "8201": $codigo = "TotalUnaExhibicion no debe existir y deben existir TotalParcialidad, MontoDiario. Ya que la clave expresada en el atributo TipoPercepcion es 044."; break;
			case "8202": $codigo = "El elemento AccionesOTitulos debe existir. Ya que la clave expresada en el atributo TipoPercepcion es 045."; break;
			case "8203": $codigo = "El elemento AccionesOTitulos no debe existir. Ya que la clave expresada en el atributo TipoPercepcion no es 045."; break;
			case "8204": $codigo = "El elemento HorasExtra, debe existir. Ya que la clave expresada en el atributo TipoPercepcion es 019."; break;
			case "8205": $codigo = "El elemento HorasExtra, no debe existir. Ya que la clave expresada en el atributo TipoPercepcion no es 019."; break;
			case "8206": $codigo = "El nodo Incapacidades debe existir, Ya que la clave expresada en el atributo TipoPercepcion es 014."; break;
			case "8207": $codigo = "La suma de los campos ImporteMonetario no es igual a la suma de los valores ImporteGravado e ImporteExento de la percepción, Ya que la clave expresada en el atributo TipoPercepcion es 014."; break;
			case "8208": $codigo = "El valor del atributo Nomina.Percepciones.Percepcon.HorasExtra.TipoHoras no cumple con un valor del catálogo c_TipoHoras."; break;
			case "8209": $codigo = "Los atributos MontoDiario y TotalParcialidad no deben existir, ya que existe valor en TotalUnaExhibicion."; break;
			case "8210": $codigo = "El atributo MontoDiario debe existir y el atributo TotalUnaExhibicion no debe existir, ya que Nomina.Percepciones.JubilacionPensionRetiro.TotalParcialidad tiene valor."; break;
			case "8211": $codigo = "El valor en el atributo Nomina.Deducciones.TotalImpuestosRetenidos no es igual a la suma de los atributos Importe de las deducciones que tienen expresada la clave 002 en el atributo TipoDeduccion."; break;
			case "8212": $codigo = "Nomina.Deducciones.TotalImpuestosRetenidos no debe existir, ya que no existen deducciones con clave 002 en el atributo TipoDeduccion."; break;
			case "8213": $codigo = "El valor del atributo Nomina.Deducciones.Deduccion.TipoDeduccion no cumple con un valor del catálogo c_TipoDeduccion."; break;
			case "8214": $codigo = "Debe existir el elemento Incapacidades, ya que la clave expresada en Nomina.Deducciones.Deduccion.TipoDeduccion es 006."; break;
			case "8215": $codigo = "El atributo Deduccion: Importe no es igual a la suma de los nodos Incapacidad: ImporteMonetario. Ya que la clave expresada en Nomina.Deducciones.Deduccion.TipoDeduccion es 006"; break;
			case "8216": $codigo = "Nomina.Deducciones.Deduccion.Importe no es mayor que cero."; break;
			case "8217": $codigo = "El valor del atributo Nomina.OtrosPagos.OtroPago.TipoOtroPago no cumple con un valor del catálogo c_TipoOtroPago."; break;
			case "8218": $codigo = "El nodo CompensacionSaldosAFavor debe existir, ya que el valor de Nomina.OtrosPagos.OtroPago.TipoOtroPago es 004."; break;
			case "8219": $codigo = "El nodo SubsidioAlEmpleo. debe existir, ya que el valor de Nomina.OtrosPagos.OtroPago.TipoOtroPago es 002."; break;
			case "8220": $codigo = "Nomina.OtrosPagos.OtroPago.Importe no esmayor que cero."; break;
			case "8221": $codigo = "Nomina.OtrosPagos.OtroPago.SubsidioAlEmpleo.SubsidioCausado no es mayor o igual que el valor del atributo Importe del nodo OtroPago."; break;
			case "8222": $codigo = "Nomina.OtrosPagos.OtroPago.CompensacionSaldosAFavor.SaldoAFavor no es mayor o igual que el valor del atributo CompensacionSaldosAFavor:RemanenteSalFav."; break;
			case "8223": $codigo = "Nomina.OtrosPagos.OtroPago.CompensacionSaldosAFavor.Año  no es menor que el año en curso."; break;
			case "8224": $codigo = "El valor del atributo Incapacidades.Incapacidad.TipoIncapacidad no cumple con un valor del catálogo c_TIpoIncapacidad."; break;
			
			
			case "7001": $codigo = "CFDI33101 - El campo Fecha no cumple con el patrón requerido."; break;
			case "7002": $codigo = "CFDI33102 - El resultado de la digestión debe ser igual al resultado de la desencripción del sello."; break;
			case "7003": $codigo = "CFDI33103 - Si existe el complemento para recepción de pagos este campo no debe existir"; break;
			case "7004": $codigo = "CFDI33104 - El campo FormaPago no contiene un valor del catálogo c_FormaPago."; break;
			case "7005": $codigo = "CFDI33105 - EL certificado no cumple con alguno de los valores permitidos"; break;
			case "7006": $codigo = "CFDI33106 - El valor de este campo SubTotal excede la cantidad de decimales que soporta la moneda."; break;
			case "7007": $codigo = "CFDI33107 - El TipoDeComprobante es I,E o N, el importe registrado en el campo no es igual a la suma de los importes de los conceptos registrados."; break;
			case "7008": $codigo = "CFDI33108 - El TipoDeComprobante es T o P y el importe no es igual a 0, o cero con decimales."; break;
			case "7009": $codigo = "CFDI33109 - El valor registrado en el campo Descuento no es menor o igual que el campo Subtotal."; break;
			case "7010": $codigo = "CFDI33110 - El TipoDeComprobante NO es I,E o N, y un concepto incluye el campo descuento."; break;
			case "7011": $codigo = "CFDI33111 - El valor del campo Descuento excede la cantidad de decimales que soporta la moneda."; break;
			case "7012": $codigo = "CFDI33112 - El campo Moneda no contiene un valor del catálogo c_Moneda."; break; 
			case "7013": $codigo = "CFDI33113 - El campo TipoCambio no tiene el valor 1 y la moneda indicada es MXN."; break;
			case "7014": $codigo = "CFDI33114 - El campo TipoCambio se debe registrar cuando el campo Moneda tiene un valor distinto de MXN y XXX."; break;
			case "7015": $codigo = "CFDI33115 - El campo TipoCambio no se debe registrar cuando el campo Moneda tiene el valor XXX."; break;
			case "7016": $codigo = "CFDI33116 - El campo TipoCambio no cumple con el patrón requerido."; break;
			case "7017": $codigo = "CFDI33117 - Cuando el valor del campo TipoCambio se encuentre fuera de los límites establecidos, debe existir el campo Confirmacion"; break;
			case "7018": $codigo = "CFDI33118 - El campo Total no corresponde con la suma del subtotal, menos los descuentos aplicables, más las contribuciones recibidas (impuestos trasladados - federales o locales, derechos, productos, aprovechamientos, aportaciones de seguridad social, contribuciones de mejoras) menos los impuestos retenidos."; break;
			case "7019": $codigo = "CFDI33119 - Cuando el valor del campo Total se encuentre fuera de los límites establecidos, debe existir el campo Confirmacion"; break;
			case "7020": $codigo = "CFDI33120 - El campo TipoDeComprobante, no contiene un valor del catálogo c_TipoDeComprobante."; break;
			case "7021": $codigo = "CFDI33121 - El campo MetodoPago, no contiene un valor del catálogo c_MetodoPago."; break;
			case "7022": $codigo = "CFDI33122 - Cuando se tiene el valor PIP en el campo MetodoPago y el valor en el campo TipoDeComprobante es I ó E, el CFDI debe contener un complemento de recibo de pago"; break;
			case "7023": $codigo = "CFDI33123 - Se debe omitir el campo MetodoPago cuando el TipoDeComprobante es T o P"; break;
			case "7024": $codigo = "CFDI33124 - Si existe el complemento para recepción de pagos en este CFDI este campo no debe existir."; break;
			case "7025": $codigo = "CFDI33125 - El campo LugarExpedicion, no contiene un valor del catálogo c_LugarExpedicion."; break;
			case "7026": $codigo = "CFDI33126 - El campo Confirmacion no debe existir cuando los atributios TipoCambio y/o Total están dentro del rango permitido"; break;
			case "7027": $codigo = "CFDI33127 - Número de confirmación inválido"; break;
			case "7028": $codigo = "CFDI33128 - Número de confrirmación utilizado previamente"; break;
			case "7029": $codigo = "CFDI33129 - El campo TipoRelacion, no contiene un valor del catálogo c_TipoRelacion."; break;
			case "7030": $codigo = "CFDI33130 - El campo RegimenFiscal, no contiene un valor del catálogo c_RegimenFiscal."; break;
			case "7031": $codigo = "CFDI33131 - La clave del campo RegimenFiscal debe corresponder con el tipo de persona (fisica o moral)"; break;
			case "7032": $codigo = "El RFC del Receptor, no existe en la lista de RFC inscritos no cancelados antes el SAT"; break;
			
			case "7033": $codigo = "CFDI33133 - El campo ResidenciaFiscal, no contiene un valor del catálogo c_Pais"; break;
			case "7034": $codigo = "CFDI33134 - El RFC del receptor es de un RFC registrado en el SAT o un RFC genérico nacional y EXISTE el campo ResidenciaFiscal."; break;
			case "7035": $codigo = "CFDI33135 - El valor del campo ResidenciaFiscal no puede ser MEX"; break;
			case "7036": $codigo = "CFDI33136 - Se debe registrar un valor de acuerdo al catálogo c_Pais en en el campo ResidenciaFiscal, cuando en el en el campo NumRegIdTrib se registre información."; break;
			case "7037": $codigo = "CFDI33137 - El valor del campo es un RFC inscrito no cancelado en el SAT o un RFC genérico nacional, y se registró el campo NumRegIdTrib."; break;
			case "7038": $codigo = "CFDI33138 - Para registrar el campo NumRegIdTrib, el CFDI debe contener el complemento de comercio exterior y el RFC del receptor debe ser un RFC genérico extranjero."; break;
			case "7039": $codigo = "CFDI33139 - El campo NumRegIdTrib no cumple con el patrón correspondiente."; break;
			case "7040": $codigo = "CFDI33140 - El campo UsoCFDI, no contiene un valor del catálogo c_UsoCFDI."; break;
			case "7041": $codigo = "CFDI33141 - La clave del campo UsoCFDI debe corresponder con el tipo de persona (fisica o moral)"; break;
			case "7042": $codigo = "CFDI33142 - El campo ClaveProdServ, no contiene un valor del catálogo c_ClaveProdServ."; break;
			case "7043": $codigo = "CFDI33143 - No existe el complemento requerido para el valor de ClaveProdServ"; break;
			case "7044": $codigo = "CFDI33144 - No está declarado el impuesto relacionado con el valor de ClaveProdServ"; break;
			case "7045": $codigo = "CFDI33145 - El campo ClaveUnidad no contiene un valor del catálogo c_ClaveUnidad."; break;
			case "7046": $codigo = "CFDI33146 - El valor del campo ValorUnitario debe tener hasta la cantidad de decimales que soporte la moneda."; break;
			case "7047": $codigo = "CFDI33147 - El valor valor del campo ValorUnitario debe ser mayor que cero (0) cuando el tipo de comprobante es Ingreso, Egreso o Nomina"; break;
			case "7048": $codigo = "CFDI33148 - El valor del campo Importe debe tener hasta la cantidad de decimales que soporte la moneda."; break;
			case "7049": $codigo = "CFDI33149 - El valor del campo Importe no se encuentra entre el limite inferior y superior permitido"; break;
			case "7050": $codigo = "CFDI33150 - El valor del campo Descuento debe tener hasta la cantidad de decimales que tenga registrado el atributo importe del concepto."; break;
			case "7051": $codigo = "CFDI33151 - El valor del campo Descuento es mayor que el campo Importe"; break;
			case "7052": $codigo = "CFDI33152 - En caso de utilizar el nodo Impuestos en un concepto, se deben incluir impuestos de traslado y/o retenciones"; break;
			case "7053": $codigo = "CFDI33153 - El valor del campo Base que corresponde a Traslado debe tener hasta la cantidad de decimales que soporte la moneda."; break;
			case "7054": $codigo = "CFDI33154 - El valor del campo Base que corresponde a Traslado debe ser mayor que cero"; break;
			case "7055": $codigo = "CFDI33155 - El valor del campo Impuesto que corresponde a Traslado no contiene un valor del catálogo c_Impuesto."; break;
			case "7056": $codigo = "CFDI33156 - El valor del campo TipoFactor que corresponde a Traslado no contiene un valor del catálogo c_TipoFactor."; break;
			case "7057": $codigo = "CFDI33157 - Si el valor registrado en el campo TipoFactor que corresponde a Traslado es Exento no se deben registrar los campos TasaOCuota ni Importe."; break;
			case "7058": $codigo = "CFDI33158 - Si el valor registrado en el campo TipoFactor que corresponde a Traslado es Tasa o Cuota, se deben registrar los campos TasaOCuota e Importe."; break;
			case "7059": $codigo = "CFDI33159 - El valor del campo TasaOCuota que corresponde a Traslado no contiene un valor del catálogo c_TasaOcuota o se encuentra fuera de rango."; break;
			case "7060": $codigo = "CFDI33160 - El valor del campo Importe que corresponde a Traslado debe tener hasta la cantidad de decimales que soporte la moneda."; break;
			case "7061": $codigo = "CFDI33161 - El valor del campo Importe o que corresponde a Traslado no se encuentra entre el limite inferior y superior permitido"; break;
			case "7062": $codigo = "CFDI33162 - El valor del campo Base que corresponde a Retención debe tener hasta la cantidad de decimales que soporte la moneda."; break;
			case "7063": $codigo = "CFDI33163 - El valor del campo Base que corresponde a Retención debe ser mayor que cero."; break;
			case "7064": $codigo = "CFDI33164 - El valor del campo Impuesto que corresponde a Retención no contiene un valor del catálogo c_Impuesto."; break;
			case "7065": $codigo = "CFDI33165 - El valor del campo TipoFactor que corresponde a Retención no contiene un valor del catálogo c_TipoFactor."; break;
			case "7066": $codigo = "CFDI33166 - Si el valor registrado en el campo TipoFactor que corresponde a Retención debe ser distinto de Excento."; break;
			case "7067": $codigo = "CFDI33167 - El valor del campo TasaOCuota que corresponde a Retención no contiene un valor del catálogo c_TasaOcuota o se encuentra fuera de rango."; break;
			case "7068": $codigo = "CFDI33168 - El valor del campo Importe que corresponde a Retención debe tener hasta la cantidad de decimales que soporte la moneda."; break;
			case "7069": $codigo = "CFDI33169 - El valor del campo Importe que corresponde a Retención no se encuentra entre el limite inferior y superior permitido."; break;
			case "7070": $codigo = "CFDI33170 - El número de pedimento es inválido"; break;
			case "7071": $codigo = "CFDI33171 - El NumeroPedimento no debe existir si se incluye el complemento de comercio exterior"; break;
			case "7072": $codigo = "CFDI33172 - El campo ClaveProdServ, no contiene un valor del catálogo c_ClaveProdServ."; break;
			case "7073": $codigo = "CFDI33173 - El valor del campo ValorUnitario debe tener hasta la cantidad de decimales que soporte la moneda."; break;
			case "7074": $codigo = "CFDI33174 - El valor del campo ValorUnitario debe ser mayor que cero (0)"; break;
			case "7075": $codigo = "CFDI33175 - El valor del campo ValorUnitario debe tener hasta la cantidad de decimales que soporte la moneda."; break;
			case "7076": $codigo = "CFDI33176 - El valor del campo Importe no se encuentra entre el limite inferior y superior permitido"; break;
			case "7077": $codigo = "CFDI33177 - El número de pedimento es inválido"; break;
			case "7078": $codigo = "CFDI33178 - El NumeroPedimento no debe existir si se incluye el complemento de comercio exterior"; break;
			case "7079": $codigo = "CFDI33179 - Cuando el TipoDeComprobante sea T o P, este elemento no debe existir."; break;
			case "7080": $codigo = "CFDI33180 - El valor del campo TotalImpuestosRetenidos debe tener hasta la cantidad de decimales que soporte la moneda."; break;
			case "7081": $codigo = "CFDI33181 - El valor del campo TotalImpuestosRetenidos debe ser igual a la suma de los importes registrados en el elemento hijo Retencion."; break;
			case "7082": $codigo = "CFDI33182 - El valor del campo TotalImpuestosTrasladados debe tener hasta la cantidad de decimales que soporte la moneda."; break;
			case "7083": $codigo = "CFDI33183 - El valor del campo TotalImpuestosTrasladados no es igual a la suma de los importes registrados en el elemento hijo Traslado"; break;
			case "7084": $codigo = "CFDI33184 - Debe existir el campo TotalImpuestosRetenidos"; break;
			case "7085": $codigo = "CFDI33185 - El campo Impuesto no contiene un valor del catálogo c_Impuesto."; break;
			case "7086": $codigo = "CFDI33186 - Debe haber sólo un registro por cada tipo de impuesto retenido."; break;
			case "7087": $codigo = "CFDI33187 - Debe existir el campo TotalImpuestosRetenidos"; break;
			case "7088": $codigo = "CFDI33188 - El valor del campo Importe correspondiente a Retención debe tener hasta la cantidad de decimales que soporte la moneda."; break;
			case "7089": $codigo = "CFDI33189 - El campo Importe correspondiente a Retención no es igual a la suma de los importes de los impuestos retenidos registrados en los conceptos donde el impuesto sea igual al campo impuesto de este elemento."; break;
			case "7090": $codigo = "CFDI33190 - Debe existir el campo TotalImpuestosTrasladados"; break;
			case "7091": $codigo = "CFDI33191 - El campo Impuesto no contiene un valor del catálogo c_Impuesto."; break;
			case "7092": $codigo = "CFDI33192 - Debe haber sólo un registro con la misma combinación de impuesto, factor y tasa por cada traslado."; break;
			case "7093": $codigo = "CFDI33193 - El valor seleccionado debe corresponder a un valor del catalogo donde la columna impuesto corresponda con el campo impuesto y la columna factor corresponda con el campo TipoFactor"; break;
			case "7094": $codigo = "CFDI33194 - El valor del campo Importe correspondiente a Traslado debe tener hasta la cantidad de decimales que soporte la moneda."; break;
			case "7095": $codigo = "CFDI33195 - El campo Importe correspondiente a Traslado no es igual a la suma de los importes de los impuestos trasladados registrados en los conceptos donde el impuesto del concepto sea igual al campo impuesto de este elemento y la TasaOCuota del concepto sea igual al campo TasaOCuota de este elemento."; break;
			
			case "7096": $codigo = "CRP101 - El valor del campo TipoDeComprobante debe ser P"; break;
			case "7097": $codigo = "CRP102 - El valor del campo SubTotal debe ser cero 0"; break;
			case "7098": $codigo = "CRP103 - El valor del campo Moneda debe ser XXX"; break;
			case "7099": $codigo = "CRP104 - El campo FormaPago no se debe registrar en el CFDI."; break;
			case "7100": $codigo = "CRP105 - El campo MetodoPago no se debe registrar en el CFDI."; break;
			case "7101": $codigo = "CRP106 - El campo CondicionesDePago no se debe registrar en el CFDI."; break;
			case "7102": $codigo = "CRP107 - El campo Descuento no se debe registrar en el CFDI."; break;
			case "7103": $codigo = "CRP108 - El campo TipoCambio no se debe registrar en el CFDI."; break;
			case "7104": $codigo = "CRP109 - El valor del campo Total debe ser cero 0"; break;
			case "7105": $codigo = "CRP110 - El valor del campo UsoCFDI debe ser P01"; break;
			case "7106": $codigo = "CRP111 - Solo debe existir un Concepto en el CFDI."; break;
			case "7107": $codigo = "CRP112 - No se deben registrar apartados dentro de Conceptos"; break;
			case "7108": $codigo = "CRP113 - El valor del campo ClaveProdServ debe ser 84111506"; break;
			case "7109": $codigo = "CRP114 - El campo NoIdentificacion no se debe registrar en el CFDI."; break;
			case "7110": $codigo = "CRP115 - El valor del campo Cantidad debe ser 1"; break;
			case "7111": $codigo = "CRP116 - El valor del campo ClaveUnidad debe ser ACT"; break;
			case "7112": $codigo = "CRP117 - El campo Unidad no se debe registrar en el CFDI."; break;
			case "7113": $codigo = "CRP118 - El valor del campo Descripcion debe ser Pago"; break;
			case "7114": $codigo = "CRP119 - El valor del campo ValorUnitario debe ser cero 0"; break;
			case "7115": $codigo = "CRP120 - El valor del campo Importe debe ser cero 0"; break;
			case "7116": $codigo = "CRP121 - El campo Descuento no se debe registrar en el CFDI."; break;
			case "7117": $codigo = "CRP122 - No se debe registrar el apartado de Impuestos en el CFDI."; break;
			case "7118": $codigo = "CRP201 - El valor del campo FormaDePagoP debe ser distinto de 99"; break;
			case "7119": $codigo = "CRP202 - El campo MonedaP debe ser distinto de XXX"; break;
			case "7120": $codigo = "CRP203 - El campo TipoCambioP se debe registrar."; break;
			case "7121": $codigo = "CRP204 - El campo TipoCambioP no se debe registrar."; break;
			case "7122": $codigo = "CRP205 - Cuando el valor del campo TipoCambioP se encuentre fuera de los límites establecidos, debe existir el campo Confirmacion"; break;
			case "7123": $codigo = "CRP206 - La suma de los valores registrados en el campo ImpPagado de los apartados DoctoRelacionado no es menor o igual que el valor del campo Monto."; break;
			case "7124": $codigo = "CRP207 - El valor del campo Monto no es mayor que cero 0"; break;
			case "7125": $codigo = "CRP208 - El valor del campo Monto debe tener hasta la cantidad de decimales que soporte la moneda registrada en el campo MonedaP."; break;
			case "7126": $codigo = "CRP209 - Cuando el valor del campo Monto se encuentre fuera de los límites establecidos, debe existir el campo Confirmacion"; break;
			case "7127": $codigo = "CRP210 - El RFC del campo RfcEmisorCtaOrd no se encuentra en la lista de RFC."; break;
			case "7128": $codigo = "CRP211 - El campo NomBancoOrdExt se debe registrar."; break;
			case "7129": $codigo = "CRP212 - El campo CtaOrdenante no se debe registrar."; break;
			case "7130": $codigo = "CRP213 - El campo CtaOrdenante no cumple con el patrón requerido."; break;
			case "7131": $codigo = "CRP214 - El campo RfcEmisorCtaBen no se debe registrar."; break;
			case "7132": $codigo = "CRP215 - El campo CtaBeneficiario no se debe registrar."; break;
			case "7133": $codigo = "CRP216 - El campo TipoCadPago no se debe registrar."; break;
			case "7134": $codigo = "CRP217 - El valor del campo MonedaDR debe ser distinto de XXX"; break;
			case "7135": $codigo = "CRP218 - El campo TipoCambioDR se debe registrar."; break;
			case "7136": $codigo = "CRP219 - El campo TipoCambioDR no se debe registrar."; break;
			case "7137": $codigo = "CRP220 - El campo TipoCambioDR debe ser 1"; break;
			case "7138": $codigo = "CRP221 - El campo ImpSaldoAnt debe mayor a cero."; break;
			case "7139": $codigo = "CRP222 - El valor del campo ImpSaldoAnt debe tener hasta la cantidad de decimales que soporte la moneda registrada en el campo MonedaDR."; break;
			case "7140": $codigo = "CRP223 - El campo ImpPagado debe mayor a cero."; break;
			case "7141": $codigo = "CRP224 - El valor del campo ImpPagado debe tener hasta la cantidad de decimales que soporte la moneda registrada en el campo MonedaDR."; break;
			case "7142": $codigo = "CRP225 - El valor del campo ImpSaldoInsoluto debe tener hasta la cantidad de decimales que soporte la moneda registrada en el campo MonedaDR."; break;
			case "7143": $codigo = "CRP226 - El campo ImpSaldoInsoluto debe ser mayor o igual a cero y calcularse con la suma de los campos ImSaldoAnt menos el ImpPagado o el Monto."; break;
			case "7144": $codigo = "CRP227 - El campo CertPago se debe registrar."; break;
			case "7145": $codigo = "CRP228 - El campo CertPago no se debe registrar."; break;
			case "7146": $codigo = "CRP229 - El campo CadPago se debe registrar."; break;
			case "7147": $codigo = "CRP230 - El campo CadPago no se debe registrar."; break;
			case "7148": $codigo = "CRP231 - El campo SelloPago se debe registrar."; break;
			case "7149": $codigo = "CRP232 - El campo SelloPago no se debe registrar."; break;
			case "7150": $codigo = "CRP233 - El campo NumParcialidad se debe registrar."; break;
			case "7151": $codigo = "CRP234 - El campo ImpSaldoAnt se debe registrar."; break;
			case "7152": $codigo = "CRP235 - El campo ImpPagado se debe registrar."; break;
			case "7153": $codigo = "CRP236 - El campo ImpSaldoInsoluto se debe registrar."; break;
			case "7154": $codigo = "CRP237 - No debe exstir el apartado de Impuestos."; break;
			case "7155": $codigo = "CRP238 - El campo RfcEmisorCtaOrd no se debe registrar."; break;
			
			case "7156": $codigo = "CRP239 - El campo CtaBeneficiario no cumple con el patrón requerido."; break;
			case "7157": $codigo = "CRP999 - Error no clasificado"; break;
			
			#case -1; $codigo="Error al sellar el CFD";break;
			
			default:$codigo='Error en el proceso';break;
		}
		
		return $codigo;
	}
	
	function crearDirectorio($Ruta)
	{
		$band=false;

		if(!file_exists($Ruta))
		{
			$Crear=mkdir($Ruta,0777);
			
			if($Crear)
			{
				chmod($Ruta,0777);
				$Band=true;
			}
		}
		else
		{
			$Band=false;
		}  
		
		return $Band;   
	}
	
	function leerFichero($file='', $mode='READ', $input='') 
	{
		if ($mode == "READ") 
		{
			if (file_exists($file)) 
			{
				$handle = fopen($file, "r");
				$output = fread($handle, filesize($file));
				return $output; // output file text
			} 
			else 
			{
				return false; // failed.
			}
		} 
		elseif ($mode == "WRITE") 
		{
			$handle = fopen($file, "w");
			
			if (!fwrite($handle, $input)) 
			{
				return false; // failed.
			} 
			else 
			{
				return true; //success.
			}
		} 
		elseif($mode == "READ/WRITE") 
		{
			if (file_exists($file) && isset($input)) 
			{
				$handle = fopen($file,"r+");
				$read = fread($handle, filesize($file));
				$data = $read.$input;
				
				if (!fwrite($handle, $data)) 
				{
					return false; // failed.
				} 
				else 
				{
					return true; // success.
				}
			} 
			else 
			{
				return false; // failed.
			}
		} 
		else 
		{
			return false; // failed.
		}
		
		fclose($handle);
	}
	
	function guardarArchivoXML($Archivo,$XML)
	{
		$fh =fopen($Archivo,'w');
		
		if($fh)
		{
			fwrite($fh,$XML);
			fclose($fh);
			return true;
		}
		else 
		{
			return false;
		}
	}
	
	//FUNCIONES NUEVAS CONTABILIDAD
	
	function guardarFichero($Archivo,$XML)
	{
		$fh =fopen($Archivo,'w');
	
		if($fh)
		{
			fwrite($fh,$XML);
			fclose($fh);
			return true;
		}
	
		else 
		{
			return false;
		}
	}
	
	function calcularTamanoDisco($carpeta) //OBTENER CUOTA DE ARCHIVOS
	{
		if(file_exists($carpeta))
		{
			$tamano = 0;
			foreach(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($carpeta)) as $file)
			{
				$tamano+=$file->getSize();
			}
			
			return $tamano/1024/1024;
		}
		else
		{
			return 0;
		}
	}
	
	function obtenerTipoCatalogo($tipo) //TIPO DE PÓLIZA
	{
		switch($tipo)
		{
			case 0:$tipo="Ingresos y egresos";break;
			case 1:$tipo="Ingresos";break;
			case 2:$tipo="Egresos";break;
		}
		
		return $tipo;
	}
	
	//CONTABILIDAD ELECTRÓNICA
	function obtenerTipoPoliza($tipo) //TIPO DE PÓLIZA
	{
		switch($tipo)
		{
			case 1:$tipo="Ingreso";break;
			case 2:$tipo="Egreso";break;
			case 3:$tipo="Diario";break;
		}
		
		return $tipo;
	}
	
	function leerArchivoHexadecimal($fichero)
	{
		try
		{
			if(file_exists($fichero))
			{
				$gestor 		= fopen($fichero, "r");
				$contenido 		= fread($gestor, filesize($fichero));
				
				fclose($gestor);
				
				return obtenerMayusculas(bin2hex($contenido));
			}
			else
			{
				return "0";
			}
		}
		
		catch(Exception $ex)
		{	
			return "0";
		}
	}
	
	function obtenerCeldaExcel($numero)
	{
		$letra="A";
		
		switch($numero)
		{
			case  '1':$letra="A";break;
			case  '2':$letra="B";break;
			case  '3':$letra="C";break;
			case  '4':$letra="D";break;
			case  '5':$letra="E";break;
			case  '6':$letra="F";break;
			case  '7':$letra="G";break;
			case  '8':$letra="H";break;
			case  '9':$letra="I";break;
			case '10':$letra="J";break;
			case '11':$letra="K";break;
			case '12':$letra="L";break;
			case '13':$letra="M";break;
			case '14':$letra="N";break;
			case '15':$letra="O";break;
			case '16':$letra="P";break;
			case '17':$letra="Q";break;
			case '18':$letra="R";break;
			case '19':$letra="S";break;
			case '20':$letra="T";break;
			case '21':$letra="U";break;
			case '22':$letra="V";break;
			case '23':$letra="W";break;
			case '24':$letra="X";break;
			case '25':$letra="Y";break;
			case '26':$letra="Z";break;
			
			case '27':$letra="AA";break;
			case '28':$letra="AB";break;
			case '29':$letra="AC";break;
			case '30':$letra="AD";break;
			case '31':$letra="AE";break;
			case '32':$letra="AF";break;
			case '33':$letra="AG";break;
			case '34':$letra="AH";break;
			case '35':$letra="AI";break;
			case '36':$letra="AJ";break;
			case '37':$letra="AK";break;
			case '38':$letra="AL";break;
			case '39':$letra="AM";break;
			case '40':$letra="AN";break;
			case '41':$letra="AO";break;
			case '42':$letra="AP";break;
			case '43':$letra="AQ";break;
			case '44':$letra="AR";break;
			case '45':$letra="AS";break;
			case '46':$letra="AT";break;
			case '47':$letra="AU";break;
			case '48':$letra="AV";break;
			case '49':$letra="AW";break;
			case '50':$letra="AX";break;
			case '51':$letra="AY";break;
			case '52':$letra="AZ";break;
			
			case '53':$letra="BA";break;
			case '54':$letra="BB";break;
			case '55':$letra="BC";break;
			case '56':$letra="BD";break;
			case '57':$letra="BE";break;
			case '58':$letra="BF";break;
			case '59':$letra="BG";break;
			case '60':$letra="BH";break;
			case '61':$letra="BI";break;
			case '62':$letra="BJ";break;
			case '63':$letra="BK";break;
			case '64':$letra="BL";break;
			case '65':$letra="BM";break;
			case '66':$letra="BN";break;
			case '67':$letra="BO";break;
			case '68':$letra="BP";break;
			case '69':$letra="BQ";break;
			case '70':$letra="BR";break;
			case '71':$letra="BS";break;
			case '72':$letra="BT";break;
			case '73':$letra="BU";break;
			case '74':$letra="BV";break;
			case '75':$letra="BW";break;
			case '76':$letra="BX";break;
			case '77':$letra="BY";break;
			case '78':$letra="BZ";break;
			
			case '79':$letra="CA";break;
			case '80':$letra="CB";break;
			case '81':$letra="CC";break;
			case '82':$letra="CD";break;
			case '83':$letra="CE";break;
			case '84':$letra="CF";break;
			case '85':$letra="CG";break;
			case '86':$letra="CH";break;
			case '87':$letra="CI";break;
			case '88':$letra="CJ";break;
			case '89':$letra="CK";break;
			case '90':$letra="CL";break;
			case '91':$letra="CM";break;
			case '92':$letra="CN";break;
			case '93':$letra="CO";break;
			case '94':$letra="CP";break;
			case '95':$letra="CQ";break;
			case '96':$letra="CR";break;
			case '97':$letra="CS";break;
			case '98':$letra="CT";break;
			case '99':$letra="CU";break;
			case '100':$letra="CV";break;
			case '101':$letra="CW";break;
			case '102':$letra="CX";break;
			case '103':$letra="CY";break;
			case '104':$letra="CZ";break;
			
			case '105':$letra="DA";break;
			case '106':$letra="DB";break;
			case '107':$letra="DC";break;
			case '108':$letra="DD";break;
			case '109':$letra="DE";break;
			case '110':$letra="DF";break;
			case '111':$letra="DG";break;
			case '112':$letra="DH";break;
			case '113':$letra="DI";break;
			case '114':$letra="DJ";break;
			case '115':$letra="DK";break;
			case '116':$letra="DL";break;
			case '117':$letra="DM";break;
			case '118':$letra="DN";break;
			case '119':$letra="DO";break;
			case '120':$letra="DP";break;
			case '121':$letra="DQ";break;
			case '122':$letra="DR";break;
			case '123':$letra="DS";break;
			case '124':$letra="DT";break;
			case '125':$letra="DU";break;
			case '126':$letra="DV";break;
			case '127':$letra="DW";break;
			case '128':$letra="DX";break;
			case '129':$letra="DY";break;
			case '130':$letra="DZ";break;
			
			case '131':$letra="EA";break;
			case '132':$letra="EB";break;
			case '133':$letra="EC";break;
			case '134':$letra="ED";break;
			case '135':$letra="EE";break;
			case '136':$letra="EF";break;
			case '137':$letra="EG";break;
			case '138':$letra="EH";break;
			case '139':$letra="EI";break;
			case '140':$letra="EJ";break;
			case '141':$letra="EK";break;
			case '142':$letra="EL";break;
			case '143':$letra="EM";break;
			case '144':$letra="EN";break;
			case '145':$letra="EO";break;
			case '146':$letra="EP";break;
			case '147':$letra="EQ";break;
			case '148':$letra="ER";break;
			case '149':$letra="ES";break;
			case '150':$letra="ET";break;
			case '151':$letra="EU";break;
			case '152':$letra="EV";break;
			case '153':$letra="EW";break;
			case '154':$letra="EX";break;
			case '155':$letra="EY";break;
			case '156':$letra="EZ";break;
			
			case '157':$letra="FA";break;
			case '158':$letra="FB";break;
			case '159':$letra="FC";break;
			case '160':$letra="FD";break;
			case '161':$letra="FE";break;
			case '162':$letra="FF";break;
			case '163':$letra="FG";break;
			case '164':$letra="FH";break;
			case '165':$letra="FI";break;
			case '166':$letra="FJ";break;
			case '167':$letra="FK";break;
			case '168':$letra="FL";break;
			case '169':$letra="FM";break;
			case '170':$letra="FN";break;
			case '171':$letra="FO";break;
			case '172':$letra="FP";break;
			case '173':$letra="FQ";break;
			case '174':$letra="FR";break;
			case '175':$letra="FS";break;
			case '176':$letra="FT";break;
			case '177':$letra="FU";break;
			case '178':$letra="FV";break;
			case '179':$letra="FW";break;
			case '180':$letra="FX";break;
			case '181':$letra="FY";break;
			case '182':$letra="FZ";break;
			
			case '183':$letra="GA";break;
			case '184':$letra="GB";break;
			case '185':$letra="GC";break;
			case '186':$letra="GD";break;
			case '187':$letra="GE";break;
			case '188':$letra="GF";break;
			case '189':$letra="GG";break;
			case '190':$letra="GH";break;
			case '191':$letra="GI";break;
			case '192':$letra="GJ";break;
			case '193':$letra="GK";break;
			case '194':$letra="GL";break;
			case '195':$letra="GM";break;
			case '196':$letra="GN";break;
			case '197':$letra="GO";break;
			case '198':$letra="GP";break;
			case '199':$letra="GQ";break;
			case '200':$letra="GR";break;
			case '201':$letra="GS";break;
			case '202':$letra="GT";break;
			case '203':$letra="GU";break;
			case '204':$letra="GV";break;
			case '205':$letra="GW";break;
			case '206':$letra="GX";break;
			case '207':$letra="GY";break;
			case '208':$letra="GZ";break;
			
			case '209':$letra="HA";break;
			case '210':$letra="HB";break;
			case '211':$letra="HC";break;
			case '212':$letra="HD";break;
			case '213':$letra="HE";break;
			case '214':$letra="HF";break;
			case '215':$letra="HG";break;
			case '216':$letra="HH";break;
			case '217':$letra="HI";break;
			case '218':$letra="HJ";break;
			case '219':$letra="HK";break;
			case '220':$letra="HL";break;
			case '221':$letra="HM";break;
			case '222':$letra="HN";break;
			case '223':$letra="HO";break;
			case '224':$letra="HP";break;
			case '225':$letra="HQ";break;
			case '226':$letra="HR";break;
			case '227':$letra="HS";break;
			case '228':$letra="HT";break;
			case '229':$letra="HU";break;
			case '230':$letra="HV";break;
			case '231':$letra="HW";break;
			case '232':$letra="HX";break;
			case '233':$letra="HY";break;
			case '234':$letra="HZ";break;
		}
		
		return $letra;
	}

	function encriptarCertificado($archivo,$carpetaFel)
	{
		try
		{
			if(file_exists($archivo))
			{
				exec('openssl x509 -inform DER -outform PEM -in '.$archivo.' -pubkey -out '.$carpetaFel.'certificado.pem');
				$certificado	= leerFichero($carpetaFel.'certificado.pem',"READ","");

				return $certificado;
			}
			else
			{
				return "";
			}
		}
		
		catch(Exception $ex)
		{	
			return "";
		}
	}

	function encriptarLlave($archivo,$carpetaFel,$passwordLlave,$password)
	{
		try
		{
			$password	= str_replace('&','\&',$password);
			
			if(file_exists($archivo))
			{
				exec('openssl pkcs8 -inform DER -in '.$archivo.' -passin pass:'.$passwordLlave.' -out '.$carpetaFel.'llave.pem');
				exec('openssl rsa -in '.$carpetaFel.'llave.pem -des3 -out '.$carpetaFel.'llaveDes3.txt -passout pass:'.$password);
				#echo ('openssl rsa -in '.$carpetaFel.'llave.pem -des3 -out '.$carpetaRfc.'llaveDes3.txt -passout pass:'.$password);
				$llave	= leerFichero($carpetaFel.'llaveDes3.txt',"READ","");
				
				return $llave;
			}
			else
			{
				return "";
			}
		}
		
		catch(Exception $ex)
		{	
			return "";
		}
	}

	function objetoArreglo ( $xmlObject, $out = array () )
	{
		foreach ( (array) $xmlObject as $index => $node )
			$out[$index] = ( is_object ( $node ) ) ? objetoArreglo ( $node ) : $node;
	
		return $out;
	}

	function obtenerCodigoBarras()
	{
		return rand(10000000000,99999999999);
	}

	function obtenerCodigoGenerico()
	{
		return rand(100000000,999999999);
	}

	function obtenerNumeral($folio)
	{
		switch(strlen($folio))
		{
			case 1: return '00'.$folio; break;
			case 2: return '0'.$folio; break;
			default: return $folio; break;

		}
	}
?>
