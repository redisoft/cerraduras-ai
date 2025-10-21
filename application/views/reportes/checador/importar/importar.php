<?php
#$filas			=file($fichero); 
#utf8_encode(fgets(carpetaFicheros.'checador.csv'));

header('Content-Type: text/html; charset=UTF-8');

$filas			= file(carpetaFicheros.'checador.csv'); 
$productos		= array();
$i				= 0;
$numeroFilas	= count($filas);
$orden			= 0;

$filas = array_map("utf8_encode", $filas);

//Leer lineas de archivos
while($filas[$i]!=NULL)
{
	try
	{
		$fila 				= $filas[$i]; 
		$fila 				= explode(",",$fila); //Cada concepto esta separado por comas

		if($i>0)
		{
			$personal		= $this->importar->obtenerPersonalNombre($fila[1]);

			if($personal!=null)	
			{
				$registro["idPersonal"]	= $personal->idPersonal;
				$operacion				= trim($fila[3]);
				$fechas					= obtenerFechaMysql($fila[2]);
				$fecha					= $fechas[0];
				$hora					= $fechas[1];
				
				$dia 					= date( "l", strtotime ($fecha));
				$registro["dia"]		= obtenerDiaNombre($dia);
				$registro["fecha"]		= $fecha;
				
				$chequeo				= $this->importar->comprobarRegistroChequeo($personal->idPersonal,$fecha);
								
				if($operacion=='E')
				{
					$diferencia					= $this->importar->obtenerDiferenciaChequeo($fecha.' '.$hora,$fecha.' '.$personal->horaInicial);
					$registro["horaEntrada"]	= $hora;
					$registro["retardoMinutos"]	= $diferencia;
					
					
					
					if($chequeo!=null)
					{
						if(strlen($chequeo->horaEntrada)<3)
						{
							$this->importar->editarChequeo($registro,$chequeo->idChequeo);
						}
					}
					else
					{
						$this->importar->registrarChequeo($registro);
					}
				}
				
				if($operacion=='S')
				{
					$diferencia					= $this->importar->obtenerDiferenciaChequeo($fecha.' '.$hora,$fecha.' '.$personal->horaFinal);
					$registro["horaSalida"]		= $hora;
					$registro["salidaMinutos"]	= $diferencia;
					
					if($chequeo!=null)
					{
						if(strlen($chequeo->horaSalida)<3)
						{
							$this->importar->editarChequeo($registro,$chequeo->idChequeo);
						}
					}
					else
					{
						$this->importar->registrarChequeo($registro);
					}
					
				}
			}
		}
		
		$i++;
		
		if(($i)==$numeroFilas)
		{
			break;
		} 
	}
	catch(Exception $ex)
	{
		
	}
}

echo '1';