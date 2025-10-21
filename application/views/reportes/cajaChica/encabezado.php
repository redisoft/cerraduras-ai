
<htmlpageheader name="myHTMLHeader1">
<?php
echo'
<div align="center">
	<table width="80%" class="admintabla">
		<tr>
			<td>
				<img src="'.base_url().'img/logos/'.$this->session->userdata('logotipo').'" width="60" height="60" />
			</td>
			<td style="font-size:18px" align="center">REPORTE DE CAJA CHICA</td>
		</tr>
	</table>
</div>';

echo '
<table class="admintable" width="100%">
	<tr>
		<th width="2%">#</th>
		<th width="8%">Fecha</th>
		<th width="15%">Concepto</th>
		<th width="10%">Monto</th>
		<th width="10%">Forma de pago</th>
		<th width="10%">Cheque / Trasferencia</th>
		<th width="10%">Nombre</th>
		<th width="10%">Departamento</th>
		<th width="10%">Descripción del producto</th>
		<th width="15%">Tipo</th>
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
				<img src="'.base_url().'img/iexe.png" width="60" height="60" />
			</td>
			<td style="font-size:18px" align="center">IEXE ESCUELA DE POLITICAS PÚBLICAS</td>
		</tr>
	</table>
</div>';

echo '
<table class="admintabla" width="100%">
	<tr>
		<th width="2%">#</th>
		<th width="8%">Fecha</th>
		<th width="15%">Concepto</th>
		<th width="10%">Monto</th>
		<th width="10%">Forma de pago</th>
		<th width="10%">Cheque / Trasferencia</th>
		<th width="10%">Nombre</th>
		<th width="10%">Departamento</th>
		<th width="10%">Descripción del producto</th>
		<th width="15%">Tipo</th>
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