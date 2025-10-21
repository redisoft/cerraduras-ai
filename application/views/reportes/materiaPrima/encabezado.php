
<htmlpageheader name="myHTMLHeader1">
<?php
echo'
<div align="center">
	<table width="80%">
		<tr>
			<td>
				<img src="'.base_url().'img/logos/'.$this->session->userdata('logotipo').'" width="100" height="60" />
			</td>
			<td style="font-size:18px" align="center">Reporte de inventario de materia prima</td>
		</tr>
	</table>
</div>';


	echo'<div align="left" style="font-size:12px">';
		echo 'AL '.obtenerNombreFecha(date('Y-m-d')).'<br />';
	echo' </div>';


echo '
<table class="admintable" width="100%">
	<tr>
		<th colspan="8" align="right">Total: $ '.number_format($total,2).'</th>
	</tr>
	<tr>
		<th width="5%">#</th>
		<th width="13%">Código</th>
		<th width="26%">Materia prima</th>
		<th width="13%">Proveedor</th>
		<th width="13%">Unidad</th>
		<th width="10%">Existencia</th>
		<th width="10%">Costo unitario</th>
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
			<td style="font-size:18px" align="center">Reporte de inventario de materia prima</td>
		</tr>
	</table>
</div>';


	echo'<div align="left" style="font-size:12px">';
		echo 'AL '.obtenerNombreFecha(date('Y-m-d')).'<br />';
	echo' </div>';


echo '
<table class="admintable" width="100%">
	<tr>
		<th colspan="8" align="right">Total: $ '.number_format($total,2).'</th>
	</tr>
	<tr>
		<th width="5%">#</th>
		<th width="13%">Código</th>
		<th width="26%">Materia prima</th>
		<th width="13%">Proveedor</th>
		<th width="13%">Unidad</th>
		<th width="10%">Existencia</th>
		<th width="10%">Costo unitario</th>
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