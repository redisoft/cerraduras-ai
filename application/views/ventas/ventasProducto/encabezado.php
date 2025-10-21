
<htmlpageheader name="myHTMLHeader1">
<?php
echo'
<div align="center">
	<table width="80%">
		<tr>
			<td>
				<img src="'.base_url().'img/logos/'.$this->session->userdata('logotipo').'" width="100" height="60" />
			</td>
			<td style="font-size:18px" align="center">VENTAS POR PRODUCTO</td>
		</tr>
	</table>
</div>

<div align="left" style="font-size:12px">Periodo: '.obtenerFechaMesCorto($inicio).' a '.obtenerFechaMesCorto($fin).'</div>';

echo'
<table class="admintable" width="100%">
	<tr>
        <th width="4%" class="encabezadoPrincipal">#</th>
        <th width="18%" class="encabezadoPrincipal">Cliente</th>
        <th width="10%" class="encabezadoPrincipal">Venta</th>
        <th width="10%" class="encabezadoPrincipal">Fecha</th>
        <th width="18%" class="encabezadoPrincipal">Producto</th>
        <th width="10%" class="encabezadoPrincipal">Cantidad</th>
        <th width="10%" class="encabezadoPrincipal">PU</th>
        <th width="10%" class="encabezadoPrincipal">Descuento</th>
        <th width="10%" class="encabezadoPrincipal">Importe</th>
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
			<td style="font-size:18px" align="center">VENTAS POR PRODUCTO</td>
		</tr>
	</table>
</div>

<div align="left" style="font-size:12px">Periodo: '.obtenerFechaMesCorto($inicio).' a '.obtenerFechaMesCorto($fin).'</div>';

echo'
<table class="admintable" width="100%">
	<tr>
        <th width="4%" class="encabezadoPrincipal">#</th>
        <th width="18%" class="encabezadoPrincipal">Cliente</th>
        <th width="10%" class="encabezadoPrincipal">Venta</th>
        <th width="10%" class="encabezadoPrincipal">Fecha</th>
        <th width="18%" class="encabezadoPrincipal">Producto</th>
        <th width="10%" class="encabezadoPrincipal">Cantidad</th>
        <th width="10%" class="encabezadoPrincipal">PU</th>
        <th width="10%" class="encabezadoPrincipal">Descuento</th>
        <th width="10%" class="encabezadoPrincipal">Importe</th>
    </tr>
</table>';?>

</htmlpageheader>

    
mpdf-->
<!-- set the headers/footers - they will occur from here on in the document -->
<!--mpdf
<sethtmlpageheader name="myHTMLHeader1" page="O" value="on" show-this-page="1" />
<sethtmlpageheader name="myHTMLHeader1Even" page="E" value="on" />
mpdf-->