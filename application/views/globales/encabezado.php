
<htmlpageheader name="myHTMLHeader1">
<?php
echo'
<div align="center">
	<table width="80%">
		<tr>
			<td>';
			if(strlen($this->session->userdata('logotipo'))>0 and file_exists('img/logos/'.$this->session->userdata('logotipo')))
			{
				echo'<img src="'.base_url().'img/logos/'.$this->session->userdata('logotipo').'" width="100" height="60" />';
			}
				
			echo'
			</td>
			<td style="font-size:18px" align="center">REPORTE DE CHECADOR</td>
		</tr>
	</table>
</div>

<div align="left" style="font-size:12px">Periodo: '.obtenerFechaMesCorto($inicio).' a '.obtenerFechaMesCorto($fin).'</div>';

echo'
<table class="admintable" width="100%">
	<tr>
		<th width="2%">#</th>
		<th width="7%">Fecha</th>
		<th width="15%">Personal</th>
		<th width="10%">Puesto</th>
		<th width="12%">Departamento</th>
		<th width="8%">Día</th>
		<th width="5%">Hora entrada</th>
		<th width="8%">Hora checado entrada</th>
		<th width="10%">Diferencia minutos entrada</th>
		<th width="5%">Hora salida</th>
		<th width="8%">Hora checado salida</th>
		<th width="10%">Diferencia minutos salida</th>
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
			<td>';
			
			if(strlen($this->session->userdata('logotipo'))>0 and file_exists('img/logos/'.$this->session->userdata('logotipo')))
			{
				echo'<img src="'.base_url().'img/logos/'.$this->session->userdata('logotipo').'" width="100" height="60" />';
			}
				
			echo'
			</td>
			<td style="font-size:18px" align="center">REPORTE DE CHECADOR</td>
		</tr>
	</table>
</div>

<div align="left" style="font-size:12px">Periodo: '.obtenerFechaMesCorto($inicio).' a '.obtenerFechaMesCorto($fin).'</div>';

echo'
<table class="admintable" width="100%">
	<tr>
		<th width="2%">#</th>
		<th width="7%">Fecha</th>
		<th width="15%">Personal</th>
		<th width="10%">Puesto</th>
		<th width="12%">Departamento</th>
		<th width="8%">Día</th>
		<th width="5%">Hora entrada</th>
		<th width="8%">Hora checado entrada</th>
		<th width="10%">Diferencia minutos entrada</th>
		<th width="5%">Hora salida</th>
		<th width="8%">Hora checado salida</th>
		<th width="10%">Diferencia minutos salida</th>
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