<div id="registrandoInformacion"></div>

<form id="frmRegistro">
	<input type="hidden" id="txtIdIngreso" name="txtIdIngreso" value="<?=$registro->idIngreso?>"  />
    <input type="hidden" id="txtImporteIngreso" name="txtImporteIngreso" value="<?=$registro->importe?>"  />
    
    <input type="hidden" id="txtNumeroCuentas" 			name="txtNumeroCuentas" 		value="<?=count($cuentas)?>"  />
    <input type="hidden" id="txtTotalCobradoIngreso" 	name="txtTotalCobradoIngreso" 	value="0"  />
    
    <table class="admintable" width="100%">
		<tr>
            <td class="key">Total:</td>
            <td>
               $<?=number_format($registro->importe,decimales)?>
            </td>
        </tr>
        <tr>
            <td class="key">Concepto:</td>
            <td>
               <?=$registro->concepto?>
            </td>
        </tr>
        
        <tr>
            <td class="key">Efectivo:</td>
            <td>
                <input type="text" id="txtEfectivo" name="txtEfectivo" value="0" class="cajas" style="width:100px" maxlength="14" onkeypress="return soloDecimales(event)"  />
                <?='$'.number_format($financiera->efectivo,decimales)?>
            </td>
        </tr>
        
        <?php
		$i=0;
		foreach($cuentas as $row)
		{
			echo '
			<tr>
				<td class="key">'.$row->banco.' '.$row->cuenta.':</td>
				<td>
					<input type="text" id="txtCuentas'.$i.'" name="txtCuentas'.$row->idCuenta.'" value="'.round(0,decimales).'" style="width:100px" class="cajas" maxlength="10" onkeypress="return soloDecimales(event)" />
					$'.number_format($row->saldoManual,decimales).'
				</td>
			</tr>';
			
			$i++;
		}
		
		?>
        
        <!--
        
        <tr>
            <td class="key">Cuentas:</td>
            <td>
                <input type="text" id="txtCuentas" name="txtCuentas" value="0" class="cajas" style="width:100px" maxlength="14" onkeypress="return soloDecimales(event)" />
            </td>
        </tr>
        
        <tr>
            <td class="key">Paypal:</td>
            <td>
                <input type="text" id="txtPaypal" name="txtPaypal" value="0" class="cajas" style="width:100px" maxlength="14" onkeypress="return soloDecimales(event)"/>
            </td>
        </tr>-->
    </table>
</form>