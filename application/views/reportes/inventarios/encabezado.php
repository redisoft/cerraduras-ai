
<htmlpageheader name="myHTMLHeader1">
<?php
echo'
<div align="center">
	<table width="80%">
		<tr>
			<td>';

			if(strlen($configuracion->logotipo)>2 and file_exists('img/logos/'.$configuracion->id.'_'.$configuracion->logotipo))
			{
				echo '<img src="'.base_url().'img/logos/'.$configuracion->id.'_'.$configuracion->logotipo.'" style="max-width: 150px; max-height: 100px" />';
			}
				
			echo'
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
		<th colspan="11" align="right">Total: $ '.number_format($total,2).'</th>
	</tr>
	<tr>
		<th width="5%">#</th>
		<th width="13%">Código</th>
		<th width="20%">Artículo</th>
		<th width="10%">Línea</th>
		<th width="10%">Unidad</th>
		<th width="7%">Existencia</th>
		<th width="7%">Costo unitario</th>
		<th width="7%">Total</th>
		<th width="7%">Precio 1</th>
		<th width="7%">Precio venta</th>
		<th width="7%">Precio mayoreo</th>
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
		<th colspan="1" align="right">Total: $ '.number_format($total,2).'</th>
	</tr>
	<tr>
		<th width="5%">#</th>
		<th width="13%">Código</th>
		<th width="20%">Artículo</th>
		<th width="10%">Línea</th>
		<th width="10%">Unidad</th>
		<th width="7%">Existencia</th>
		<th width="7%">Costo unitario</th>
		<th width="7%">Total</th>
		<th width="7%">Precio 1</th>
		<th width="7%">Precio venta</th>
		<th width="7%">Precio mayoreo</th>
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
