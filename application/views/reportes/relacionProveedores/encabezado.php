<htmlpageheader name="myHTMLHeader1">
<?php
echo'
<div align="center">
	<table width="80%">
		<tr>
			<td>
				<img src="'.base_url().'img/logos/'.$this->session->userdata('logotipo').'" width="100" height="60" />
			</td>
			<td style="font-size:18px" align="center">Relación proveedores - '.$anio.'</td>
		</tr>
	</table>
</div>';

echo '
<table class="admintable" width="100%">
	<tr>
		<th align="center" colspan="5" class="encabezadoPrincipal" style="border-right:none" >
			'.($emisor!=null?$emisor->nombre:'').'
			'.($emisor!=null?'<br />'.$emisor->rfc:'').'
		</th>
		<th align="right" colspan="2" class="encabezadoPrincipal" ="border-left:none"  >Total $ '.number_format($totales,2).'</th>
	</tr>
	
	<tr>
		<th width="3%">#</th>
		<th width="15%">Emisor</th>
		<th width="40%">Proveedor</th>
		<th width="12%">RFC</th>
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
			<td style="font-size:18px" align="center">Relación proveedores - '.$anio.'</td>
		</tr>
	</table>
</div>';

echo '
<table class="admintable" width="100%">
	<tr>
		<th align="center" colspan="5" class="encabezadoPrincipal" style="border-right:none" >
			'.($emisor!=null?$emisor->nombre:'').'
			'.($emisor!=null?'<br />'.$emisor->rfc:'').'
		</th>
		<th align="right" colspan="2" class="encabezadoPrincipal" ="border-left:none"  >Total $ '.number_format($totales,2).'</th>
	</tr>
	
	<tr>
		<th width="3%">#</th>
		<th width="15%">Emisor</th>
		<th width="40%">Proveedor</th>
		<th width="12%">RFC</th>
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