<htmlpageheader name="myHTMLHeader1">
<?php
echo'
<div align="center">
	<table width="80%">
		<tr>
			<td>
				<img src="'.base_url().'img/logos/'.$this->session->userdata('logotipo').'" width="100" height="60" />
			</td>
			<td style="font-size:18px" align="center">Gastos facturados - '.obtenerFechaMesAnio($fecha).'</td>
		</tr>
	</table>
</div>';

echo '
<table class="admintable" width="100%">
	<tr>
		<th colspan="7" class="encabezadoPrincipal" style="border-right:none;">
			'.($emisor!=null?$emisor->nombre:'').'
			'.($emisor!=null?'<br />'.$emisor->rfc:'').'
		</th>
		<th align="right" class="encabezadoPrincipal" style="border-left:none; border-right:none;" colspan="1">$ '.number_format($totales[1],2).'</th>
		<th align="right" class="encabezadoPrincipal" style="border-left:none" colspan="1">$ '.number_format($totales[0],2).'</th>
	</tr>
	<tr>
		<th width="3%">#</th>
		<th width="8%">Fecha</th>
		<th width="8%">Fecha pago</th>
		<th width="12%">Emisor</th>
		<th width="25%">Proveedor</th>
		<th width="10%">Factura</th>
		<th width="12%">Subtotal</th>
		<th width="10%">Iva</th>
		<th width="12%">Total</th>
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
			<td style="font-size:18px" align="center">Gastos facturados - '.obtenerFechaMesAnio($fecha).'</td>
		</tr>
	</table>
</div>';

echo '
<table class="admintable" width="100%">
	<tr>
		<th colspan="7" class="encabezadoPrincipal" style="border-right:none;">
			'.($emisor!=null?$emisor->nombre:'').'
			'.($emisor!=null?'<br />'.$emisor->rfc:'').'
		</th>
		<th align="right" class="encabezadoPrincipal" style="border-left:none; border-right:none;" colspan="1">$ '.number_format($totales[1],2).'</th>
		<th align="right" class="encabezadoPrincipal" style="border-left:none" colspan="1">$ '.number_format($totales[0],2).'</th>
	</tr>
	<tr>
		<th width="3%">#</th>
		<th width="8%">Fecha</th>
		<th width="8%">Fecha pago</th>
		<th width="12%">Emisor</th>
		<th width="25%">Proveedor</th>
		<th width="10%">Factura</th>
		<th width="12%">Subtotal</th>
		<th width="10%">Iva</th>
		<th width="12%">Total</th>
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