<?php
echo '
<select class="cajas" id="selectMotivos" name="selectMotivos" style="width:250px">
	<option value="0">Seleccione</option>'; 
					
	foreach($motivos as $row) 
	{
		echo '<option value="'.$row->idMotivo.'">'.$row->nombre.'</option>';  
	}
	
echo'
</select>';