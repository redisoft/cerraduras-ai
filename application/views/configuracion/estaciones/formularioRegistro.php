<?php
echo'
<div class="ui-state-error" ></div>
<form id="frmRegistro" name="frmRegistro" action="javascript:registrarFormulario()">
	<table class="admintable" width="100%;">
		<tr>
			<td class="key" >Nombre:</td>
			<td>
				<input type="text" name="txtNombre" id="txtNombre" class="cajas" style="width:500px;"  maxlength="150" required="true"/> 
			</td>
		</tr>		
	</table>
</form>';