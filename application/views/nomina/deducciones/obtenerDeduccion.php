<form id="frmDeducciones" name="frmDeducciones">
    <table class="admintable" width="100%">
        <tr>
            <td class="key">Tipo deducción:</td>
            <td>
                <select class="cajas" id="selectDeducciones" name="selectDeducciones" style="width:400px">
                <?php
                foreach($deducciones as $row)
                {
					$seleccionado	=$row->idDeduccion==$deduccion->idDeduccion?'selected="selected"':'';
                    echo '<option '.$seleccionado.' value="'.$row->idDeduccion.'" >('.$row->clave.')'.$row->nombre.'</option>';
                }
                ?>
                 </select>
            </td>
        </tr>
        <tr>
            <td class="key">Clave:</td>
            <td>
                <input type="text" class="cajas" id="txtClave" name="txtClave" value="<?php echo $deduccion->clave?>"  style="width:100px" maxlength="10"/>
                <input type="hidden" id="txtIdCatalogoDeduccion" name="txtIdCatalogoDeduccion" value="<?php echo $deduccion->idCatalogoDeduccion?>"  />
            </td>
        </tr>
        <tr>
            <td class="key">Concepto:</td>
            <td>
                <input type="text" class="cajas" id="txtConcepto" name="txtConcepto" value="<?php echo $deduccion->concepto?>" style="width:400px" />
            </td>
        </tr>
        
        <tr>
            <td class="key">Importe gravado:</td>
            <td>
                <input type="text" class="cajas" id="txtImporteGravado" name="txtImporteGravado" value="<?php echo round($deduccion->importeGravado,2)?>" style="width:100px" maxlength="10" />
            </td>
        </tr>
        <tr>
            <td class="key">Importe excento:</td>
            <td>
                <input type="text" class="cajas" id="txtImporteExento" name="txtImporteExento" value="<?php echo round($deduccion->importeExento,2)?>" style="width:100px" maxlength="10" />
            </td>
        </tr>
    </table>
</form>