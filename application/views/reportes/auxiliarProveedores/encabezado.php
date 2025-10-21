
<htmlpageheader name="myHTMLHeader1">
<?php
echo'
<div align="center">
	<table width="80%">
		<tr>
			<td>
				<img src="'.base_url().'img/logos/'.$this->session->userdata('logotipo').'" width="60" height="60" />
			</td>
			<td style="font-size:18px" align="center">AUXILIAR DE PROVEEDORES</td>
		</tr>
	</table>
</div>

<div align="left" style="font-size:12px">
'.$proveedor.'
<br />
Periodo: '.$inicio.' a '.$fin.' </div>';

echo'<table class="admintable" width="100%">';

echo'
<tr>
	<th colspan="6" align="right">Total: $ '.number_format($total,2).'</th>
</tr>
<tr>
	<th width="3%">#</th>
	<th width="17%">Fecha</th>
	<th width="35%">Num. Orden Comp.</th>
	<th width="15%">Factura</th>
	<th width="15%">Remisión</th>
	<th width="15%" align="right">Monto</th>
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
			<td style="font-size:18px" align="center">AUXILIAR DE PROVEEDORES</td>
		</tr>
	</table>
</div>

<div align="left" style="font-size:12px">
'.$proveedor.'
<br />
Periodo: '.$inicio.' a '.$fin.' </div>';

echo'<table class="admintable" width="100%">';

echo'
<tr>
	<th colspan="6" align="right">Total: $ '.number_format($total,2).'</th>
</tr>

<tr>
	<th width="3%">#</th>
	<th width="17%">Fecha</th>
	<th width="35%">Num. Orden Comp.</th>
	<th width="15%">Factura</th>
	<th width="15%">Remisión</th>
	<th width="15%" align="right">Monto</th>
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