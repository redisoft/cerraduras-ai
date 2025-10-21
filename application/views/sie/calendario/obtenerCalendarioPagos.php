<?php
$data = array();

 foreach($egresos as $row)
 {
	
	 
	 $dia					= round(substr($row->fechaPago,8,2));
	 $data[$dia]['dia']		= 'onclick="obtenerDetallesCalendario(\''.$anio.'-'.$mes.'-'.$dia.'\')"  style="color: green; cursor: pointer" title="Ver detalles '.obtenerFechaMesLargo($anio.'-'.$mes.'-'.$dia).'" ';
	 
	 $proyectado			= $this->proyeccion->sumarEgresosDia($anio.'-'.$mes.'-'.$dia);
	 $creditos				= $this->creditos->sumarCreditosDia($anio.'-'.$mes.'-'.$dia);
	 
	 $data[$dia]['importe']	= '<br />$'.number_format($proyectado+$creditos,decimales);
 }
 
 #print_r($data);

echo $this->calendar->generate($anio, $mes, $data);