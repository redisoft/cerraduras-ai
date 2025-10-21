<table class="admintable" width="100%" >
    <?php
    foreach($licencias as $row)
	{
		echo '
		<tr>
			<td class="key">'.$row->nombre.'</td>
			<td>
				<label>Numero ventas</label>
				<br />
				<input type="text" id="txtNumeroVentas'.$row->idLicencia.'" style="width:200px" class="cajas" value="'.$row->numeroVentas.'" onchange="editarNumeroVentas('.$row->idLicencia.')" onkeypress="return soloNumerico(event)" maxlength="3" />
				<br />
				<label>Importe</label>
				<br />
				<input type="text" id="txtImporteDinero'.$row->idLicencia.'" style="width:200px" class="cajas" value="'.round($row->importeDinero,decimales).'" onchange="editarNumeroVentas('.$row->idLicencia.')" onkeypress="return soloDecimales(event)" maxlength="10"  />
				
			</td>
		</tr>';
	}
	
	?>
</table>