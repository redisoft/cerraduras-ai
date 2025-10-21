<select id="selectDireccionesCfdi" name="selectDireccionesCfdi" class="cajas" style="width:550px;">
	<option value="0">Seleccione</option>
<?php
foreach($direcciones as $row)
{
	echo '<option value="'.$row->idDireccion.'">'.$row->razonSocial.', '.$row->calle.' '.$row->numero.' '.$row->colonia.'</option>';
}
?>
</select>