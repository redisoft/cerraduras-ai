<?php
echo '
<select class="form-control"  id="selectEstaciones" name="selectEstaciones" required="true">';
	foreach($estaciones as $row)
	{
		echo '<option value="'.$row->idEstacion.'">'.$row->nombre.'</option>';
	}
echo '</select>';
?>