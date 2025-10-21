<select id="selectServicio" name="selectServicio" class="cajas" style="width:200px; " onchange="opcionesServicios()">
<?php
foreach($servicios as $row)
{
	echo '<option value="'.$row->idServicio.'">'.$row->nombre.'</option>';
}
?>
</select>