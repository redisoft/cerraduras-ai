
<htmlpageheader name="myHTMLHeader1">
<?php

echo'
<div align="center">
	<table width="80%">
		<tr>
			<td>';
				if(strlen($this->session->userdata('logotipo')) and file_exists('img/logos/'.$this->session->userdata('logotipo')))	
				{
					echo '<img src="'.base_url().'img/logos/'.$this->session->userdata('logotipo').'" style="max-width: 100px; max-height: 80px" />';
				}
			echo'
			</td> 
			<td style="font-size:18px" align="center">REPORTE DE ENVÍOS</td>
		</tr>
	</table>
</div>

<div align="left" style="font-size:12px">Fecha: '.obtenerNombreFecha(date('Y-m-d')).'</div>';

echo'
<table class="admintable" width="100%">
	<tr>
		<th colspan="11" align="right">Total $ '.number_format($totalCobranza,2).'</th>
	</tr>
	<tr>
		<th width="4%">#</th>
		<th width="9%">Fecha venta</th>
		<th width="9%">Fecha entrega</th>
		<th width="27%">Cliente</th>
		<th width="8%">Ruta</th>
		<th width="9%">Teléfono</th>
		<th width="9%">Nota</th>
		<th width="5%">Folio</th>
		<th width="5%">Factura</th>
		<th width="8%">Importe</th>
		<th width="7%">Saldo</th>
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
			<td>';
				if(strlen($this->session->userdata('logotipo')) and file_exists('img/logos/'.$this->session->userdata('logotipo')))	
				{
					echo '<img src="'.base_url().'img/logos/'.$this->session->userdata('logotipo').'" style="max-width: 100px; max-height: 80px" />';
				}
			
	echo'
			</td> 
			<td style="font-size:18px" align="center">REPORTE DE ENVÍOS</td>
		</tr>
	</table>
</div>

<div align="left" style="font-size:12px">Fecha: '.obtenerNombreFecha(date('Y-m-d')).'</div>';

echo'
<table class="admintable" width="100%">
	<tr>
		<th colspan="11" align="right">Total $ '.number_format($totalCobranza,2).'</th>
	</tr>
	<tr>
		<th width="4%">#</th>
		<th width="9%">Fecha venta</th>
		<th width="9%">Fecha entrega</th>
		<th width="27%">Cliente</th>
		<th width="8%">Ruta</th>
		<th width="9%">Teléfono</th>
		<th width="9%">Nota</th>
		<th width="5%">Folio</th>
		<th width="5%">Factura</th>
		<th width="8%">Importe</th>
		<th width="7%">Saldo</th>
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
