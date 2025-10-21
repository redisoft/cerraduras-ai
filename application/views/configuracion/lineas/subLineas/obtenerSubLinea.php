<?php
echo '
<form id="frmSubLineas" name="frmSubLineas">
	<table class="admintable" width="100%;">
		<tr>
			<td class="key">Sublinea:</td>
			<td>
				<input name="txtSubLinea" style="width:300px" id="txtSubLinea" type="text" class="cajas" value="'.$sublinea->nombre.'"  />
				<input type="hidden" name="txtIdSubLinea" id="txtIdSubLinea" value="'.$sublinea->idSubLinea.'" />
			</td>
		</tr>	
	</table>
</form>';