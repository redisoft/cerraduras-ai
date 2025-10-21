<?php

if($relacion!=null)
{
	$this->load->view('reportes/relacionProveedores/encabezado');
	
	echo'
	<table class="admintable" width="100%">';
		
		$i	= 1;
		
		foreach($relacion as $row)
		{
			$totales	= $this->reportes->obtenerRelacionProveedor($row->idProveedor,$anio,$idEmisor);
			
			echo '
			<tr '.($i%2>0?'class="sombreado"':'class="sinSombra"').'>
				<td width="3%" align="right">'.$i.'</td>
				<td width="15%" align="left">'.$row->emisor.'</td>
				<td width="40%" align="left">'.$row->empresa.'</td>
				<td width="12%" align="left">'.$row->rfc.'</td>
				<td width="10%" align="right">$'.number_format($totales[0],2).'</td>
				<td width="10%" align="right">$'.number_format($totales[1],2).'</td>
				<td width="10%" align="right">$'.number_format($totales[2],2).'</td>
				
			</tr>';
			
			$i++;
		}
	
	echo'
	</table>';
}
else
{
	echo '<div class="Error_validar">Sin registros de relación de proveedores</div>';
}

?>