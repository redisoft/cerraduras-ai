
<htmlpageheader name="myHTMLHeader1">
<?php
echo'
<div align="center">
	<table width="80%">
		<tr>
			<td>
				<img src="'.base_url().'img/logos/'.$this->session->userdata('logotipo').'" width="60" height="60" />
			</td>
			<td style="font-size:18px" align="center">Existencia y valor del mobiliario</td>
		</tr>
	</table>
</div>';


	echo'<div align="left" style="font-size:12px">';
		echo 'AL '.obtenerNombreFecha(date('Y-m-d')).'<br />
		Valuación: Costo promedio';
	echo' </div>';


echo '
<table class="admintable" width="100%">
	<tr>
		<th colspan="6" align="right">Total: $ '.number_format($total,2).'</th>
	</tr>
	<tr>
		<th width="5%">#</th>
		<th width="30%">Artículo</th>
		<th width="20%">Proveedor</th>

		<th width="15%">Existencia</th>
		<th width="15%">Costo unitario</th>
		<th width="15%">Total</th>
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
				<img src="'.base_url().'img/logos/'.$this->session->userdata('logotipo').'" width="60" height="60" />
			</td>
			<td style="font-size:18px" align="center">Existencia y valor del inventario</td>
		</tr>
	</table>
</div>';


	echo'<div align="left" style="font-size:12px">';
		echo 'AL '.obtenerNombreFecha(date('Y-m-d')).'<br />
		Valuación: Costo promedio';
	echo' </div>';


echo '
<table class="admintable" width="100%">
	<tr>
		<th colspan="6" align="right">Total: $ '.number_format($total,2).'</th>
	</tr>
	<tr>
		<th width="5%">#</th>
		<th width="30%">Artículo</th>
		<th width="20%">Proveedor</th>

		<th width="15%">Existencia</th>
		<th width="15%">Costo unitario</th>
		<th width="15%">Total</th>
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