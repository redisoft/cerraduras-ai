<?php
echo '
<select class="cajas" id="selectNivel2" name="selectNivel2" style="width:290px" onchange="obtenerNiveles3Catalogo()">
	<option value="0">Seleccione</option>';
	
	foreach($nivel as $row)
	{
		echo '<option value="'.$row->idNivel2.'">'.$row->nombre.'</option>';
	}
echo'
</select>';
?>