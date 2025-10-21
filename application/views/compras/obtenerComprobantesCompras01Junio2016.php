<?php
echo'
<script src="'.base_url().'js/ficherosCompras.js"></script>
<table class="admintable" width="100%">
	<tr>
		<th colspan="2">Cargar comprobante</th>
	</tr>
	<tr>
		<td class="key">Comprobante:</td>
		<td>
			<div class="custom-input-file" title="Subir comprobante" onclick="seleccionarFichero(1,'.$idCompra.')">
				<input class="input-file" type="file" id="archivo1"/>
				
				<img src="'.base_url().'img/subir.png" width="34"   title="Subir comprobante" />
			</div>
		</td>
	</tr>
</table>';
	
if($ficheros!=null)
{
	$i=1;
	
	echo'
	<table class="admintable" width="100%">
		<tr>
			<th colspan="5">Lista de comprobantes</th>
		</tr>
		<tr>
			<th>#</th>
			<th>Fecha</th>
			<th width="45%">Nombre</th>
			<th>Tamaño</th>
			<th>Acciones</th>
		</tr>';
	
	foreach($ficheros as $row)
	{
		$estilo	=$i%2>0?'class="sinSombra"':'class="sombreado"';
		
		echo '
		<tr '.$estilo.'>
			<td align="right">'.$i.'</td>
			<td align="center">'.$row->fecha.'</td>
			<td>
				<a title="Descargar '.$row->nombre.'" href="'.base_url().'compras/descargarFicheroCompra/'.$row->idComprobante.'">'.$row->nombre.'</a>
			</td>
			<td align="center">'.number_format($row->tamano/1024,1).' KB</td>
			<td align="center">
				<img onclick="accesoBorrarComprobanteCompra('.$row->idComprobante.','.$idCompra.')" src="'.base_url().'img/borrar.png" width="22" title="Borrar comprobante" /><br />
				<a>Borrar</a>
			</td>
		</tr>';	
		
		$i++;
	}
	
	echo '</table>';
}
else
{
	echo '<div class="Error_validar">Sin registro de comprobantes</div>';
}
?>