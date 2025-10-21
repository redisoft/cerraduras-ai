
<htmlpageheader name="myHTMLHeader1">
<?php
echo'
<div align="center">
	<table width="80%">
		<tr>
			<td>
				<img src="'.base_url().'img/logos/'.$this->session->userdata('logotipo').'" width="120" height="60" />
			</td>
			<td style="font-size:18px" align="center">REPORTE DE FACTURACIÓN</td>
		</tr>
	</table>
</div>';


	echo'<div align="left" style="font-size:12px">';
	
	if($mes!='mes')
	{
		echo 'Mes fiscal: '.obtenerNombreMes($mes).' DEL '.$anio;
	}
	else
	{
		echo '&nbsp;';
	}
	echo' </div>';


echo '
<table class="admintable" width="100%">
	<tr>
		<th width="3%">#</th>
		<th width="8%">Fecha</th>
		<th width="11%">Documento</th>
		<th width="24%">Cliente</th>
		
		<th width="15%">Emisor</th>
		
		<th width="13%">Folio y serie</th>
		
		<th width="8%">Facturado</th>
		<th width="8%">Pendiente</th>
		
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
				<img src="'.base_url().'img/logos/'.$this->session->userdata('logotipo').'" width="120" height="60" />
			</td>
			<td style="font-size:18px" align="center">REPORTE DE FACTURACIÓN</td>
		</tr>
	</table>
</div>';


	echo'<div align="left" style="font-size:12px">';
	
	if($mes!='mes')
	{
		echo 'Mes fiscal: '.obtenerNombreMes($mes).' DEL '.$anio;
	}
	else
	{
		echo '&nbsp;';
	}
	echo' </div>';


echo '
<table class="admintable" width="100%">
	<tr>
		<th width="3%">#</th>
		<th width="8%">Fecha</th>
		<th width="11%">Documento</th>
		<th width="24%">Cliente</th>
		
		<th width="15%">Emisor</th>
		
		<th width="13%">Folio y serie</th>
		
		<th width="8%">Facturado</th>
		<th width="8%">Pendiente</th>
		
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