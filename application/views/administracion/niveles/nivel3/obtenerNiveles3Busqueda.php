<?php
echo '
<select class="cajas" id="selectNiveles3Busqueda" name="selectNiveles3Busqueda" style="width:105px" >
	<option value="0">Nivel 3</option>';
	
	foreach($niveles as $row)
	{
		echo '<option  value="'.$row->idNivel3.'">'.$row->nombre.'</option>';
	}

echo'
</select>';
?>