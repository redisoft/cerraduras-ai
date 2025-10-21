<?php
echo'
<form id="frmCategorias">
	<table class="admintable" width="100%">
		<tr>
			<td class="key">Categoría:</td>
			<td>
				<input type="text" class="cajas" id="txtCategoria"  name="txtCategoria" value="'.$categoria->nombre.'" style="width:300px"/>
				<input type="hidden" id="txtIdCategoria" name="txtIdCategoria" value="'.$categoria->idCategoria.'" />
			</td>
		</tr>
	</table>
</form>';