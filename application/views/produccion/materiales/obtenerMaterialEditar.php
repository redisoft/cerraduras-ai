<?php
echo'
	<table class="admintable" width="100%;">
	<tr>
		<td class="key">Material:</td>
		<td>
			<input type="text" readonly name="material" id="material" class="cajas" style="width:90%;" value="'.$material->nombre.'"/> 
			<input type="hidden" id="txtIdProductoEditar" value="'.$idProducto.'"/> 
		</td>
	</tr>
	
	<tr>
		<td class="key">Cantidad:</td>
		<td><input type="text" name="cantidad" id="cantidad" class="cajas" style="width:30%;" value="'.round($material->cantidad,4).'"  /> </td>
	</tr>';

echo '</table>';