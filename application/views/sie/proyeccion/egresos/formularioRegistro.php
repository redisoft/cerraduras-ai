<div id="registrandoInformacion"></div>

<form id="frmRegistro">
    <table class="admintable" width="100%">
        <tr>
            <td class="key">Fecha:</td>
            <td>
                <input type="text" class="cajas" id="txtFecha" name="txtFecha" style="width:100px" value="<?=date('Y-m-d')?>"  />
                <script>
				$('#txtFecha').datepicker();
				</script>
            </td>
        </tr>
        
        <tr>
            <td class="key">Fecha de pago:</td>
            <td>
                <input type="text" class="cajas" id="txtFechaPago" name="txtFechaPago" style="width:100px" value="<?=date('Y-m-d')?>"  />
                <script>
				$('#txtFechaPago').datepicker();
				</script>
            </td>
        </tr>
        
        <tr>
            <td class="key">Nombre:</td>
            <td>
                <input type="text" class="cajas" id="txtConcepto" name="txtConcepto" style="width:400px"  />
            </td>
        </tr>
        
        <tr>
            <td class="key">Importe:</td>
            <td>
                <input type="text" class="cajas" id="txtImporte" name="txtImporte" style="width:100px" onkeypress="return soloDecimales(event)" maxlength="10"  />
            </td>
        </tr>
        
        <tr>
            <td class="key">Escenario:</td>
            <td>
                <select id="selectEscenarios" name="selectEscenarios" class="cajas" style="width:200px">
                	<option value="0">Seleccione</option>
                    <?php
                    foreach($escenarios as $row)
					{
						echo '<option value="'.$row->idEscenario.'">'.$row->nombre.'</option>';
					}
                    ?>
                </select>
            </td>
        </tr>
        
    </table>
</form>