<script>
	$('#txtFechaPago,#txtFechaInicialPago,#txtFechaFinalPago').datepicker();
</script>
<form id="frmNomina" name="frmNomina">
	
    <input type="hidden" id="txtAgregarEmpleados" value="1" />
    <input type="hidden" id="txtAgregarPercepciones" value="1" />
    <input type="hidden" id="txtAgregarDeducciones" value="1" />

	<div style="min-height:105px; overflow:auto; max-height:105px">
    <table class="admintable" id="tablaEmpleadosNomina" width="100%">
        <tr>
            <th class="encabezadoPrincipalChico" colspan="6">
            	Empleados
                
                <?php echo '<img style="margin-left:20px" src="'.base_url().'img/add.png" width="22" onclick="listaEmpleados()" title="Empleados">';?>
            </th>
        </tr>
        <tr>
        	<th width="3%">-</th>
        	<th width="27%">Nombre</th>
            <th width="15%">RFC</th>
            <th width="20%">Puesto</th>
            <th width="20%">Departamento</th>
            <th width="15%">Status</th>
        </tr>
    </table>
    </div>
    
    <table class="admintable" id="tablaFechas" width="100%">
        <tr>
            <th class="encabezadoPrincipalChico" colspan="8">Fechas</th>
        </tr>
        <tr>
        	<td class="key">Fecha de pago:</td>
            <td><input type="text" class="cajas" id="txtFechaPago" name="txtFechaPago" value="<?php echo date('Y-m-d')?>" style="width:90px"/></td>
            
            <td class="key">Fecha inicial de pago:</td>
            <td><input type="text" class="cajas" id="txtFechaInicialPago" name="txtFechaInicialPago" value="<?php echo date('Y-m-d')?>" style="width:90px" onchange="obtenerDiasTrabajados()"/></td>
            
            <td class="key">Fecha final de pago:</td>
            <td><input type="text" class="cajas" id="txtFechaFinalPago" name="txtFechaFinalPago" value="<?php echo date('Y-m-d')?>" style="width:90px" onchange="obtenerDiasTrabajados()"/></td>
            
            <td class="key">Días trabajados:</td>
            <td><input type="text" class="cajas" id="txtDiasTrabajados" name="txtDiasTrabajados" value="1" style="width:90px" readonly="readonly"/></td>
        </tr>
    </table>
    
    <table class="admintable" id="tablaDetalles" width="100%">
        <tr>
            <th class="encabezadoPrincipalChico" colspan="6">Detalles</th>
        </tr>
        <tr>
        	<td class="key">Emisor:</td>
            <td colspan="3">
            	<select style="width:550px" id="selectEmisores" name="selectEmisores" class="cajas" onchange="obtenerFolio()">
                	<option value="0">Seleccione</option>
                	<?php
                    foreach($emisores as $row)
					{
						echo '<option value="'.$row->idEmisor.'">(Serie '.$row->serie.') '.$row->rfc.', '.$row->nombre.'</option>';
					}
					?>
                </select>
            </td>
            <td class="key">Serie y folio:</td>
            <td id="obtenerFolio">Seleccionar emisor</td>
        </tr> 
        <tr>
        	<td class="key">Concepto:</td>
            <td><input type="text" class="cajas" id="txtConcepto" name="txtConcepto" value="Pago de nómina" style="width:250px"/></td>
            
            <td class="key">Forma de pago:</td>
            <td><input type="text" class="cajas" id="txtFormaPago" name="txtFormaPago" value="Pago en una sola exhibicion" style="width:250px"/></td>
            
            <td class="key">Método de pago:</td>
            <td>
            	<select style="width:150px" id="txtMetodoPago" name="txtMetodoPago" class="cajas" onchange="sugerirMetodoPago()" >
            	<?php
                	foreach($metodos as $row)
					{
						echo '<option value="'.$row->clave.'">'.$row->clave.', '.$row->concepto.'</option>';
					}
				?>
                </select>
            	<input type="hidden" class="cajas" id="txtMetodoPagoTexto" name="txtMetodoPagoTexto" value="01, Efectivo" style="width:250px"/>
            </td>
        </tr>
       
    </table>
    
    <section style="min-height:100px; overflow:auto; max-height:100px">
    	<table class="admintable" id="tablaPercepcionesNomina" width="100%">
            <tr>
                <th class="encabezadoPrincipalChico" colspan="6">
                	Percepciones
                    <?php echo '<img style="margin-left:20px" src="'.base_url().'img/add.png" width="22" onclick="listaPercepciones()" title="Agregar percepción">';?>
                </th>
            </tr>
            <tr>
            	<th width="3%">-</th>
                <th width="10%">Clave</th>
                <th width="27%">Concepto</th>
                <th width="30%">Tipo percepción</th>
                <th width="15%">Importe gravado</th>
                <th width="15%">Importe exento</th>
            </tr>
        </table>
    </section>
    
     <section style="min-height:100px; overflow:auto; max-height:100px">
    	<table class="admintable" id="tablaDeduccionesNomina" width="100%">
            <tr>
                <th class="encabezadoPrincipalChico" colspan="6">
                	Deducciones
                    <?php echo '<img style="margin-left:20px" src="'.base_url().'img/add.png" width="22" onclick="listaDeducciones()" title="Agregar deducción">';?>
                </th>
            </tr>
            <tr>
            	<th width="3%">-</th>
                <th width="10%">Clave</th>
                <th width="27%">Concepto</th>
                <th width="30%">Tipo deducción</th>
                <th width="15%">Importe gravado</th>
                <th width="15%">Importe exento</th>
            </tr>
        </table>
    </section>
    
    <table class="admintable" id="tablaImportes" width="100%">
        <tr>
            <th class="encabezadoPrincipalChico" colspan="6">Importes</th>
        </tr>
        <tr>
        	<td class="key">Percepciones:</td>
            <td>
            	<input type="text" class="cajas" id="txtPercepciones" name="txtPercepciones" value="0.00" style="width:100px" readonly="readonly"/>
                <input type="hidden" id="txtTotalGravadoPercepciones" name="txtTotalGravadoPercepciones" value="0" />
                <input type="hidden" id="txtTotalExentoPercepciones" name="txtTotalExentoPercepciones" value="0" />
                <input type="hidden" id="txtNumeroPercepciones" name="txtNumeroPercepciones" value="0" />
            </td>
            
            <td class="key">Deducciones:</td>
            <td>
            	<input type="text" class="cajas" id="txtDeducciones" name="txtDeducciones" value="0.00" style="width:100px" readonly="readonly"/>
                <input type="hidden" id="txtTotalIsr" name="txtTotalIsr" value="0" />
                <input type="hidden" id="txtTotalGravadoDeducciones" name="txtTotalGravadoDeducciones" value="0" />
                <input type="hidden" id="txtTotalExentoDeducciones" name="txtTotalExentoDeducciones" value="0" />
                <input type="hidden" id="txtNumeroDeducciones" name="txtNumeroDeducciones" value="0" />
            </td>

            <td class="key">Total:</td>
            <td><input type="text" class="cajas" id="txtTotales" name="txtTotales" value="0.00" style="width:100px" readonly="readonly"/></td>
        </tr>
    </table>
    
    
</form>