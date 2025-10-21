<?php
echo
'<form id="frmEditarInventario" name="frmEditarInventario" method="post" action="'.base_url().'inventarioProductos/editarInventario">
	<table class="admintable" width="100%">
		<tr>
			<td class="key">Nombre:</td>
			<td>
				<input type="text" class="cajas" style="width:90%" id="txtNombre" name="txtNombre" value="'.$inventario->nombre.'" />
				
				<input type="hidden" id="txtIdInventario" name="txtIdInventario" value="'.$inventario->idInventario.'" />
			</td>
		</tr>
		
		<tr>
			<td class="key">Código:</td>
			<td>
				<input type="text" name="txtCodigo" id="txtCodigo" class="cajas" style="width:200px" value="'.$inventario->codigoInterno.'" /> 
			</td>
		</tr>
		
		<tr>
			<td class="key">Unidad:</td>
			<td>
				<input type="text" name="txtUnidad" id="txtUnidad" class="cajas" style="width:200px" value="'.$inventario->unidad.'" /> 
			</td>
		</tr>
		
	</table>
</form>';

echo '
<table class="admintable" width="100%">
	<tr>
		<th colspan="3">Proveedores asociados</th>
	</tr>
	<tr>
		<th width="3%">#</th>
		<th width="70%">Proveedor</th>
		<th>Costo</th>
	</tr>';

$i=1;
foreach($inventarios as $row)
{
	$estilo=$i%2>0?'class="sinSombra"':'class="sombreado"';
	echo '
	<tr '.$estilo.'>
		<td>'.$i.'</td>
		<td>'.$row->empresa.'</td>
		<td align="right">$'.number_format($row->costo,2).'</td>
	</tr>';
	
	$i++;
}

echo '</table>';