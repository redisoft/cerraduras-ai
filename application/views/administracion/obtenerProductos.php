<?php
echo'
<select class="cajas" id="selectProductos" name="selectProductos" style="width:290px">
	<option value="0">Seleccione</option>';
	
	foreach($productos as $row)
	{
		echo'<option value="'.$row->idProducto.'">'.$row->nombre.'</option>';	
	}
	
echo'</select>';