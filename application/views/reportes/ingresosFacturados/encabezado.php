<htmlpageheader name="myHTMLHeader1">
<?php
echo'
<div align="center">
	<table width="80%">
		<tr>
			<td>
				<img src="'.base_url().'img/logos/'.$this->session->userdata('logotipo').'" width="100" height="60" />
			</td>
			<td style="font-size:18px" align="center">INGRESOS FACTURADOS</td>
		</tr>
	</table>
</div>

<div align="left" style="font-size:12px">';
	echo 'Mes: '.obtenerFechaMesAnio($fecha);
echo' </div>';

echo '
<table class="admintable" width="100%">
	<tr>
		<th width="80%" colspan="6" class="encabezadoPrincipal" style="border-right:none;">
			
			'.($emisor!=null?$emisor->nombre:'').'
			'.($cuenta!=null?'<br />'.$cuenta->banco.': '.$cuenta->cuenta.'':'').'
			'.($emisor!=null?'<br />'.$emisor->rfc:'').'
		</th>
		
		<th width="20%" align="right" colspan="2" class="encabezadoPrincipal" style="border-left:none">Total $ '.number_format($totales,2).'</th>
	</tr>
	
	<tr>
		<th width="5%">#</th>
		<th width="10%">Fecha</th>
		<th width="10%">Fecha pago</th>
		<th width="35%">Cliente</th>
		<th width="10%">Factura</th>
		<th width="10%">Subtotal</th>
		<th width="10%">Iva</th>
		<th width="10%">Total</th>
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
			<td style="font-size:18px" align="center">INGRESOS FACTURADOS</td>
		</tr>
	</table>
</div>

<div align="left" style="font-size:12px">';
	echo 'Mes: '.obtenerFechaMesAnio($fecha);
echo' </div>';

echo '
<table class="admintable" width="100%">
	<tr>
		<th width="80%" colspan="6" class="encabezadoPrincipal" style="border-right:none;">
			
			'.($emisor!=null?$emisor->nombre:'').'
			'.($cuenta!=null?'<br />'.$cuenta->banco.': '.$cuenta->cuenta.'':'').'
			'.($emisor!=null?'<br />'.$emisor->rfc:'').'
		</th>
		
		<th width="20%" colspan="2" align="right" class="encabezadoPrincipal" style="border-left:none">Total $ '.number_format($totales,2).'</th>
	</tr>
	
	<tr>
		<th width="5%">#</th>
		<th width="10%">Fecha</th>
		<th width="10%">Fecha pago</th>
		<th width="35%">Cliente</th>
		<th width="10%">Factura</th>
		<th width="10%">Subtotal</th>
		<th width="10%">Iva</th>
		<th width="10%">Total</th>
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