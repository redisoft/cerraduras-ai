<?php
echo'
<form id="frmMarcas">
	<table class="admintable" width="100%">
		<tr>
			<td class="key">Marca:</td>
			<td>
				<input type="text" class="cajas" id="txtMarca"  name="txtMarca" value="'.$marca->nombre.'" style="width:300px"/>
				<input type="hidden" id="txtIdMarca" name="txtIdMarca" value="'.$marca->idMarca.'" />
			</td>
		</tr>
	</table>
</form>';