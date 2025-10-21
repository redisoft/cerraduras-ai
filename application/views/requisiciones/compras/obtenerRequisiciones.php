<form id="frmComprasRequisicion">
<?php
echo '<input type="hidden" class="cajas" id="txtNumeroRequisiciones" 	name="txtNumeroRequisiciones" value="'.count($requisiciones).'" />';

if($requisiciones!=null)
{
	echo '
	<div style="width:90%">
		<ul id="pagination-digg" class="ajax-pagAbiertas">'.$this->pagination->create_links().'</ul>
	 </div>
	<table class="admintable" width="100%">
		<tr>
			<th>#</th>
			<th>Fecha requisición</th>
			<th>Requisicion</th>
			<th>Materia prima</th>
			<th>Unidad</th>
			<th width="20%">Proveedor</th>
			<th>Usuario</th>
			<th>Comentarios</th>
			<th>Precio</th>
			<th>Inventario</th>
			<th>Cantidad</th>
			<th>No autorizar</th>
		</tr>';
	
	$i	= 0;
	foreach($requisiciones as $row)
	{
		$estilo			= $i%2>0?'class="sinSombra"':'class="sombreado"';
		$proveedores	= $this->materiales->obtenerProveedoresMaterial($row->idMaterial);
		

		echo '
		<tr '.$estilo.'>
			<td align="right">'.($i+1).'</td>
			<td align="center">'.obtenerFechaMesCorto($row->fechaRequisicion).'</td>
			<td align="left">'.requisicion.$row->folio.' <input type="checkbox" id="chkAutorizar'.$i.'" name="chkAutorizar'.$i.'" value="'.$row->idDetalle.'" title="Seleccione para autorizar" /></td>
			<td align="left">'.$row->material.'</td>
			<td align="left">'.$row->unidad.'</td>
			<td align="center">
				<select class="cajas" id="selectProveedores'.$i.'" name="selectProveedores'.$i.'" style="width:170px" onchange="sugerirProveedorRequisicion('.$i.')">';
					
					foreach($proveedores as $pro)
					{
						echo'<option '.($row->idProveedor==$pro->idProveedor?'selected="selected"':'').' value="'.$pro->idProveedor.'-'.$pro->costo.'">'.$pro->empresa.'</option>';
					}

				echo'
				</select>
				
				<img src="'.base_url().'img/proveedores.png" width="18" onclick="obtenerProveedoresCompraAsociar('.$row->idMaterial.')" title="Agregar proveedor" />
				
			</td>
			<td align="left">'.$row->usuario.'</td>
			<td align="left">'.nl2br($row->comentarios).'</td>
			<td align="center"><input type="text" class="cajas" id="txtCostoProducto'.$i.'" 	name="txtCostoProducto'.$i.'" 		style="width:70px" value="'.round($row->costo,decimales).'" /></td>
			
			<td align="center">'.round($row->inventario-$row->salidas,decimales).'</td>
			
			<td align="center"><input type="text" class="cajas" id="txtCantidadProducto'.$i.'" 	name="txtCantidadProducto'.$i.'" 	style="width:50px" value="'.round($row->cantidad,decimales).'" /></td>
			
			<input type="hidden" class="cajas" id="txtIdProveedor'.$i.'" 	name="txtIdProveedor'.$i.'" value="'.$row->idProveedor.'" />
			<input type="hidden" class="cajas" id="txtIdMaterial'.$i.'" 	name="txtIdMaterial'.$i.'" value="'.$row->idMaterial.'" />
			<input type="hidden" class="cajas" id="txtIdDetalle'.$i.'" 	name="txtIdDetalle'.$i.'" value="'.$row->idDetalle.'" />
			
			<td align="center"><img id="btnNoAutorizar'.$i.'" src="'.base_url().'img/cancelar.png" width="20" height="20" title="No autorizar" onclick="formularioNoAutorizar('.$row->idRequisicion.');" style="cursor:pointer;"/></td>
			
		</tr>';
		
		$i++;
	}
	
	echo '</table>
	<div style="width:90%">
		<ul id="pagination-digg" class="ajax-pagAbiertas">'.$this->pagination->create_links().'</ul>
	 </div>';
}
else
{
	echo '<div class="Error_validar">Sin registro de requisiciones</div>';
}
?>
</form>