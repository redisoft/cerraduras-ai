
<htmlpageheader name="myHTMLHeader1">
<?php

echo'
<div align="center">
	<table width="80%">
		<tr>
			<td>
				<img src="'.base_url().'img/logos/'.$this->session->userdata('logotipo').'" width="100" height="60" />
			</td>
			<td style="font-size:18px" align="center">REPORTE DE INGRESOS</td>
		</tr>
	</table>
</div>

<div align="left" style="font-size:12px">Periodo: '.obtenerFechaMesCorto($inicio).' a '.obtenerFechaMesCorto($fin).'</div>';

echo'
<table class="admintable" width="100%">
	<tr>
		<th align="right" colspan="13">Total $ '.number_format($sumaIngresos,2).'</th>
	</tr>
	<tr>
		<th width="3%">#</th>
		<th width="7%">Fecha</th>
		<th width="10%">'.(sistemaActivo=='IEXE'?'Alumno':'Cliente').'</th>
		<th width="10%">Descripción</th>
		<th width="8%">Forma de pago</th>
		<th width="7%">Folio</th>
		<th width="7%">Banco</th>
		<th width="6%">Cuenta</th>
		<th width="10%">Factura</th>
		<th width="8%">Remisión</th>
		<th width="8%">Subtotal</th>
		<th width="7%">Impuestos</th>
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
			<td style="font-size:18px" align="center">REPORTE DE INGRESOS</td>
		</tr>
	</table>
</div>

<div align="left" style="font-size:12px">Periodo: '.obtenerFechaMesCorto($inicio).' a '.obtenerFechaMesCorto($fin).'</div>';

echo'
<table class="admintable" width="100%">
	<tr>
		<th align="right" colspan="13">Total $ '.number_format($sumaIngresos,2).'</th>
	</tr>
	<tr>
		<th width="3%">#</th>
		<th width="7%">Fecha</th>
		<th width="10%">'.(sistemaActivo=='IEXE'?'Alumno':'Cliente').'</th>
		<th width="10%">Descripción</th>
		<th width="8%">Forma de pago</th>
		<th width="7%">Folio</th>
		<th width="7%">Banco</th>
		<th width="6%">Cuenta</th>
		<th width="10%">Factura</th>
		<th width="8%">Remisión</th>
		<th width="8%">Subtotal</th>
		<th width="7%">Impuestos</th>
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