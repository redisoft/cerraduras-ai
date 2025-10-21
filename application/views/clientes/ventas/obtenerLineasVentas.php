<?php
foreach($lineas as $row)
{
	echo '<div class="puntoVentaLineas" onclick="obtenerSubLineasVentas('.$row->idLinea.')">';
	
	if(file_exists(carpetaProductos.$row->imagen) and strlen($row->imagen)>4)
	{
		echo '<img src="'.base_url().carpetaProductos.$row->imagen.'" />';
	}
	else
	{
		echo '<img src="'.base_url().carpetaProductos.'default.png" />';
	}
	
	echo '<section>'.$row->nombre.'</section>
	
	</div>';
}