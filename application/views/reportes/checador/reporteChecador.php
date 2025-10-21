<?php
if($checador!=null)
{
	$this->load->view('reportes/checador/encabezado');
	
	echo '
	<table class="admintable" width="100%">';
	
	$i=1;
	foreach($checador as $row)
	{
		$estilo		=$i%2>0?'class="sinSombra"':'class="sombreado"';
		
		echo '
		<tr '.$estilo.'>
			<td align="right" width="2%">' .$i.'</td>
			<td align="center" width="7%">'.obtenerFechaMesCorto($row->fecha).'</td>
			<td align="left" width="15%">' .($row->nombre).'</td>
			<td align="center" width="10%">'.$row->puesto.'</td>
			<td align="center" width="12%">'.$row->departamento.'</td>
			<td align="center" width="8%">'.$row->dia.'</td>
			<td align="center" width="5%">'.$row->horaInicialPersonal.'</td>
			<td align="center" width="8%">'.$row->horaEntrada.'</td>
			<td align="center" width="10%">';
			
			if($row->retardoMinutos!=0)
			{
				echo $row->retardoMinutos>0?$row->retardoMinutos.' a favor':$row->retardoMinutos*(-1).' en contra'; 
			}
			
			echo'
			</td>
			<td align="center" width="5%">'.$row->horaFinalPersonal.'</td>
			<td align="center" width="8%">'.$row->horaSalida.'</td>
			<td align="center" width="10%">';
			
				if($row->horaSalida!=null)
				{
					if($row->salidaMinutos!=0)
					{
						echo $row->salidaMinutos<0?$row->salidaMinutos*(-1).' a favor':$row->salidaMinutos.' en contra'; 
					}
				}
			echo'
			</td>
		</tr>';
		
		$i++;
	}
	
	echo '</table>';
}
else
{
	echo '<div class="Error_validar">Sin registro de checador</div>';
}
?>