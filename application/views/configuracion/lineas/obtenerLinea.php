<?php
echo '
<form id="frmLineas" name="frmLineas" action="'.base_url().'configuracion/editarLinea" method="post" enctype="multipart/form-data">
	<table class="admintable" width="100%;">
		<tr>
			<td class="key">Nombre:</td>
			<td>
				<input name="txtNombre" style="width:300px" value="'.$linea->nombre.'" id="txtNombre" type="text" class="cajas"  />
				<input value="'.$linea->idLinea.'" id="txtIdLinea" name="txtIdLinea" type="hidden" />
			</td>
		</tr>	
		<tr>
			<td class="key">Imagen:</td>
			<td>
				<input name="txtImagen" style="height:30px; width:300px" id="txtImagen" type="file" class="cajas"  />
			</td>
		</tr>
	</table>
</form>';