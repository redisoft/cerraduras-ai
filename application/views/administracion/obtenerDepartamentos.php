<?php
echo'
<select class="cajas" id="selectDepartamento" name="selectDepartamento" style="width:290px">
	<option value="0">Seleccione</option>';
	
	foreach($departamentos as $row)
	{
		echo'<option value="'.$row->idDepartamento.'">'.$row->nombre.'</option>';	
	}
	
echo'</select>';