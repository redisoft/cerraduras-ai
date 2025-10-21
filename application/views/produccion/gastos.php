<script src="<?php echo base_url()?>js/administracion.js"></script>
<script>

function buscarCuentas()
{
	div = document.getElementById('listaBancos');
	idBanco=div.value;
	
	$("#cargarCuenta").load(base_url+"ficha/obtenerCuentas/"+idBanco);
}

function obtenerCuentas()
{
	$("#filaCuenta").load(base_url+"produccion/obtenerCuentas/"+$('#selectBancos').val());
}
	
$(document).ready(function()
{
	reporteBancos(); 
});
	
</script>

<div class="derecha">
<div class="submenu">
<div class="toolbar" id="toolbar">
<div class="seccionDiv">
Gastos administrativos
</div>
 <table class="toolbar" width="100%" >
    <tr>
    
     <?php
		if($permiso->escribir=='1')
		{
			echo'
			<td align="center" valign="middle" style="border:none">
				<img src="'.base_url().'img/ingresos.png" width="30px;" height="30px;" 
				id="btnOtrosIngresos" style="cursor:pointer;" title="Ingresos" />
				<br />
				Ingresos  
			</td>';
			
			echo'
			<td align="center" valign="middle" style="border:none">
				<img src="'.base_url().'img/egresos.png" width="30px;" height="30px;" 
				id="btnOtrosEgresos" style="cursor:pointer;" title="Gastos" />
				<br />
				Gastos  
			</td>';
			
			echo'
			<td align="center" valign="middle" style="border:none">
				<img src="'.base_url().'img/traspasos.png" width="30px;" height="30px;" 
				id="btnTraspasos" style="cursor:pointer;" title="Traspasos" />
				<br />
				Traspasos  
			</td>';

		}
		?>
          <td width="35%">
        
        Reporte bancos &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        Cuenta: 
        <select class="cajas" id="selectCuentaBancos" style="width:auto" onchange="reporteBancos()">
        	<option value="0">Todas</option>
        <?php
			foreach($cuentasBanco as $row)
			{
				echo '<option value="'.$row->idCuenta.'">'.$row->cuenta.', '.$row->nombre.'</option>';
			}
        ?>
        </select>
        </td>

        
        <td width="35%" style="text-align:left">
        Mes:
        <input onchange="reporteBancos()" type="text" class="cajas" id="txtMesReporte" style="width:80px" readonly="readonly" value="<?php echo date('Y-m')?>" /> 
        
        </td>
	</tr>
</table>
 </div>
</div>
<div class="listproyectos">

<input type="hidden" id="txtIvaSesion" value="<?php echo $this->session->userdata('iva')?>" />
<div id="obtenerReporteBancos"></div>

<!-- OTROS INGRESOS-->
<div id="ventanaOtrosIngresos" title="Ingresos">
<div style="width:100%;" id="cargandoIngresos"></div>
<div class="ui-state-error" ></div>
<table class="admintable" width="100%">
	<tr>
    	<td>
        	<input type="text" class="cajas" id="txtBuscarIngreso" onkeyup="obtenerOtrosIngresos()" placeholder="Buscar ingreso" style="width:400px" />
            
            <input type="text" class="cajas" id="txtBuscarIngresoFecha" onchange="obtenerOtrosIngresos()" placeholder="Fecha" style="width:120px" />
            <script>
				$('#txtBuscarIngresoFecha').datepicker();
			</script>
            <select class="cajas" id="selectCuentaIngresos" style="width:auto" onchange="obtenerOtrosIngresos()">
        	<option value="0">Seleccione cuenta</option>
        <?php
			foreach($cuentasBanco as $row)
			{
				echo '<option value="'.$row->idCuenta.'">'.$row->cuenta.', '.$row->nombre.'</option>';
			}
        ?>
        </select>
        </td>
    </tr>
</table>
<div id="obtenerOtrosIngresos"></div>
</div>

<div id="ventanaFormularioIngresos" title="Agregar ingresos">
<div style="width:100%;" id="agregandoIngresos"></div>
<div id="formularioIngresos"></div>
</div>

