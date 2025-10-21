<select id="selectContactos" name="selectContactos" class="cajas" style="width:300px">
    <option value="0">Seleccione</option>
	<?php
	$i=0;
    foreach($contactos as $row)
    {
        echo '<option '.($i==0?'selected="selected"':'').' value="'.$row->idContacto.'">Nombre: '.$row->nombre.', Teléfono: '.$row->telefono.', Email: '.$row->email.'</option>';
		
		$i++;
    }
    ?>
</select>