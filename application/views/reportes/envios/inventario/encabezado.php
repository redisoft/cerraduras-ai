<htmlpageheader name="myHTMLHeader1">
<?php
echo'
<div align="center">
	<table width="60%">
		<tr>
			<td>
				<img src="'.base_url().'img/logos/'.$this->session->userdata('logotipo').'" style="max-width: 100px; max-height: 60px"/>
			</td>
			<td style="font-size:18px" align="center">Inventario entregas</td>
		</tr>
	</table>
</div>

<table class="admintable" width="100%" >
	<tr>
		<th width="3%" align="right">#</th>
		<th width="10%">Fecha</th>
		<th width="10%">Nota</th>
		<th width="8%">Folio</th>
		<th width="14%">Código interno</th>
		<th width="24%">Producto</th>
		<th width="8%">Entregado</th>
		<th width="8%">No entregado</th>
		<th width="15%">Comentarios</th>
	</tr>
</table>';

?>
</htmlpageheader>

<htmlpageheader name="myHTMLHeader1Even">
<?php
echo'
<div align="center">
	<table width="60%">
		<tr>
			<td>
				<img src="'.base_url().'img/logos/'.$this->session->userdata('logotipo').'" style="max-width: 100px; max-height: 60px"/>
			</td>
			<td style="font-size:18px" align="center">Inventario entregas</td>
		</tr>
	</table>
</div>

<table class="admintable" width="100%" >
	<tr>
		<th width="3%" align="right">#</th>
		<th width="10%">Fecha</th>
		<th width="10%">Nota</th>
		<th width="8%">Folio</th>
		<th width="14%">Código interno</th>
		<th width="24%">Producto</th>
		<th width="8%">Entregado</th>
		<th width="8%">No entregado</th>
		<th width="15%">Comentarios</th>
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
