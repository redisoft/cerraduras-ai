<?php
echo'
<select class="cajas" id="selectNombres" name="selectNombres" style="width:290px">
	<option value="0">Seleccione</option>';
	
	foreach($nombres as $row)
	{
		echo'<option value="'.$row->idNombre.'">'.$row->nombre.'</option>';	
	}
	
echo'</select>';