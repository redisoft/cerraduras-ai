<?php
echo'
<div class="ui-state-error" ></div>
<form id="frmEditar" name="frmEditar" action="javascript:editarFormulario()">
	<table class="admintable" width="100%;">
		<tr>
			<td class="key" >Nombre:</td>
			<td>
				<input type="text" name="txtNombre" id="txtNombre" class="cajas" style="width:500px;"  maxlength="150" value="'.$registro->nombre.'" required="true"/> 
				<input type="hidden" name="txtIdRegistro" id="txtIdRegistro" class="cajas" value="'.$registro->idEstacion.'"/> 
			</td>
		</tr>
	</table>
</form>';