<?php

if($checador!=null)
{
	echo '
	<div style="width:90%; margin-bottom:1%;">
		<ul id="pagination-digg" class="ajax-pagChecador">'.$this->pagination->create_links().'</ul>
	</div>
	
	<div id="generandoReporte"></div>
	<table class="admintable" width="100%">
	<tr>
		<th class="encabezadoPrincipal" colspan="12" style="border-right:none">
			<img onclick="reporteChecador()" src="'.base_url().'img/pdf.png" width="22" title="Pdf" />
			&nbsp;&nbsp;&nbsp;
			<img onclick="excelChecador()" src="'.base_url().'img/excel.png" width="22" title="Excel" />
			<br />
			PDF
			&nbsp;&nbsp;
			Excel
		</th>
		
	</tr>
	<tr>
		<th>#</th>
		<th>Fecha</th>
		<th>Personal</th>
		<th>Puesto</th>
		<th>Departamento</th>
		<th>Día</th>
		<th>Hora entrada</th>
		<th>Hora checado entrada</th>
		<th>Diferencia minutos entrada</th>
		<th>Hora salida</th>
		<th>Hora checado salida</th>
		<th>Diferencia minutos salida</th>
	</tr>';

	$i=1;
	foreach($checador as $row)
	{
		$estilo		=$i%2>0?'class="sinSombra"':'class="sombreado"';
		
		echo '
		<tr '.$estilo.'>
			<td align="right">' .$i.'</td>
			<td align="center">'.obtenerFechaMesCorto($row->fecha).'</td>
			<td align="left">' .($row->nombre).'</td>
			<td align="center">'.$row->puesto.'</td>
			<td align="center">'.$row->departamento.'</td>
			<td align="center">'.$row->dia.'</td>
			<td align="center">'.$row->horaInicialPersonal.'</td>
			<td align="center">'.$row->horaEntrada.'</td>
			<td align="center">';
			
			if($row->retardoMinutos!=0)
			{
				#echo $row->retardoMinutos; 
				echo $row->retardoMinutos>0?$row->retardoMinutos.' a favor':$row->retardoMinutos*(-1).' en contra'; 
			}
			
			echo'
			</td>
			<td align="center">'.$row->horaFinalPersonal.'</td>
			<td align="center">'.$row->horaSalida.'</td>
			<td align="center">';
			
				if($row->horaSalida!=null)
				{
					if($row->salidaMinutos!=0)
					{
						echo $row->salidaMinutos<0?$row->salidaMinutos*(-1).' a favor':$row->salidaMinutos.' en contra'; 
					}
				}
				
				#echo $row->salidaMinutos; 
				
			echo'
			</td>
		</tr>';
		
		$i++;
		
	}
	
	echo '</table>
	<div style="width:90%; margin-bottom:1%;">
		<ul id="pagination-digg" class="ajax-pagChecador">'.$this->pagination->create_links().'</ul>
	</div>';
}
else
{
	echo '<div class="Error_validar">Sin registro de checador</div>';
}