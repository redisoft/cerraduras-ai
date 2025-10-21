<?php
echo '
<select class="cajas" id="selectNivel3" name="selectNivel3" style="width:290px">
	<option value="0">Seleccione</option>';
	
	foreach($nivel as $row)
	{
		echo '<option value="'.$row->idNivel3.'">'.$row->nombre.'</option>';
	}
echo'
</select>';
?>