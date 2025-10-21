<select id="selectCausas" name="selectCausas" class="cajas" style="width:200px;">
	<option value="0">Seleccione</option>
<?php
foreach($causas as $row)
{
	echo '<option value="'.$row->idCausa.'">'.$row->nombre.'</option>';
}
?>
</select>