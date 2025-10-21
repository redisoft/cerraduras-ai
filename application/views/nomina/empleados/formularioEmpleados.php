<script>
	$('#txtFechaInicio').datepicker();
</script>
<form id="frmEmpleados" name="frmEmpleados">
    <table class="admintable" style="width:100%">
        
         <tr>
            <td class="key">Nombre:</td>
            <td>
                <input type="text" class="cajas" id="txtNombre" name="txtNombre" maxlength="100"  />
            </td>
        </tr>
        <tr>
            <td class="key">Número empleado:</td>
            <td>
                <input type="text" class="cajas" id="txtNumeroEmpleado" name="txtNumeroEmpleado" />
            </td>
        </tr>
        
        <tr>
            <td class="key">Registro patronal:</td>
            <td>
                <input type="text" class="cajas" id="txtRegistroPatronal" name="txtRegistroPatronal" />
            </td>
        </tr>
        
        <tr>
            <td class="key">CURP:</td>
            <td>
                <input type="text" class="cajas" id="txtCurp" name="txtCurp" />
            </td>
        </tr>
        
        <tr>
            <td class="key">RFC:</td>
            <td>
                <input type="text" class="cajas" id="txtRfc" name="txtRfc" />
            </td>
        </tr>
        
        <tr>
            <td class="key">Régimen:</td>
            <td>
                 <select class="cajas" id="selectRegimen" name="selectRegimen" style="width:490px" >
                <?php
                foreach($regimen as $row)
                {
                    echo '<option value="'.$row->idRegimen.'" >'.$row->nombre.'</option>';
                }
                ?>
                </select>
            </td>
        </tr>
        
        <tr>
            <td class="key">Número seguro:</td>
            <td>
                <input type="text" class="cajas" id="txtNumeroSeguro" name="txtNumeroSeguro" />
            </td>
        </tr>
        
        <tr>
            <td class="key">Fecha inicio:</td>
            <td>
                <input type="text" class="cajas" id="txtFechaInicio" name="txtFechaInicio" style="width:100px" readonly="readonly" value="<?php echo date('Y-m-d')?>" />
                
            </td>
        </tr>
        
         <tr>
            <td class="key">Departamento:</td>
            <td>
                <select class="cajas" id="selectDepartamentos" name="selectDepartamentos" >
                	<option value="0">Seleccione</option>
                <?php
                foreach($departamentos as $row)
                {
                    echo '<option value="'.$row->idDepartamento.'" >'.$row->nombre.'</option>';
                }
                ?>
                </select>
            </td>
        </tr>
        
         <tr>
            <td class="key">Puesto:</td>
            <td>
                <select class="cajas" id="selectPuestos" name="selectPuestos" >
                	<option value="0">Seleccione</option>
                <?php
                foreach($puestos as $row)
                {
                    echo '<option value="'.$row->idPuesto.'" >'.$row->nombre.'</option>';
                }
                ?>
                </select>
            </td>
        </tr>
        
        <tr>
            <td class="key">Tipo de contrato:</td>
            <td>
                <select class="cajas" id="selectTipoContrato" name="selectTipoContrato" >
                	<option>Base</option>
                    <option>Eventual</option>
                    <option>Confianza</option>
                    <option>Sindicalizado</option>
                    <option>A prueba</option>
                </select>
            </td>
        </tr>
        
        <tr>
            <td class="key">Tipo de jornada:</td>
            <td>
                 <select class="cajas" id="selectTipoJornada" name="selectTipoJornada" >
                	<option>Diurna</option>
                    <option>Nocturna</option>
                    <option>Mixta</option>
                    <option>Por hora</option>
                    <option>Reducida</option>
                    <option>Continuada</option>
                    <option>Partida</option>
                    <option>Por turnos</option>
                </select>
            </td>
        </tr>
        
        <tr>
            <td class="key">Periodicidad de pago:</td>
            <td>
               <select class="cajas" id="selectPeriodicidadPago" name="selectPeriodicidadPago" >
                	<option>Diario</option>
                    <option>Semanal</option>
                    <option>Quincenal</option>
                    <option>Catorcenal</option>
                    <option>Mensual</option>
                    <option>Bimestral</option>
                    <option>Unidad de Obra</option>
                    <option>Precio Alzado</option>
                </select>
            </td>
        </tr>
        
        <tr>
            <td class="key">Riesgo:</td>
            <td>
                <select class="cajas" id="selectRiesgo" name="selectRiesgo" >
                	<option value="0">Seleccione</option>
                <?php
                foreach($riesgo as $row)
                {
                    echo '<option value="'.$row->idRiesgo.'" >'.$row->nombre.'</option>';
                }
                ?>
                </select>
            </td>
        </tr>
        
        
        <tr>
            <td class="key">Banco:</td>
            <td>
                <select class="cajas" id="selectBancos" name="selectBancos" >
                	<option value="0">Seleccione</option>
                <?php
                foreach($bancos as $row)
                {
                    echo '<option value="'.$row->idBanco.'" >('.$row->clave.') '.$row->nombre.'</option>';
                }
                ?>
                </select>
            </td>
        </tr>
       
        <tr>
            <td class="key">Clabe:</td>
            <td>
                <input type="text" class="cajas" id="txtClabe" name="txtClabe" />
            </td>
        </tr>
        
        <tr>
            <td class="key">Email:</td>
            <td>
                <input type="text" class="cajas" id="txtEmail" name="txtEmail" maxlength="100"  />
            </td>
        </tr>
        
        <tr>
            <td class="key">Salario diario integrado:</td>
            <td>
                <input type="text" class="cajas" id="txtSalarioDiario" name="txtSalarioDiario" value="0" style="width:100px" maxlength="10" onkeypress="return soloDecimales(event)"   />
            </td>
        </tr>
        <tr>
            <td class="key">Salario base:</td>
            <td>
                <input type="text" class="cajas" id="txtSalarioBase" name="txtSalarioBase" value="0"  style="width:100px" maxlength="10" onkeypress="return soloDecimales(event)"  />
            </td>
        </tr>
    </table>
</form>