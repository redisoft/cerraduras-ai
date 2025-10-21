<?php
echo
'<table class="admintable" width="100%" style="margin-top:5px">
	<tr>
		<td align="center">';
		
		$imagen='<img src="'.base_url().carpetaProductos.'default.png" style="width:100px; height:100px;"  />';
	
		if(file_exists("img/productos/".$idProducto.'_'.$producto->imagen))
		{
			$imagen='<img src="'.base_url().carpetaProductos.$idProducto.'_'.$producto->imagen.'" style="width:100px; height:100px;" />';
		}
			
		echo $imagen;
			
		echo'</td>
		<td align="center">
		
		<script>
			$("#codigoEditar").barcode("'.$producto->codigoBarras.'", "code93",{barWidth:1, barHeight:40})
		</script>
		<div id="codigoEditar"></div></td>
	</tr>
	<tr class="sombreado">
		<td><strong>Nombre: </strong>'.$producto->descripcion.'</td>
		<td><strong>Código interno:</strong> '.$producto->codigoInterno.'</td>
	</tr>
	<tr class="sinSombra">
		<td><strong>Fecha registro: </strong> '.obtenerFechaMesCortoHora($producto->fecha).'</td>
		<td><strong>Unidad: </strong>'.$producto->unidad.'</td>
	</tr>
	<tr class="sombreado">
		<td><strong>Precio A: </strong> $'.number_format($producto->precioA,decimales).'</td>
		<td><strong>Precio B: </strong> $'.number_format($producto->precioB,decimales).'</td>
	</tr>
	
	<tr class="sinSombra">
		<td><strong>Precio C:</strong> $'.number_format($producto->precioC,decimales).'</td>
		<td><strong>Precio D: </strong>$'.number_format($producto->precioD,decimales).'</td>
	</tr>
	
	<tr class="sombreado">
		<td><strong>Precio E:</strong> $'.number_format($producto->precioE,decimales).'</td>
		<td><strong>Inventario:</strong> '.number_format($producto->stock,decimales).' unidades</td>
	</tr>
</table>';