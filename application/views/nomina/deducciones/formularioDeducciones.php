<form id="frmDeducciones" name="frmDeducciones">
    <table class="admintable" width="100%">
        <tr>
            <td class="key">Tipo deducción:</td>
            <td>
                <select class="cajas" id="selectDeducciones" name="selectDeducciones" style="width:400px">
                <?php
                foreach($deducciones as $row)
                {
                    echo '<option value="'.$row->idDeduccion.'" >('.$row->clave.') '.$row->nombre.'</option>';
                }
                ?>
                </select>
            </td>
        </tr>
        <tr>
            <td class="key">Clave:</td>
            <td>
                <input type="text" class="cajas" id="txtClave" name="txtClave" style="width:100px" maxlength="10"  />
            </td>
        </tr>
        <tr>
            <td class="key">Concepto:</td>
            <td>
                <input type="text" class="cajas" id="txtConcepto" name="txtConcepto" style="width:400px"  />
            </td>
        </tr>
        
        <tr>
            <td class="key">Importe gravado:</td>
            <td>
                <input type="text" class="cajas" id="txtImporteGravado" name="txtImporteGravado" value="0" style="width:100px" maxlength="10" onkeypress="return soloDecimales(event)"   />
            </td>
        </tr>
        <tr>
            <td class="key">Importe exento:</td>
            <td>
                <input type="text" class="	cajas" id="txtImporteExento" name="txtImporteExento" value="0"  style="width:100px" maxlength="10" onkeypress="return soloDecimales(event)"  />
            </td>
        </tr>
    </table>
</form>