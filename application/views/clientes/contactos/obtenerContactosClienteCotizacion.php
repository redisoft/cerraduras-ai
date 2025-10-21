<select id="selectContactosClienteCotizacion" name="selectContactosClienteCotizacion" class="cajas" style="width:250px">
	<option value="0">Seleccione contacto</option>
    
    <?php
    foreach($contactos as $row)
	{
		echo '<option value="'.$row->idContacto.'">'.$row->nombre.'</option>';
	}
	?>
</select>