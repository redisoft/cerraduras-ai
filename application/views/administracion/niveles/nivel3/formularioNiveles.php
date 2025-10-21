<table class="admintable" width="100%">
	<tr>
    	<td class="key">Nombre:</td>
        <td>
        	<input type="text" class="cajas" id="txtNivel3" style="width:300px"  />
        </td>
    </tr>
    
    <tr>
    	<td class="key">Nivel 2:</td>
        <td>
        	<?php
            echo '
			<select class="cajas" id="selectNiveles2Registro" name="selectNiveles2Registro" style="width:290px">
				<option value="0">Seleccione</option>';
				
				foreach($nivel2 as $row)
				{
					echo '<option value="'.$row->idNivel2.'">'.$row->nombre.'</option>';
				}
			echo'
			</select>';
			?>
        </td>
    </tr>
</table>