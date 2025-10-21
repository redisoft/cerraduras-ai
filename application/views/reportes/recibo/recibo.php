<?php

echo'
<div align="center">
	<table width="70%">
		<tr>
			<td>
				<img src="'.base_url().'img/logos/'.$this->session->userdata('logotipo').'" width="100" height="60" />
			</td>
			<td style="font-size:18px" align="center">RECIBO</td>
		</tr>
	</table>
</div>';

echo'<div align="left" style="font-size:12px">Fecha: '.obtenerNombreFecha($egreso->fecha).'</div>';

echo '
<table class="admintable" width="100%">
	<tr>
		<th>Concepto</th>
		<th>Importe</th>
		<th>Firma</th>
	</tr>';

echo '
<tr>
	<td  style="height:50px" align="center" width="50%">'.$egreso->producto.'</td>
	<td align="right" width="20%">$'.number_format($egreso->pago,2).'</td>
	<td width="30%" align="center">
	</td>
</tr>';

echo '</table>';
?>