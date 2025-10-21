<?php
echo '
<select class="cajas" id="selectNivel1" name="selectNivel1" style="width:290px" onchange="obtenerNiveles2Catalogo()">
	<option value="0">Seleccione</option>';
	
	foreach($nivel as $row)
	{
		echo '<option value="'.$row->idNivel1.'">'.$row->nombre.'</option>';
	}
echo'
</select>';
?>