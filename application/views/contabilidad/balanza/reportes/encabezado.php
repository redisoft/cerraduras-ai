
<htmlpageheader name="myHTMLHeader1">
<?php
echo'
<div align="center">
	<table width="80%">
		<tr>
			<td>
				<img src="'.base_url().'img/logos/'.$this->session->userdata('logotipo').'" width="100" height="60" />
			</td>
			<td style="font-size:18px" align="center">Balanza de comprobación</td>
		</tr>
	</table>
</div>';


echo'<div align="left" style="font-size:12px">';
	echo 'Periodo '.obtenerFechaMesCorto($inicio).' al '.obtenerFechaMesCorto($fin);
echo' </div>';


echo '
<table class="admintable" width="100%">
	<tr>
		<th width="20%">Cuenta</th>
		<th width="20%">Descripción</th>
		<th width="20%">Saldo inicial</th>
		<th width="10%">Cargos</th>
		<th width="10%">Abonos</th>
		<th width="20%">Saldo final</th>
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
			<td style="font-size:18px" align="center">Balanza de comprobación</td>
		</tr>
	</table>
</div>';


echo'<div align="left" style="font-size:12px">';
	echo 'Periodo '.obtenerFechaMesCorto($inicio).' al '.obtenerFechaMesCorto($fin);
echo' </div>';


echo '
<table class="admintable" width="100%">
	<tr>
		<th width="20%">Cuenta</th>
		<th width="20%">Descripción</th>
		<th width="20%">Saldo inicial</th>
		<th width="10%">Cargos</th>
		<th width="10%">Abonos</th>
		<th width="20%">Saldo final</th>
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