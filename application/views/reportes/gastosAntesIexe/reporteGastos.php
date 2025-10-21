<?php
if($gastos!=null)
{
	$this->load->view('reportes/gastos/encabezado');
	
	echo '
	<table class="admintable" width="100%">';
	
	$i=1;
	foreach($gastos as $row)
	{
		$estilo		=$i%2>0?'class="sinSombra"':'class="sombreado"';
		$proveedor	=$this->proveedores->obtenerProveedor($row->idProveedor);
		
		echo '
		<tr '.$estilo.'>
			<td width="3%" align="right">'.$i.'</td>
			<td width="7%" align="center">'.obtenerFechaMesCorto($row->fecha).'</td>
			<td width="10%" align="center">';
				echo $proveedor!=null?$proveedor->empresa:'';
			echo'</td>
			<td width="10%" align="center">';
			$producto	=$this->configuracion->obtenerProducto($row->idProducto);
			echo $producto!=null?$producto->nombre:'';
			echo'</td>
			<td width="10%">'.$row->producto.'</td>
			<td width="8%" align="center">';
			$departamento	=$this->configuracion->obtenerDepartamento($row->idDepartamento);
			echo $departamento!=null?$departamento->nombre:'';
			echo'</td>
			<td width="10%" align="center">';
			$gasto	=$this->configuracion->obtenerGasto($row->idGasto);
			echo $gasto!=null?$gasto->nombre:'';
			echo'</td>
			<td width="10%" align="center">'.$row->factura.'</td>
			
			<td width="8%" align="center">'.$row->remision.'</td>
			
			<td width="8%" align="right">$'.number_format($row->subTotal,2).'</td>
			<td width="7%" align="right">$'.number_format($row->ivaTotal,2).'</td>
			<td width="9%" align="right">$'.number_format($row->pago,2).'</td>
		</tr>';
		
		$i++;
	}
	
	echo '</table>';
}
else
{
	echo '<div class="Error_validar">Sin registro de egresos</div>';
}
?>