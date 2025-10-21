<?php
$ultimo		= $this->inventario->obtenerUltimaCompraProducto($idProducto);
$unidad		= explode(',',$producto->unidad);

echo
'<table class="admintable" width="100%" style="margin-top:5px">
	<tr>
		<td align="center" colspan="2">';
		
		$imagen='<img src="'.base_url().carpetaProductos.'default.png" style="width:100px; height:100px;"  />';
	
		if(file_exists("img/productos/".$idProducto.'_'.$producto->imagen))
		{
			$imagen='<img src="'.base_url().carpetaProductos.$idProducto.'_'.$producto->imagen.'" style="width:100px; height:100px;" />';
		}
			
		echo $imagen;
			
		echo'</td>
	</tr>
	
	<tr>
		<td class="key">Nom:</td>
		<td>'.$producto->descripcion.'</td>
	</tr>
	<tr>
		<td class="key">Med:</td>
		<td>'.($unidad[1]).'</td>
	</tr>
	<tr>
		<td class="key">Costo actual:</td>
		<td>$'.number_format($ultimo,decimales).'</td>
	</tr>
	<tr>
		<td class="key">'.obtenerNombrePrecio(3).':</td>
		<td>$'.number_format($producto->precioC,decimales).'</td>
	</tr>
	<tr>
		<td class="key">'.obtenerNombrePrecio(1).':</td>
		<td>$'.number_format($producto->precioA,decimales).'</td>
	</tr>
	<tr>
		<td class="key">'.obtenerNombrePrecio(2).':</td>
		<td>
			$'.number_format($producto->precioB,decimales).'
			&nbsp;&nbsp;&nbsp;&nbsp;
			Apartir de '.round($producto->cantidadMayoreo,decimales).' &nbsp;&nbsp;&nbsp;&nbsp; (pzas/jgos/lts)
		
		</td>
	</tr>
	
	<tr>
		<td class="key">CVE de Fabrica:</td>
		<td>'.$producto->codigoInterno.'</td>
	</tr>
	
	<tr>
		<td class="key">Existencias:</td>
		<td>'.round($producto->stock,decimales).'</td>
	</tr>
	
	<tr>
		<td class="key">Exist. Mínimo:</td>
		<td>'.round($producto->stockMinimo,decimales).'</td>
	</tr>
	
	<tr>
		<td class="key">Clave S.A.T.:</td>
		<td>'.$producto->claveProducto.'</td>
	</tr>
</table>';