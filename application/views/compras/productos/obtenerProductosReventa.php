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
		<th align="left">Código</th>
		<th align="left">Producto</th>
		<th align="left">Unidad</th>
		<th align="left" width="35%">Proveedor</th>
		<th width="11%">Precio</th>
		<th width="10%">Stock</th>
		<th width="8%">Cantidad</th>
	</tr>';
	 
$i=1;
foreach($productos as $row)
{
	$precio	= $row->costo;
	
	if($this->input->post('pagina')=='0')
	{
		$precio	= $row->costo;
	}
	
	$estilo		= $i%2>0?'class="sinSombra"':'class="sombreado"';
	$onclick	= 'onclick="cargarProductoCompra('.$i.',\'si\')"';
	$stockMaximo= 0;
	
	echo'
	<tr '.$estilo.'>
		<td '.$onclick.' align="right">'.$i.'</td>
		<td '.$onclick.'>'.$row->codigoInterno.'</td>
		<td '.$onclick.'>'.$row->nombre.'</td>
		<td '.$onclick.'>'.$row->unidad.'</td>
		<td>'.$row->empresa.'
			<div style="text-align:center; width: 100px">
				<img title="Agregar proveedor" src="'.base_url().'img/proveedores.png" width="18" onclick="accesoAgregarProveedorCompraProducto('.$row->idMaterial.')" style="cursor:pointer" />
				<br />
				<a>Asignar proveedor</a>
			</div>
		</td>
		<td align="center" valign="middle">
			$<input type="text" class="cajas" style="width:70px"  id="precio'.$i.'" value="'.round($precio,decimales).'" onkeypress="return soloDecimales(event)" maxlength="15" onchange="editarPrecioProducto('.$i.','.$row->idMaterial.','.$row->idProveedor.')"/> 
			
			<img title="Actualizar precio" src="'.base_url().'img/guardar.png" width="18" onclick="editarPrecioProducto('.$i.','.$row->idMaterial.','.$row->idProveedor.')" style="cursor:pointer; display: none" />
		</td>
		<td '.$onclick.' align="center">
			'.number_format($row->stock,2);
			
			if(sistemaActivo=='cerraduras')
			{
				echo '<br />Stock máximo: '.round($row->stockMaximo,2);
				$stockMaximo	= $row->stock-$row->stockMaximo;
			}
			
		echo'
		</td>
		<td align="center">
			<input onchange="cargarProductoCompra('.$i.',\'no\',\''.$stockMaximo.'\')" type="text" value="0" style="width:50px" class="cajas" id="cantidad'.$i.'"  onkeypress="return soloDecimales(event)" maxlength="15"/>
			<input type="hidden" value="'.$row->idMaterial.'" id="agregar'.$i.'" />
			<input type="hidden"  id="descripcion'.$i.'" value="'.$row->nombre.'"/>
			<!--<input type="hidden" class="cajas" style="width:100px"  id="precio'.$i.'" value="'.$precio.'"/> -->
			<input type="hidden"  id="txtNombreProveedor'.$i.'" value="'.$row->empresa.'"/>
			<input type="hidden"  id="txtProveedor'.$i.'" value="'.$row->idProveedor.'"/>
			<input type="hidden"  id="txtCodigoInterno'.$i.'" value="'.$row->codigoInterno.'"/>
		</td>
	</tr>';
	
	$i++;
}

echo '</table>';
 $i=1;