<div id="ventanaEditarIngresos" title="Editar ingresos">
<div style="width:100%;" id="editandoIngresos"></div>
<div id="obtenerIngresoEditar"></div>																			
</div>

<div id="ventanaFormularioDepartamentos" title="Agregar departamento">
<div style="width:100%;" id="agregandoDepartamento"></div>
<div id="formularioDepartamentos"></div>
</div>

<div id="ventanaFormularioNombres" title="Agregar nombre">
<div style="width:100%;" id="agregandoNombre"></div>
<div id="formularioNombres"></div>
</div>

<div id="ventanaFormularioProductos" title="Agregar producto">
<div style="width:100%;" id="agregandoProducto"></div>
<div id="formularioProductos"></div>
</div>
                                                    
<div id="ventanaFormularioGastos" title="Tipo de gasto">
<div style="width:100%;" id="agregandoGasto"></div>
<div id="formularioGastos"></div>
</div>


<!-- OTROS EGRESOS-->
<div id="ventanaOtrosEgresos" title="Gastos">
<div style="width:100%;" id="cargandoEgresos"></div>
<div class="ui-state-error" ></div>
<table class="admintable" width="100%">
	<tr>
    	<td>
        	<input type="text" class="cajas" id="txtBuscarEgreso" onkeyup="obtenerOtrosEgresos()" placeholder="Buscar egreso" style="width:400px" />
            
            <input type="text" class="cajas" id="txtBuscarEgresoFecha" onchange="obtenerOtrosEgresos()" placeholder="Fecha" style="width:120px" />
            <script>
				$('#txtBuscarEgresoFecha').datepicker();
			</script>
            <select class="cajas" id="selectCuentaEgresos" style="width:auto" onchange="obtenerOtrosEgresos()">
        	<option value="0">Seleccione cuenta</option>
			<?php
                foreach($cuentasBanco as $row)
                {
                    echo '<option value="'.$row->idCuenta.'">'.$row->cuenta.', '.$row->nombre.'</option>';
                }
            ?>
            </select>
        </td>
    </tr>
</table>


<div id="obtenerOtrosEgresos"></div>
</div>

<div id="ventanaFormularioEgresos" title="Agregar gasto">
<div style="width:100%;" id="agregandoEgresos"></div>
<div id="formularioEgresos"></div>
</div>

<div id="ventanaEditarEgresos" title="Editar gasto">
<div style="width:100%;" id="editandoEgresos"></div>
<div id="obtenerEgresoEditar"></div>
</div>

<!-- TRASPASOS-->
<div id="ventanaTraspasos" title="Traspasos">
<div style="width:100%;" id="agregandoTrapasos"></div>
<div id="obtenerTraspasos"></div>
</div>

<div id="ventanaFormularioTraspasos" title="Agregar Traspaso">
<div style="width:100%;" id="agregandoTrapasos"></div>
<div id="formularioTraspasos"></div>
</div>

<div id="ventanaCajaChica" title="Caja chica">
<div style="width:100%;" id="cargandoCajaChica"></div>
<div id="obtenerCajaChica"></div>
</div>

<div id="ventanaAgregarCajaChica" title="Registrar caja chica">
<div style="width:100%;" id="agregandoCajaChica"></div>
<div id="formularioCajaChica"></div>
</div>

<div id="ventanaEditarCajaChica" title="Editar caja chica">
<div style="width:100%;" id="editandoCajaChica"></div>
<div id="obtenerCajaChicaEditar"></div>
</div>

<div id="ventanaComprobantes" title="Comprobantes">
<div id="registrandoComprobante"></div>
<div id="obtenerComprobantes"></div>
</div>

<div id="ventanaComprobantesEgresos" title="Comprobantes egresos">
<div id="registrandoComprobanteEgreso"></div>
<div id="obtenerComprobantesEgresos"></div>
</div>

<div id="ventanaFormularioClientes" title="Agregar cliente">
<div id="registrandoCliente"></div>
<div id="formularioClientes"></div>
</div>

<div id="ventanaFormularioProveedores" title="Agregar proveedor">
<div id="registrandoProveedor"></div>
<div id="formularioProveedores"></div>
</div>

</div>
</div>

