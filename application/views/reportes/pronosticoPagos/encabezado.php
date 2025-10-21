
<htmlpageheader name="myHTMLHeader1">
<?php
echo'
<div align="center">
	<table width="80%">
		<tr>
			<td>
				<img src="'.base_url().'img/logos/'.$this->session->userdata('logotipo').'" width="60" height="60" />
			</td>
			<td style="font-size:18px" align="center">PRONÓSTICO DE PAGOS</td>
		</tr>
	</table>
</div>

<div align="left" style="font-size:12px">
Fechas: '.$fechaInicio.' a '.$fechaFin.' </div>

<table class="admintable" width="100%">
<tr>
	<th colspan="2">Proveeedores</th>
	<th colspan="4">Desglose de saldos por pagar en dias</th>
</tr>
<tr>
	<th width="35%">Compras</th>
	<th width="13%">Saldo</th>
	<th width="13%">1-7</th>
	<th width="13%">8-14</th>
	<th width="13%">15-21</th>
	<th width="13%">22 o más</th>
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
			<td style="font-size:18px" align="center">PRONÓSTICO DE PAGOS</td>
		</tr>
	</table>
</div>

<div align="left" style="font-size:12px">
Fechas: '.$fechaInicio.' a '.$fechaFin.' </div>

<table class="admintable" width="100%">
<tr>
	<th colspan="2">Proveeedores</th>
	<th colspan="4">Desglose de saldos por pagar en dias</th>
</tr>
<tr>
	<th width="35%">Compras</th>
	<th width="13%">Saldo</th>
	<th width="13%">1-7</th>
	<th width="13%">8-14</th>
	<th width="13%">15-21</th>
	<th width="13%">22 o más</th>
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