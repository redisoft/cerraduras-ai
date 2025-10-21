<?php
#style="width:20vh; height: 2.5vh; font-size: 1.5vh"
echo '
<select class="cajas" id="selectSubLineas" name="selectSubLineas" style="width: 280px" onchange="obtenerProductosVenta()">
	<option value="0">Seleccione sublinea</option>';

	foreach($sublineas as $row)
	{
		echo '<option value="'.$row->idSubLinea.'">'.$row->nombre.'</option>';
	}

echo'
</select>';