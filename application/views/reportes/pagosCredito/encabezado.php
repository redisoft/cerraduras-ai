
<htmlpageheader name="myHTMLHeader1">
<?php

echo'
<div align="center">
	<table width="80%">
		<tr>
			<td>
				<img src="'.base_url().'img/logos/'.$this->session->userdata('logotipo').'" style="max-width: 120px; max-height: 60px" />
			</td>
			<td style="font-size:18px" align="center">REPORTE DE PAGO CRÉDITOS</td>
		</tr>
	</table>
</div>

<div align="left" style="font-size:12px">Periodo: '.obtenerFechaMesCorto($inicio).' a '.obtenerFechaMesCorto($fin).'</div>';

echo'
<table class="admintable" width="100%">
	<tr>
		<th align="right" colspan="10">Total $ '.number_format($totales,2).'</th>
	</tr>
	<tr>
		<th width="3%">#</th>
		<th width="10%">Fecha</th>
		<th width="26%">Cliente</th>
		<th width="10%">Nota</th>
		<th width="10%">Forma de pago</th>
		<th width="10%">Banco</th>
		<th width="10%">Cuenta</th>
		<th width="7%">Factura</th>
		<th width="7%">Total venta</th>
		<th width="7%">Pago</th>
	</tr>
</table>';
?>

</htmlpageheader>

<htmlpageheader name="myHTMLHeader1Even">
<?php

echo'
<div align="center">
	<table width="80%">
		<tr>
			<td>
				<img src="'.base_url().'img/logos/'.$this->session->userdata('logotipo').'" style="max-width: 120px; max-height: 60px" />
			</td>
			<td style="font-size:18px" align="center">REPORTE DE PAGO CRÉDITOS</td>
		</tr>
	</table>
</div>

<div align="left" style="font-size:12px">Periodo: '.obtenerFechaMesCorto($inicio).' a '.obtenerFechaMesCorto($fin).'</div>';

echo'
<table class="admintable" width="100%">
	<tr>
		<th align="right" colspan="10">Total $ '.number_format($totales,2).'</th>
	</tr>
	<tr>
		<th width="3%">#</th>
		<th width="10%">Fecha</th>
		<th width="26%">Cliente</th>
		<th width="10%">Nota</th>
		<th width="10%">Forma de pago</th>
		<th width="10%">Banco</th>
		<th width="10%">Cuenta</th>
		<th width="7%">Factura</th>
		<th width="7%">Total venta</th>
		<th width="7%">Pago</th>
	</tr>
</table>';
?>

</htmlpageheader>

    
mpdf-->
<!-- set the headers/footers - they will occur from here on in the document -->
<!--mpdf
<sethtmlpageheader name="myHTMLHeader1" page="O" value="on" show-this-page="1" />
<sethtmlpageheader name="myHTMLHeader1Even" page="E" value="on" />
mpdf-->