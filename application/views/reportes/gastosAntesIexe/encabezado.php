
<htmlpageheader name="myHTMLHeader1">
<?php

echo'
<div align="center">
	<table width="80%">
		<tr>
			<td>
				<img src="'.base_url().'img/logos/'.$this->session->userdata('logotipo').'" width="100" height="60" />
			</td>
			<td style="font-size:18px" align="center">REPORTE DE EGRESOS</td>
		</tr>
	</table>
</div>

<div align="left" style="font-size:12px">Periodo: '.obtenerFechaMesCorto($inicio).' a '.obtenerFechaMesCorto($fin).'</div>';

echo'
<table class="admintable" width="100%">
	<tr>
		<th align="right" colspan="12">Total $ '.number_format($sumaGastos,2).'</th>
	</tr>
	<tr>
		<th width="3%">#</th>
		<th width="7%">Fecha</th>
		<th width="10%">Proveedor</th>
		<th width="10%">Concepto</th>
		<th width="10%">Descripción del producto</th>
		<th width="8%">Departamento</th>
		<th width="10%">Tipo</th>
		<th width="10%">Factura</th>
		<th width="8%">Remisión</th>
		
		<th width="8%">Subtotal</th>
		
		<th width="7%">Iva</th>
		<th width="9%">Total</th>
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
			<td style="font-size:18px" align="center">REPORTE DE EGRESOS</td>
		</tr>
	</table>
</div>

<div align="left" style="font-size:12px">Periodo: '.obtenerFechaMesCorto($inicio).' a '.obtenerFechaMesCorto($fin).'</div>';

echo'
<table class="admintable" width="100%">
	<tr>
		<th align="right" colspan="12">Total $ '.number_format($sumaGastos,2).'</th>
	</tr>
	<tr>
		<th width="3%">#</th>
		<th width="7%">Fecha</th>
		<th width="10%">Proveedor</th>
		<th width="10%">Concepto</th>
		<th width="10%">Descripción del producto</th>
		<th width="8%">Departamento</th>
		<th width="10%">Tipo</th>
		<th width="10%">Factura</th>
		<th width="8%">Remisión</th>
		
		<th width="8%">Subtotal</th>
		
		<th width="7%">Iva</th>
		<th width="9%">Total</th>
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