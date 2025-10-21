<?php
echo'
<form id="frmSubCategorias">
<table class="admintable" width="100%">
	<tr>
		<td class="key">Subcategoría:</td>
		<td>
			<input style="width:200px" type="text" class="cajas" id="txtSubCategoria" name="txtSubCategoria" value="'.$subCategoria->nombre.'" />
			<input type="hidden" id="txtIdSubCategoria" name="txtIdSubCategoria" value="'.$subCategoria->idSubCategoria.'" />
		</td>
	</tr>
</table>
</form>';