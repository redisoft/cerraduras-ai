<select id="selectStatus" name="selectStatus" class="cajas" style="width:200px;" onchange="opcionesSeguimiento()">
<?php
foreach($status as $row)
{
	echo '<option value="'.$row->idStatus.'|'.($row->idStatus<=4?$row->idStatus:$row->idStatusIgual).'">'.$row->nombre.'</option>';
}
?>
</select>