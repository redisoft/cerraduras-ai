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
		$proveedor	= $this->proveedores->obtenerProveedor($row->idProveedor);
		$banco 		= explode('|',$row->banco);
		
		echo '
		<tr '.$estilo.'>
			<td align="right" width="3%">'.$i.'</td>
			<td align="center" width="7%">'.obtenerFechaMesCorto($row->fecha).'</td>
			<td align="center" width="10%">'.($proveedor!=null?$proveedor->empresa:'').'</td>
			<td width="10%">'.(strlen($row->productoCatalogo)>1?$row->productoCatalogo:$row->producto).'</td>
			<td width="8%" align="center">'.$row->forma.'</td>
			<td width="7%" align="center">'.$row->cheque.$row->transferencia.'</td>
			<td width="7%" align="center">'.(strlen($row->banco)>3?$banco[1]:'').'</td>
			<td width="6%" align="center">'.(strlen($row->banco)>3?$banco[0]:'').'</td>
			<td width="10%" align="center">'.($row->esRemision=='0'?$row->remision:'').'</td>
			<td width="8%" align="center">'.($row->esRemision=='1'?$row->remision:'').'</td>
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