
<htmlpageheader name="myHTMLHeader1">
<?php
echo'
<div align="center">
	<table width="80%">
		<tr>
			<td>
				<img src="'.base_url().'img/logos/'.$this->session->userdata('logotipo').'" style="max-width: 100px; max-height: 60px" />
			</td>
			<td style="font-size:18px" align="center">Reporte de precio 1</td>
		</tr>
	</table>
</div>

<div align="left" style="font-size:12px">Del '.obtenerNombreFecha($inicio).' al '.obtenerNombreFecha($fin).'</div>

<table class="admintable" width="100%">
	<tr>
		<th width="3%">#</th>
		<th width="7%">Fecha</th>
		<th width="15%">Cliente</th>
		<th width="10%">Venta</th>
		<th width="7%">Estación</th>
		<th width="20%">Producto</th>
		<th width="10%">Agente</th>
		<th width="7%">Forma de pago</th>
		<th width="7%">Subtotal</th>
		<th width="7%">Impuestos</th>
		<th width="7%">Total</th>
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
				<img src="'.base_url().'img/logos/'.$this->session->userdata('logotipo').'" style="max-width: 100px; max-height: 60px" />
			</td>
			<td style="font-size:18px" align="center">Reporte de precio 1</td>
		</tr>
	</table>
</div>

<div align="left" style="font-size:12px">Del '.obtenerNombreFecha($inicio).' al '.obtenerNombreFecha($fin).'</div>

<table class="admintable" width="100%">
	<tr>
		<th width="3%">#</th>
		<th width="7%">Fecha</th>
		<th width="15%">Cliente</th>
		<th width="10%">Venta</th>
		<th width="7%">Estación</th>
		<th width="20%">Producto</th>
		<th width="10%">Agente</th>
		<th width="7%">Forma de pago</th>
		<th width="7%">Subtotal</th>
		<th width="7%">Impuestos</th>
		<th width="7%">Total</th>
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
