
<htmlpageheader name="myHTMLHeader1">
<?php

echo'
<div align="center">
	<table width="80%">
		<tr>
			<td>
				<img src="'.base_url().'img/logos/'.$this->session->userdata('logotipo').'" width="100" height="60" />
			</td>
			<td style="font-size:18px" align="center">REPORTE DE PAGOS</td>
		</tr>
	</table>
</div>

<div align="left" style="font-size:12px">Periodo: '.obtenerFechaMesCorto($inicio).' a '.obtenerFechaMesCorto($fin).'</div>';

echo'
<table class="admintable" width="100%">
	<tr>
		<th colspan="7" align="right">
			Total $ '.number_format($totalCompras,2).'
		</th>
	</tr>
	<tr>
		<th width="3%">#</th>
		<th width="10%">Fecha</th>
		<th width="20%">Proveedor</th>
		<th width="20%">Descripción</th>
		
		<th width="19%">Fecha de vencimiento</th>
		<th width="19%">Días de vencimiento</th>
		
		<th width="9%">Saldo</th>
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
				<img src="'.base_url().'img/logos/'.$this->session->userdata('logotipo').'" width="100" height="60" />
			</td>
			<td style="font-size:18px" align="center">REPORTE DE COMPRAS</td>
		</tr>
	</table>
</div>

<div align="left" style="font-size:12px">Periodo: '.obtenerFechaMesCorto($inicio).' a '.obtenerFechaMesCorto($fin).'</div>';

echo'
<table class="admintable" width="100%">
	<tr>
		<th colspan="7" align="right">
			Total $ '.number_format($totalCompras,2).'
		</th>
	</tr>
	<tr>
		<th width="3%">#</th>
		<th width="10%">Fecha</th>
		<th width="20%">Proveedor</th>
		<th width="20%">Descripción</th>
		
		<th width="19%">Fecha de vencimiento</th>
		<th width="19%">Días de vencimiento</th>
		
		<th width="9%">Saldo</th>
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