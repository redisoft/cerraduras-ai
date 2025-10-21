<?php
echo '
<select class="cajas" id="selectNiveles2Busqueda" name="selectNiveles2Busqueda" style="width:105px" onchange="obtenerNiveles3Busqueda()">
	<option value="0">Nivel 2</option>';
	
	foreach($niveles as $row)
	{
		echo '<option  value="'.$row->idNivel2.'">'.$row->nombre.'</option>';
	}

echo'
</select>';
?>