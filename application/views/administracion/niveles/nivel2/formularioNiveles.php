<table class="admintable" width="100%">
	<tr>
    	<td class="key">Nombre:</td>
        <td>
        	<input type="text" class="cajas" id="txtNivel2" style="width:300px"  />
        </td>
    </tr>
    
    <tr>
    	<td class="key">Nivel 1:</td>
        <td>
        	<?php
            echo '
			<select class="cajas" id="selectNiveles1Registro" name="selectNiveles1Registro" style="width:290px">
				<option value="0">Seleccione</option>';
				
				foreach($nivel1 as $row)
				{
					echo '<option value="'.$row->idNivel1.'">'.$row->nombre.'</option>';
				}
			echo'
			</select>';
			?>
        </td>
    </tr>
</table>