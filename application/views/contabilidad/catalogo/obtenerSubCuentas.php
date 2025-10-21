<?php
echo'
<select id="selectSubCuenta" name="selectSubCuenta" class="selectTextosGrandes" style="margin-top:5px" onchange="definirCodigoAgrupador()">	
	<option value="0">Seleccione cuenta</option>';
	
	foreach($subcuentas as $row)
	{
		echo '<option value="'.$row->idSubCuenta.'-'.$row->codigo.'">'.$row->nombre.'</option>';
	}
	
echo'
</select>';
