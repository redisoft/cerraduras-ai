<?php
echo'
<table class="admintable" width="100%" style="margin-top:50px">
	<tr>
		<th>#</th>
		<th>Fecha</th>
		<th>Nombre</th>
		<th>Hora entrada</th>
		<th>Minutos a favor/contra</th>
		<th>Hora salida</th>
		<th>Minutos a favor/contra</th>
	</tr>';

$i=1;
foreach($asistencias as $row)
{
	echo'
	<tr ';echo $i%2>0?' class="sinSombra"':' class="sombreado"';echo ' >
		<td>'.$i.'</td>
		<td align="center">'.obtenerFechaMesCorto($row->fecha).'</td>
		<td align="center">'.$row->nombre.'</td>
		<td align="center">'.$row->horaEntrada.'</td>
		<td align="center">';
		
		if($row->retardoMinutos!=0)
		{
			echo $row->retardoMinutos>0?$row->retardoMinutos.' a favor':$row->retardoMinutos*(-1).' en contra'; 
		}
		
		echo'
		</td>
		<td align="center">'.$row->horaSalida.'</td>
		<td align="center">';
		
			#echo $row->salidaMinutos;
			if($row->horaSalida!=null)
			{
				echo $row->salidaMinutos<0?$row->salidaMinutos*(-1).' a favor':$row->salidaMinutos.' en contra'; 
			}
			
		echo'</td>
	</tr>';
	
	$i++;
}

echo'</table>';