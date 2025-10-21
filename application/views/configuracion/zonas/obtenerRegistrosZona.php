<?php
echo '
<select name="selectZonas" id="selectZonas" class="cajas" style="width:200px">
	<option value="0">Seleccione</option>';
	
	foreach($zonas as $zona) 
	{ 
		echo'<option value="'.$zona['idZona'].'">'.$zona['descripcion'].'</option>';
	} 
	
echo'
</select>';