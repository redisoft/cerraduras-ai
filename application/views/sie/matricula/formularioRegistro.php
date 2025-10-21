<div id="registrandoMatriculaSie"></div>

<form id="frmRegistroMatriculaSie">
    <table class="admintable" width="100%">
    	<tr>
            <td class="key">Cuatrimestre:</td>
            <td>
               <select id="selectCuatrimestreSie" name="selectCuatrimestreSie" class="cajas" style="width:60px">
                   
                   	<?php
                    for($i=1;$i<5;$i++)
					{
						echo '<option value="'.$i.'">'.$i.'</option>';
					}
					?>
                </select>
            </td>
        </tr>
        
    	<tr>
            <td class="key">Programa:</td>
            <td>
                <select id="selectProgramasSie" name="selectProgramasSie" class="cajas" style="width:400px">
                	<!--<option value="0">Seleccione</option>-->
                   
                   	<?php
                    foreach($programas as $row)
					{
						echo '<option value="'.$row->idPrograma.'">'.$row->nombre.'</option>';
					}
					?>
                </select>
            </td>
        </tr>
        
        <tr>
            <td class="key">Ingresos:</td>
            <td>
                <input type="text" class="cajas" id="txtIngresosSie" name="txtIngresosSie" style="width:100px" onkeypress="return soloNumerico(event)" maxlength="8"  />
            </td>
        </tr>
        
        <tr>
            <td class="key">Actual:</td>
            <td>
                <input type="text" class="cajas" id="txtActualSie" name="txtActualSie" style="width:100px" onkeypress="return soloNumerico(event)" maxlength="8"  />
            </td>
        </tr>
        
        <tr>
            <td class="key">Meta:</td>
            <td>
                <input type="text" class="cajas" id="txtMetaSie" name="txtMetaSie" style="width:100px" onkeypress="return soloDecimales(event)" maxlength="7"  />
            </td>
        </tr>
    </table>
</form>