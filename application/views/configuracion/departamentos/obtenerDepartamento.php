<?php
echo'
<form id="frmDepartamentos">
	<table class="admintable" width="100%">
		<tr>
			<td class="key">Departamento:</td>
			<td>
				<input type="text" class="cajas" id="txtDepartamento"  name="txtDepartamento" value="'.$departamento->nombre.'" style="width:300px"/>
				<input type="hidden" id="txtIdDepartamento" name="txtIdDepartamento" value="'.$departamento->idDepartamento.'" />
			</td>
		</tr>
	</table>
</form>';