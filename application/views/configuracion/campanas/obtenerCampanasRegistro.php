<select id="selectProgramas" name="selectProgramas" class="cajas" style="width:200px;" onchange="sugerirCantidadesAcademico(0)">
	<option value="0">Seleccione</option>
<?php
foreach($programas as $row)
{
	echo '<option value="'.$row->idPrograma.'|'.$row->cantidadInscripcion.'|'.$row->cantidadColegiatura.'|'.$row->cantidadReinscripcion.'">'.$row->nombre.'</option>';
}
?>
</select>