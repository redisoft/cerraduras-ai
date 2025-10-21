<?php
echo '
<input type="hidden" id="txtIdPedido" value="'.$pedido->idPedido.'" />
<table class="admintable" width="100%">
	<tr>
		<td class="key">Pedido:</td>
		<td>'.$pedido->folio.'</td>
	</tr>
	<tr>
		<td class="key">Repartidor</td>
		<td>
			<select class="cajas" id="selectRepartidores" name="selectRepartidores" style="width:300px" >
				<option value="0">Seleccione</option>';
		
			foreach($personal as $row)
			{
				echo '<option '.($row->idPersonal==$pedido->idPersonal?'selected="selected"':'').' value="'.$row->idPersonal.'">'.$row->nombre.'</option>';
			}
			
		echo'
		/td>
	</tr>
</table>';