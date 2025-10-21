
<htmlpageheader name="myHTMLHeader1">
<?php

echo'
<div align="center">
	<table width="80%">
		<tr>
			<td>
				<img src="'.base_url().'img/logos/'.$this->session->userdata('logotipo').'" width="100" height="60" />
			</td>
			<td style="font-size:18px" align="center">REPORTE DE VENTAS</td>
		</tr>
	</table>
</div>

<div align="left" style="font-size:12px">Fecha: '.obtenerNombreFecha($inicio!='fecha'?$inicio:date('Y-m-d')).' al '.obtenerNombreFecha($fin).'</div>';

echo'
<table class="admintable" width="100%">
	<tr>
		<th colspan="13" class="encabezadoPrincipal" align="right">Total $ '.number_format($total,2).'</th>
	</tr>
	<tr>
		<th width="3%">#</th>
		<th width="6%">Fecha</th>
		<th width="15%">Cliente</th>
		<th width="10%">Venta</th>
		<th width="10%">'.$this->session->userdata('identificador').'</th>
		<th width="10%">Agente de ventas</th>
		<th width="8%">CRM</th>
		<th width="6%">Subtotal</th>
		<th width="6%">Descuento</th>
		<th width="8%">Impuesto</th>
		<th width="6%">Total</th>
		<th width="6%">Abono</th>
		<th width="6%">Saldo</th>
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
			<td style="font-size:18px" align="center">REPORTE DE VENTAS</td>
		</tr>
	</table>
</div>

<div align="left" style="font-size:12px">Fecha: '.obtenerNombreFecha($inicio!='fecha'?$inicio:date('Y-m-d')).' al '.obtenerNombreFecha($fin).'</div>';

echo'
<table class="admintable" width="100%">
	<tr>
		<th colspan="13" class="encabezadoPrincipal" align="right">Total $ '.number_format($total,2).'</th>
	</tr>
	<tr>
		<th width="3%">#</th>
		<th width="6%">Fecha</th>
		<th width="15%">Cliente</th>
		<th width="10%">Venta</th>
		<th width="10%">'.$this->session->userdata('identificador').'</th>
		<th width="10%">Agente de ventas</th>
		<th width="8%">CRM</th>
		<th width="6%">Subtotal</th>
		<th width="6%">Descuento</th>
		<th width="8%">Impuesto</th>
		<th width="6%">Total</th>
		<th width="6%">Abono</th>
		<th width="6%">Saldo</th>
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