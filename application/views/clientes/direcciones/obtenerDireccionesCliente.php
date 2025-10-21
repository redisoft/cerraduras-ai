<select id="selectDirecciones" name="selectDirecciones" class="cajas" style="width:550px;">
	<option value="0">Seleccione</option>
<?php
$i=0;
foreach($direcciones as $row)
{
	echo '<option '.($i==0?'selected="selected"':'').' value="'.$row->idDireccion.'">'.$row->razonSocial.', '.$row->calle.' '.$row->numero.' '.$row->colonia.'</option>';
	
	$i++;
}
?>
</select>