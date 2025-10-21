<?php
$this->load->view('reportes/envios/inventario/encabezado');

$i=1;

echo '
<table class="admintable" width="100%">';	

if($registros!=null)
{
	foreach($registros as $row)
	{
		echo '
		<tr>
			<td align="right" width="3%">'.$i.'</td>
			<td align="center" width="10%">'.obtenerFechaMesCortoHora($row->fechaCompra).'</td>
			<td align="center" width="10%">'.$row->estacion.$row->folio.'</td>
			<td align="center" width="8%">'.$row->folioTicket.'</td>
			<td align="left" width="14%">'.$row->codigoInterno.'</td>
			<td align="left" width="24%">'.$row->producto.'</td>
			<td align="center" width="8%">'.round($row->cantidadEntregada,2).'</td>
			<td align="center" width="8%">'.round($row->noEntregados,2).'</td>
			<td align="left" width="15%">'.nl2br($row->comentarios).'</td>
		</tr>';

		$i++;
	}
}

echo '</table>';

?>
