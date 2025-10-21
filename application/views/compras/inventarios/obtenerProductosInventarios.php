<?php
if($productos==null)
{
	echo'
	<div class="Error_validar" style="margin-top:2px; width:95%; float:left margin-bottom: 5px;">
		No hay registros de productos para este proveedor.
	</div>';
	
	return;
}

echo'
<div style="width:90%; margin-bottom:1%;">
	<ul id="pagination-digg" class="ajax-pag">'.$this->pagination->create_links().'</ul>
</div>';

echo'
<table class="admintable" style="width:100%">
	<tr>
		<th width="3%" align="right">#</th>
		<th>Mobiliario/equipo</th>
		<th>Unidad</th>
		<th align="left" width="35%">Proveedor</th>
		<th width="11%">Precio</th>
		<th width="8%">Stock</th>
		<th width="8%">Cantidad</th>
	</tr>';

 $i=1;
 
 #<th style="width:100px">Agregar</th>
 #<td align="center"><input type="checkbox" value="'.$row->id.'" 
 #id="agregar'.$i.'" onclick="agregarProductoKit('.$i.')" /></td>
 
 foreach($productos as $row)
 {
	$estilo			= $i%2>0?'class="sinSombra"':'class="sombreado"';
	$onclick		= 'onclick="agregarProductoKit('.$i.',\'si\')"';
	
	 echo'
	<tr '.$estilo.'>
		<td '.$onclick.' align="right">'.$i.'</td>
		<td '.$onclick.'>'.$row->nombre.' </td>
		<td '.$onclick.'>'.$row->unidad.' </td>
		<td>'.$row->empresa.' 
			<div style="text-align:center; width: 100px">
				<img title="Agregar proveedor" src="'.base_url().'img/proveedores.png" width="18" onclick="accesoAgregarProveedorCompraInventario('.$row->idInventario.')" style="cursor:pointer" />
				<br />
				<a>Asignar proveedor</a>
			</div>
		</td>
		<td align="center" valign="middle">
			$<input type="text" class="cajas" style="width:70px"  id="precio'.$i.'" value="'.round($row->costo,decimales).'" onkeypress="return soloDecimales(event)" onchange="editarCostoInventario('.$i.','.$row->idInventario.','.$row->idProveedor.')" maxlength="15"/> 
			
			<img title="Actualizar precio" src="'.base_url().'img/guardar.png" width="18" onclick="editarCostoInventario('.$i.','.$row->idInventario.','.$row->idProveedor.')" style="cursor:pointer; display: none" />
		</td>
		<td '.$onclick.' align="center">'.number_format($row->cantidad,2).' </td>
		<td align="center">
			<input onchange="agregarProductoKit('.$i.',\'no\')" type="text" value="0" style="width:50px" class="cajas" id="cantidad'.$i.'" onkeypress="return soloDecimales(event)" maxlength="15" />
			<input type="hidden" value="'.$row->idInventario.'" id="agregar'.$i.'" />
			
			<input type="hidden"  id="descripcion'.$i.'" value="'.$row->nombre.'"/>
			<input type="hidden"  id="txtProveedor'.$i.'" value="'.$row->idProveedor.'"/>
			<input type="hidden"  id="txtNombreProveedor'.$i.'" value="'.$row->empresa.'"/>
		</td>
	</tr>';
	 
	 $i++;
 }
 
 #&nbsp;
#<img title="Actualizar precio" src="'.base_url().'img/guardar.png" width="22" 
#id="imgGuardar" onclick="precioMaterial('.$i.','.$row->idMaterial.','.$row->idProveedor.')" 
#style="cursor:pointer" />
 
 echo '</table>';
 
 $i=1;