<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

function convertir_angel($data, $tipus=1)
{
	if ($data != '' && $tipus == 0 || $tipus == 1)
	{
		$semana = array('Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado');
		$mes = array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'); 
		
		if ($tipus == 1)
		{
			$data = preg_split('/-| /', $data);
			$data = mktime(0,0,0,$data[1],$data[2],$data[0]);
		} 
		
		return date('d', $data).'-'.$mes[date('m',$data)-1].'-'.date('Y', $data);
	}
	else
	{
		return 0;
	}
}
?>