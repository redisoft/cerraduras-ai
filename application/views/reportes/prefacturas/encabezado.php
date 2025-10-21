
<htmlpageheader name="myHTMLHeader1">
<?php
echo'
<div align="center">
	<table width="80%">
		<tr>
			<td>
				<img src="'.base_url().'img/logos/'.$this->session->userdata('logotipo').'" width="100" height="60" />
			</td>
			<td style="font-size:18px" align="center">REPORTE DE REMISIÓN/PREFACTURA</td>
		</tr>
	</table>
</div>

<div align="left" style="font-size:12px">Fecha: '.obtenerNombreFecha(date('Y-m-d')).'</div>';

echo'
<table class="admintable" width="100%">
	<tr>
		<th width="4%">#</th>
		<th width="15%">Fecha prefactura</th>
		<th width="15%">Prefactura</th>
		<th width="15%">Fecha remisión</th>
		<th width="15%">Remisión</th>
		<th width="26%">Cliente</th>
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
			<td style="font-size:18px" align="center">REPORTE DE REMISIÓN/PREFACTURA</td>
		</tr>
	</table>
</div>

<div align="left" style="font-size:12px">Fecha: '.obtenerNombreFecha(date('Y-m-d')).'</div>';

echo'
<table class="admintable" width="100%">
	<tr>
		<th width="4%">#</th>
		<th width="15%">Fecha prefactura</th>
		<th width="15%">Prefactura</th>
		<th width="15%">Fecha remisión</th>
		<th width="15%">Remisión</th>
		<th width="26%">Cliente</th>
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