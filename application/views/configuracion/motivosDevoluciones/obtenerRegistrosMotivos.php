<?php
echo '
<select name="selectMotivos" id="selectMotivos" class="cajas" style="width:200px">
	<option value="0">Seleccione</option>';
	
	foreach($motivos as $row) 
	{ 
		echo'<option value="'.$row->idMotivo.'">'.$row->nombre.'</option>';
	} 
	
echo'
</select>';