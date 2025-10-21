<?php
echo'
<select class="cajas" id="selectSubCuentasRegistro" name="selectSubCuentasRegistro" style="width:200px" onchange="obtenerCuentasCatalogoAsociar()">
	<option value="0">Seleccione subcuenta</option>';

	foreach($subCuentas as $row)
	{
		echo '<option value="'.$row->idSubCuenta.'">'.$row->codigo.', '.$row->nombre.'</option>';
	}
	
	echo'
</select>';
