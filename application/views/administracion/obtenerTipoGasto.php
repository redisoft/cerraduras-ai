<?php
echo'
<select class="cajas" id="selectTipoGasto" name="selectTipoGasto" style="width:290px">
	<option value="0">Seleccione</option>';
	
	foreach($gastos as $row)
	{
		echo'<option value="'.$row->idGasto.'">'.$row->nombre.'</option>';	
	}
	
echo'</select>';