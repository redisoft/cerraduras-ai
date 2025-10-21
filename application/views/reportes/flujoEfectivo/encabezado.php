
<htmlpageheader name="myHTMLHeader1">
<?php
echo'
<div align="center">
	<table width="80%">
		<tr>
			<td>
				<img src="'.base_url().'img/logos/'.$this->session->userdata('logotipo').'" width="60" height="60" />
			</td>
			<td style="font-size:18px" align="center">REPORTE DE FLUJO DE EFECTIVO</td>
		</tr>
	</table>
</div>

<div align="left" style="font-size:12px">';
	echo 'Fecha: '.obtenerNombreMes($mes).' DEL '.$anio;
echo' </div>';
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
			<td style="font-size:18px" align="center">REPORTE DE FLUJO DE EFECTIVO</td>
		</tr>
	</table>
</div>

<div align="left" style="font-size:12px">';
	echo 'Periodo: '.$mes.' del '.$anio;
echo' </div>';
?>

 </htmlpageheader>

    
mpdf-->
<!-- set the headers/footers - they will occur from here on in the document -->
<!--mpdf
<sethtmlpageheader name="myHTMLHeader1" page="O" value="on" show-this-page="1" />
<sethtmlpageheader name="myHTMLHeader1Even" page="E" value="on" />
mpdf-->