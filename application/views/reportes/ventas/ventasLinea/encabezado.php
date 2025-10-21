
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
			<td style="font-size:18px" align="center">Ventas por línea de producto</td>
		</tr>
	</table>
</div>

<div align="left" style="font-size:12px">Fecha: '.obtenerNombreFecha($inicio!='fecha'?$inicio:date('Y-m-d')).' al '.obtenerNombreFecha($fin).'</div>';

echo'
<table class="admintable" width="100%">
	<tr>
		<th width="3%">#</th>
		<th width="50%">Departamento</th>
		<th width="22%">Cantidad</th>
		<th width="25%">Monto</th>
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
			<td style="font-size:18px" align="center">Ventas por línea de producto</td>
		</tr>
	</table>
</div>

<div align="left" style="font-size:12px">Fecha: '.obtenerNombreFecha($inicio!='fecha'?$inicio:date('Y-m-d')).' al '.obtenerNombreFecha($fin).'</div>';

echo'
<table class="admintable" width="100%">
	<tr>
		<th width="3%">#</th>
		<th width="50%">Departamento</th>
		<th width="22%">Cantidad</th>
		<th width="25%">Monto</th>
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