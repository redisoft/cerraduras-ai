<?php
echo'
<select class="cajas" id="selectCuentasRegistro" name="selectCuentasRegistro" style="width:200px" onchange="obtenerSubCuentasRegistro()">
	<option value="0">Seleccione cuenta</option>';
	
	foreach($cuentas as $row)
	{
		echo '<option value="'.$row->idCuenta.'">'.$row->codigo.', '.$row->nombre.'</option>';
	}
	
	echo'
</select>';
