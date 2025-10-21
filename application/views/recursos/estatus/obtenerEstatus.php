<?php
echo'
<select class="cajas" id="selectEstatus" name="selectEstatus" style="width:290px">
	<option value="0">Seleccione</option>';
	
	foreach($estatus as $row)
	{
		echo'<option value="'.$row->idEstatus.'">'.$row->nombre.'</option>';	
	}
	
echo'</select>';