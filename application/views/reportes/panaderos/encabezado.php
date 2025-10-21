
<htmlpageheader name="myHTMLHeader1">
<?php
echo'
<div align="center">
	<table width="80%">
		<tr>
			<td>
				<img src="'.base_url().'img/logos/'.$this->session->userdata('logotipo').'" width="100" height="60" />
			</td>
			<td style="font-size:18px" align="center">Reporte panaderos</td>
		</tr>
	</table>
</div>';


	echo'<div align="left" style="font-size:12px">';
		echo 'Periodo '.obtenerFechaMesCorto($inicio).' al '.obtenerFechaMesCorto($fin);
	echo' </div>';


echo '
<table class="admintable" width="100%">
	<tr>
		<th colspan="15" align="right">Total: $ '.number_format($total,2).'</th>
	</tr>
	<tr>
		<th width="3%">#</th>
		<th width="6%">Fecha</th>
		<th width="10%">Línea</th>
		<th width="10%">Orden</th>
		<th width="8%">Total producido</th>
		<th width="7%">Mano obra</th>
		
		<th width="7%">Maestro</th>
		<th width="6%">Maestro cuota sindical</th>
		<th width="6%">Maestro prima dominical</th>
		
		
		<th width="7%">Oficial</th>
		<th width="6%">Oficial cuota sindical</th>
		<th width="6%">Oficial prima dominical</th>
		
		<th width="6%">Cuota sindical</th>
		<th width="6%">Prima dominical</th>
		<th width="7%">Total</th>
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
			<td style="font-size:18px" align="center">Reporte panaderos</td>
		</tr>
	</table>
</div>';


	echo'<div align="left" style="font-size:12px">';
		echo 'Periodo '.obtenerFechaMesCorto($inicio).' al '.obtenerFechaMesCorto($fin);
	echo' </div>';


echo '
<table class="admintable" width="100%">
	<tr>
		<th colspan="15" align="right">Total: $ '.number_format($total,2).'</th>
	</tr>
	<tr>
		<th width="3%">#</th>
		<th width="6%">Fecha</th>
		<th width="10%">Línea</th>
		<th width="10%">Orden</th>
		<th width="8%">Total producido</th>
		<th width="7%">Mano obra</th>
		
		<th width="7%">Maestro</th>
		<th width="6%">Maestro cuota sindical</th>
		<th width="6%">Maestro prima dominical</th>
		
		
		<th width="7%">Oficial</th>
		<th width="6%">Oficial cuota sindical</th>
		<th width="6%">Oficial prima dominical</th>
		
		<th width="6%">Cuota sindical</th>
		<th width="6%">Prima dominical</th>
		<th width="7%">Total</th>
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