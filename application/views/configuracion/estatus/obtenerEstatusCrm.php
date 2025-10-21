<select id="selectEstatus" name="selectEstatus" class="cajas" style="width:200px;">
	<option value="0">Seleccione</option>
<?php
foreach($estatus as $row)
{
	echo '<option value="'.$row->idEstatus.'">'.$row->nombre.'</option>';
}
?>
</select>