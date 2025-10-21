
<htmlpageheader name="myHTMLHeader1">
<?php

echo'
<div align="center">
	<table width="80%">
		<tr>
			<td>
				<img src="'.base_url().'img/logos/'.$this->session->userdata('logotipo').'" style="max-width: 120px; max-height: 60px" />
			</td>
			<td style="font-size:18px" align="center">REPORTE DE CAJA</td>
		</tr>
	</table>
</div>

<div align="left" style="font-size:12px">Fecha: '.obtenerFechaMesCorto($fecha).'</div>';

echo'
<table class="admintable" width="100%">
	<tr>
		<th width="4%">#</th>
		<th width="36%">Ticket</th>
		<th width="30%">Importe</th>
		<th width="30%">Hora</th>
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
				<img src="'.base_url().'img/logos/'.$this->session->userdata('logotipo').'" style="max-width: 120px; max-height: 60px" />
			</td>
			<td style="font-size:18px" align="center">REPORTE DE CAJA</td>
		</tr>
	</table>
</div>

<div align="left" style="font-size:12px">Fecha: '.obtenerFechaMesCorto($fecha).'</div>';

echo'
<table class="admintable" width="100%">
	<tr>
		<th width="4%">#</th>
		<th width="36%">Ticket</th>
		<th width="30%">Importe</th>
		<th width="30%">Hora</th>
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