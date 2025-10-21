
<htmlpageheader name="myHTMLHeader1">
<?php
echo'
<div align="center">
	<table width="80%">
		<tr>
			<td>
				<img src="'.base_url().'img/logos/'.$this->session->userdata('logotipo').'" width="120" height="60" />
			</td>
			<td style="font-size:18px" align="center">
				CONTROL DE SALIDA DE MATERIA PRIMA<br />
				FECHA: '.obtenerFechaMesCorto($salida->fechaSalida).'
			</td>
		</tr>
	</table>
</div>

<table class="admintable" width="100%">
	<tr>
		<th width="15%">Código</th>
		<th width="40%">Producto</th>
		<th width="15%">Entrega</th>
		<th width="15%">Devuelto</th>
		<th width="15%">Gastado</th>
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
				<img src="'.base_url().'img/logos/'.$this->session->userdata('logotipo').'" width="120" height="60" />
			</td>
			<td style="font-size:18px" align="center">
				CONTROL DE SALIDA DE MATERIA PRIMA<br />
				FECHA: '.obtenerFechaMesCorto($salida->fechaSalida).'
			</td>
		</tr>
	</table>
</div>

<table class="admintable" width="100%">
	<tr>
		<th width="15%">Código</th>
		<th width="40%">Producto</th>
		<th width="15%">Entrega</th>
		<th width="15%">Devuelto</th>
		<th width="15%">Gastado</th>
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