<div id="registrandoInformacion"></div>

<form id="frmRegistro">
	<input type="hidden" class="cajas" id="txtIdCredito" name="txtIdCredito" value="<?=$registro->idCredito?>"  />
    <table class="admintable" width="100%">
    	<tr>
            <td class="key">Fuente:</td>
            <td>
                <input type="text" class="cajas" id="txtFuente" name="txtFuente" style="width:400px" value="<?=$registro->fuente?>" />
            </td>
        </tr>
        
        <tr>
            <td class="key">Monto:</td>
            <td>
                <input type="text" class="cajas" id="txtMonto" name="txtMonto" style="width:100px" onkeypress="return soloDecimales(event)" maxlength="10" value="<?=round($registro->monto,decimales)?>" />
            </td>
        </tr>
        
        <tr>
            <td class="key">Interés anual:</td>
            <td>
                <input type="text" class="cajas" id="txtInteresAnual" name="txtInteresAnual" style="width:100px" onkeypress="return soloDecimales(event)" maxlength="6" value="<?=round($registro->interesAnual,decimales)?>" />
            </td>
        </tr>
        
        <tr>
            <td class="key">Adeudo actual:</td>
            <td>
                <input type="text" class="cajas" id="txtAdeudoActual" name="txtAdeudoActual" style="width:100px" onkeypress="return soloDecimales(event)" maxlength="10" value="<?=round($registro->adeudoActual,decimales)?>" />
            </td>
        </tr>
        
        <tr>
            <td class="key">Frecuencia:</td>
            <td>
                <select id="selectFrecuencias" name="selectFrecuencias" class="cajas" style="width:200px">
                	<option value="0">Seleccione</option>
                    	<?php
						foreach($frecuencia as $row)
						{
							echo '<option '.($registro->idFrecuencia==$row->idFrecuencia?'selected="selected"':'').' value="'.$row->idFrecuencia.'">'.$row->nombre.'</option>';
						}
						?>
                </select>
            </td>
        </tr>
        
        <tr>
            <td class="key">Fecha:</td>
            <td>
                <input type="text" class="cajas" id="txtFechaPago" name="txtFechaPago" style="width:100px" value="<?=$registro->fechaPago?>"  />
                <script>
				$('#txtFechaPago').datepicker();
				</script>
            </td>
        </tr>

        <tr>
            <td class="key">Pago:</td>
            <td>
                <input type="text" class="cajas" id="txtPago" name="txtPago" style="width:100px" onkeypress="return soloDecimales(event)" value="<?=round($registro->pago,decimales)?>" maxlength="10"  />
            </td>
        </tr>
    </table>
</form>