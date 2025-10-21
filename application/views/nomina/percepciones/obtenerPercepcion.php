<form id="frmPercepciones" name="frmPercepciones">
    <table class="admintable" width="100%">
        <tr>
            <td class="key">Tipo percepción:</td>
            <td>
                <select class="cajas" id="selectPercepciones" name="selectPercepciones" style="width:400px">
                <?php
                foreach($percepciones as $row)
                {
					$seleccionado	=$row->idPercepcion==$percepcion->idPercepcion?'selected="selected"':'';
                    echo '<option '.$seleccionado.' value="'.$row->idPercepcion.'" >('.$row->clave.')'.$row->nombre.'</option>';
                }
                ?>
                 </select>
            </td>
        </tr>
        <tr>
            <td class="key">Clave:</td>
            <td>
                <input type="text" class="cajas" id="txtClave" name="txtClave" value="<?php echo $percepcion->clave?>"  style="width:100px" maxlength="10"/>
                <input type="hidden" id="txtIdCatalogoPercepcion" name="txtIdCatalogoPercepcion" value="<?php echo $percepcion->idCatalogoPercepcion?>"  />
            </td>
        </tr>
        <tr>
            <td class="key">Concepto:</td>
            <td>
                <input type="text" class="cajas" id="txtConcepto" name="txtConcepto" value="<?php echo $percepcion->concepto?>" style="width:400px" />
            </td>
        </tr>
        
        <tr>
            <td class="key">Importe gravado:</td>
            <td>
                <input type="text" class="cajas" id="txtImporteGravado" name="txtImporteGravado" value="<?php echo round($percepcion->importeGravado,2)?>" style="width:100px" maxlength="10" />
            </td>
        </tr>
        <tr>
            <td class="key">Importe excento:</td>
            <td>
                <input type="text" class="cajas" id="txtImporteExento" name="txtImporteExento" value="<?php echo round($percepcion->importeExento,2)?>" style="width:100px" maxlength="10" />
            </td>
        </tr>
    </table>
</form>