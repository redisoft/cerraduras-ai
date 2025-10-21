<script>
$(document).ready(function()
{
	$('#txtFechaInicio').datepicker();
})
</script>

<form id="frmEmpleados" name="frmEmpleados">
    <table class="admintable" style="width:100%">
        
         <tr>
            <td class="key">Nombre:</td>
            <td>
                <input type="text" class="cajas" id="txtNombre" name="txtNombre" maxlength="100" value="<?php echo $empleado->nombre?>" />
                <input type="hidden" id="txtIdEmpleado" name="txtIdEmpleado" value="<?php echo $empleado->idEmpleado?>" />
            </td>
        </tr>
        <tr>
            <td class="key">Número empleado:</td>
            <td>
                <input type="text" class="cajas" id="txtNumeroEmpleado" name="txtNumeroEmpleado" value="<?php echo $empleado->numeroEmpleado?>"/>
            </td>
        </tr>
        
        <tr>
            <td class="key">Registro patronal:</td>
            <td>
                <input type="text" class="cajas" id="txtRegistroPatronal" name="txtRegistroPatronal" value="<?php echo $empleado->registroPatronal?>"/>
            </td>
        </tr>
        
        <tr>
            <td class="key">CURP:</td>
            <td>
                <input type="text" class="cajas" id="txtCurp" name="txtCurp" value="<?php echo $empleado->curp?>"/>
            </td>
        </tr>
        
        <tr>
            <td class="key">RFC:</td>
            <td>
                <input type="text" class="cajas" id="txtRfc" name="txtRfc" value="<?php echo $empleado->rfc?>"/>
            </td>
        </tr>
        
        <tr>
            <td class="key">Régimen:</td>
            <td>
                 <select class="cajas" id="selectRegimen" name="selectRegimen" style="width:490px" >
                <?php
                foreach($regimen as $row)
                {
					$seleccionado=$row->idRegimen==$empleado->idRegimen?'selected="selected"':'';
                    echo '<option '.$seleccionado.' value="'.$row->idRegimen.'" >'.$row->nombre.'</option>';
                }
                ?>
                </select>
            </td>
        </tr>
        
        <tr>
            <td class="key">Número seguro:</td>
            <td>
                <input type="text" class="cajas" id="txtNumeroSeguro" name="txtNumeroSeguro" value="<?php echo $empleado->numeroSeguridad?>"/>
            </td>
        </tr>
        
        <tr>
            <td class="key">Fecha inicio:</td>
            <td>
                <input type="text" class="cajas" id="txtFechaInicio" name="txtFechaInicio" value="<?php echo $empleado->fechaInicio?>" style="width:100px"/>
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
					$seleccionado	=$row->idDepartamento==$empleado->idDepartamento?'selected="selected"':'';
                    echo '<option '.$seleccionado.' value="'.$row->idDepartamento.'" >'.$row->nombre.'</option>';
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
					$seleccionado	=$row->idPuesto==$empleado->idPuesto?'selected="selected"':'';
                    echo '<option '.$seleccionado.' value="'.$row->idPuesto.'" >'.$row->nombre.'</option>';
                }
                ?>
                </select>
            </td>
        </tr>
        
        <tr>
            <td class="key">Tipo de contrato:</td>
            <td>
                <select class="cajas" id="selectTipoContrato" name="selectTipoContrato" >
                	
                	<option <?php echo $empleado->tipoContrato=='base'?'selected="selected"':''?> >Base</option>
                    <option <?php echo $empleado->tipoContrato=='Eventual'?'selected="selected"':''?> >Eventual</option>
                    <option <?php echo $empleado->tipoContrato=='Confianza'?'selected="selected"':''?> >Confianza</option>
                    <option <?php echo $empleado->tipoContrato=='Sindicalizado'?'selected="selected"':''?> >Sindicalizado</option>
                    <option <?php echo $empleado->tipoContrato=='A prueba'?'selected="selected"':''?> >A prueba</option>
                </select>
            </td>
        </tr>
        
        <tr>
            <td class="key">Tipo de jornada:</td>
            <td>
                 <select class="cajas" id="selectTipoJornada" name="selectTipoJornada" >
                	<option <?php echo $empleado->tipoJornada=='Diurna'?'selected="selected"':''?> >Diurna</option>
                    <option <?php echo $empleado->tipoJornada=='Nocturna'?'selected="selected"':''?> >Nocturna</option>
                    <option <?php echo $empleado->tipoJornada=='Mixta'?'selected="selected"':''?> >Mixta</option>
                    <option <?php echo $empleado->tipoJornada=='Por hora'?'selected="selected"':''?> >Por hora</option>
                    <option <?php echo $empleado->tipoJornada=='Reducida'?'selected="selected"':''?> >Reducida</option>
                    <option <?php echo $empleado->tipoJornada=='Continuada'?'selected="selected"':''?> >Continuada</option>
                    <option <?php echo $empleado->tipoJornada=='Partida'?'selected="selected"':''?> >Partida</option>
                    <option <?php echo $empleado->tipoJornada=='Por turnos'?'selected="selected"':''?> >Por turnos</option>
                </select>
            </td>
        </tr>
        
        <tr>
            <td class="key">Periodicidad de pago:</td>
            <td>
               <select class="cajas" id="selectPeriodicidadPago" name="selectPeriodicidadPago" >
                	<option <?php echo $empleado->periodicidadPago=='Diario'?'selected="selected"':''?> >Diario</option>
                    <option <?php echo $empleado->periodicidadPago=='Semanal'?'selected="selected"':''?> >Semanal</option>
                    <option <?php echo $empleado->periodicidadPago=='Quincenal'?'selected="selected"':''?> >Quincenal</option>
                    <option <?php echo $empleado->periodicidadPago=='Catorcenal'?'selected="selected"':''?> >Catorcenal</option>
                    <option <?php echo $empleado->periodicidadPago=='Mensual'?'selected="selected"':''?> >Mensual</option>
                    <option <?php echo $empleado->periodicidadPago=='Bimestral'?'selected="selected"':''?> >Bimestral</option>
                    <option <?php echo $empleado->periodicidadPago=='Unidad de Obra'?'selected="selected"':''?> >Unidad de Obra</option>
                    <option <?php echo $empleado->periodicidadPago=='Precio Alzado'?'selected="selected"':''?> >Precio Alzado</option>
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
					$seleccionado	=$row->idRiesgo==$empleado->idRiesgo?'selected="selected"':'';
                    echo '<option '.$seleccionado.' value="'.$row->idRiesgo.'" >'.$row->nombre.'</option>';
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
					$seleccionado	=$row->idBanco==$empleado->idBanco?'selected="selected"':'';
                    echo '<option '.$seleccionado.' value="'.$row->idBanco.'" >('.$row->clave.') '.$row->nombre.'</option>';
                }
                ?>
                </select>
            </td>
        </tr>
       
        <tr>
            <td class="key">Clabe:</td>
            <td>
                <input type="text" class="cajas" id="txtClabe" name="txtClabe" value="<?php echo $empleado->clabe?>"/>
            </td>
        </tr>
        
        <tr>
            <td class="key">Email:</td>
            <td>
                <input type="text" class="cajas" id="txtEmail" name="txtEmail" maxlength="100"  value="<?php echo $empleado->email?>"/>
            </td>
        </tr>
        
        <tr>
            <td class="key">Salario diario integrado:</td>
            <td>
                <input type="text" class="cajas" id="txtSalarioDiario" name="txtSalarioDiario" value="<?php echo round($empleado->salarioDiario,2)?>" style="width:100px" maxlength="10" onkeypress="return soloDecimales(event)"   />
            </td>
        </tr>
        <tr>
            <td class="key">Salario base:</td>
            <td>
                <input type="text" class="cajas" id="txtSalarioBase" name="txtSalarioBase" value="<?php echo round($empleado->salarioBase,2)?>"  style="width:100px" maxlength="10" onkeypress="return soloDecimales(event)"  />
            </td>
        </tr>
    </table>
</form>