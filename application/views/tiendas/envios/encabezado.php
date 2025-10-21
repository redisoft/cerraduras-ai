
<htmlpageheader name="myHTMLHeader1">
<?php

echo'
<div align="center">
	<table width="80%">
		<tr>
			<td>
				<img src="'.base_url().'img/logos/'.$this->session->userdata('logotipo').'" width="100" height="60" />
			</td>
			<td style="font-size:18px" align="center">REPORTE DE TRASPASOS</td>
		</tr>
	</table>
</div>

<div align="left" style="font-size:12px">Fecha: '.obtenerNombreFecha($inicio!='fecha'?$inicio:date('Y-m-d')).' al '.obtenerNombreFecha($fin).'</div>';

echo'
<table class="admintable" width="100%">
	<tr>
		<th width="3%">#</th>
		<th width="10%">Fecha</th>
		<th width="10%">Folio</th>
		<th width="5%">Cantidad</th>
		<th width="10%">UPC</th>
		<th width="32%">Producto</th>
		<th width="10%">Línea</th>
		<th width="10%">Tienda salida</th>
		<th width="10%">Tienda entrada</th>
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
			<td style="font-size:18px" align="center">REPORTE DE TRASPASOS</td>
		</tr>
	</table>
</div>

<div align="left" style="font-size:12px">Fecha: '.obtenerNombreFecha($inicio!='fecha'?$inicio:date('Y-m-d')).' al '.obtenerNombreFecha($fin).'</div>';

echo'
<table class="admintable" width="100%">
	<tr>
		<th width="3%">#</th>
		<th width="10%">Fecha</th>
		<th width="10%">Folio</th>
		<th width="5%">Cantidad</th>
		<th width="10%">UPC</th>
		<th width="32%">Producto</th>
		<th width="10%">Línea</th>
		<th width="10%">Tienda salida</th>
		<th width="10%">Tienda entrada</th>
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