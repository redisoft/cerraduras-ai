<select id="selectContactos" name="selectContactos" class="cajas" style="width:300px">
    <option value="0">Seleccione</option>
	<?php
    foreach($contactos as $row)
    {
        echo '<option value="'.$row->idContacto.'">Nombre: '.$row->nombre.', Teléfono: '.$row->telefono.', Email: '.$row->email.'</option>';
    }
    ?>
</select>