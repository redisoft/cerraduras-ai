<?php
if($personal!=null)
{
	echo '
	<input type="hidden" id="txtDias" value="'.$dias.'" />
	<table class="admintable" width="100%" >
		<tr>
			<th colspan="6" class="encabezadoPrincipal" style="border-right:none">
				Nómina
				<img onclick="window.open(\''.base_url().'reportes/reporteNomina/'.$inicio.'/'.$fin.'\')" 
					src="'.base_url().'img/pdf.png" width="22" title="Pdf" />
			</th>
			<th colspan="2" class="encabezadoPrincipal" align="right" style="border-left:none">
				Total: $'.number_format($total,2).'
			</th>
		</tr>
		<tr>
			<th>#</th>
			<th>Nombre</th>
			<th>Puesto</th>
			<th>Departamento</th>
			<th>Salario por dia</th>
			<th>Días trabajados</th>
			<th>Total</th>
			<th>Acciones</th>
		</tr>';
	
	$i=$limite+1;
	foreach($personal as $row)
	{
		$estilo		=$i%2>0?'class="sinSombra"':'class="sombreado"';
		$salario	=$dias*$row->salario;
		
		echo '
		<tr '.$estilo.'>
			<td align="right">'.$i.'</td>
			<td>'.$row->nombre.'</td>
			<td>'.$row->puesto.'</td>
			<td>'.$row->departamento.'</td>
			<td align="right">$'.number_format($row->salario,2).'</td>
			<td align="center">'.$dias.'</td>
			<td align="right">$'.number_format(round($salario),2).'</td>
			<td align="center">
				<img title="Pagar" onclick="formularioNomina('.$row->idPersonal.')" src="'.base_url().'img/pagos.png" 
					style="height:22px; width:22px;" />
				&nbsp;
				<img onclick="window.open(\''.base_url().'reportes/reporteNomina/'.$inicio.'/'.$fin.'/'.$row->idPersonal.'\')" 
					src="'.base_url().'img/pdf.png" width="22" title="Pdf" />
					
				<br />
				<a>Pagar</a>
				<a>Recibo</a>
			</td>
			
		</tr>';
		
		$i++;
	}
	
	echo '</table>';
}
else
{
	echo '<div class="Error_validar">Sin registro de personal para nómina</div>';
}