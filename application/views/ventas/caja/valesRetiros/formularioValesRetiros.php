<form id="frmValesRetiros">
	<input type="hidden" id="txtTipoRegistro" name="txtTipoRegistro" value="<?=$tipoRegistro?>"/>
	<table class="admintable" width="100%">
		<tr>
			<td class="key">Tipo:</td>
			<td><?=obtenerTipoRegistro($tipoRegistro)?></td>
		</tr>
		<tr>
			<td class="key">Folio:</td>
			<td><?=obtenerFolioRegistro($tipoRegistro).$folio?></td>
		</tr>
		<tr>
			<td class="key">Importe:</td>
			<td><input type="text" class="cajas" id="txtImporteValeRetiro" name="txtImporteValeRetiro" style="width: 140px" onKeyPress="return soloDecimales(event)" maxlength="6" /></td>
		</tr>
		<tr>
			<td class="key">Descripción:</td>
			<td><input type="text" class="cajas" id="txtValeRetiro" name="txtValeRetiro" style="width: 450px" /></td>
		</tr>

		<?php
		if($tipoRegistro=='1')
		{
			echo '
			<tr>
				<td class="key">Usuario:</td>
				<td>
					<select class="cajas" id="selectUsuarios" name="selectUsuarios" style="width: 450px">
						<option value="0">Usuario</option>';

						foreach($usuarios as $row)
						{
							echo '<option value="'.$row->idUsuario.'">'.$row->nombre.'</option>';
						}
					echo'
				</select>
				</td>
			</tr>';
		}
		?>
	</table>

</form>
