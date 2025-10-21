<script src="<?php echo base_url()?>js/administracion.js"></script>
<script src="<?php echo base_url()?>js/administracion/comprobantesEgresos.js"></script>
<script src="<?php echo base_url()?>js/administracion/ingresos/excel.js"></script>
<script src="<?php echo base_url()?>js/administracion/ingresos/cfdi.js"></script>
<script src="<?php echo base_url()?>js/administracion/ingresos/ingresos.js"></script>
<script src="<?php echo base_url()?>js/administracion/egresos/excel.js"></script>
<script src="<?php echo base_url()?>js/administracion/egresos/egresos.js"></script>
<script src="<?php echo base_url()?>js/administracion/calculos.js"></script>
<script src="<?php echo base_url()?>js/administracion/sie.js"></script>
<script src="<?php echo base_url()?>js/administracion/niveles/catalogo.js"></script>

<script src="<?php echo base_url()?>js/facturacion/folios.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>js/reportes/facturacion/administracion.js"></script>



<script>
$(document).ready(function()
{
	reporteBancos(); 
});
	
</script>

<div class="derecha">
<div class="submenu">
<div class="breadcumb"><?php echo isset($breadcumb)?$breadcumb:''?></div>
<div class="toolbar" id="toolbar">
<!--<div class="seccionDiv">
	Contabilidad
</div>-->
 <table class="toolbar" width="100%" >
    <tr>
    
     <?php
	 	echo'
		<td align="center" valign="middle" style="border:none" width="5%">
			<a id="btnOtrosIngresos" onclick="formularioListaIngresos()" >
				<img src="'.base_url().'img/ingresos.png" width="30px;" height="30px;"  style="cursor:pointer;" title="Ingresos" />
				<br />
				Ingresos  
			</a>
		</td>';
		
		echo'
		<td align="center" valign="middle" style="border:none" width="5%">
			<a id="btnOtrosEgresos" onclick="formularioListaEgresos()">
				<img src="'.base_url().'img/egresos.png" width="30px;" height="30px;" style="cursor:pointer;" title="Egresos" />
				<br />
				Egresos  
			</a>
		</td>
		
		<td class="button" width="8%">
			<a id="btnImportarEgresos" onclick="formularioImportarEgresos()">
				<img src="'.base_url().'img/excel.png" width="30px;" height="30px;" title="Importar" alt="Importar" /><br />
				Importar egresos 
			</a>      
		</td>';
		
		echo'
		<td align="center" valign="middle" style="border:none" width="8%">
			<a id="btnTraspasos" onclick="obtenerListaTraspasos()">
				<img src="'.base_url().'img/traspasos.png" width="30px;" height="30px;" style="cursor:pointer;" title="Traspasos" />
				<br />
				Traspasos  
			</a>
		</td>
		
		<td align="center" valign="middle" style="border:none" width="7%">
			<a id="btnEfectivo" onclick="formularioEfectivo()">
				<img src="'.base_url().'img/coins.png" width="30px;" height="30px;" style="cursor:pointer;" title="Efectivo" />
				<br />
				Efectivo  
			</a>
		</td>
		
		<td align="center" valign="middle" style="border:none" width="7%">
			<a id="btnEfectivo" onclick="formularioCuentas()">
				<img src="'.base_url().'img/cobros.png" width="30px;" height="30px;" style="cursor:pointer;" title="Efectivo" />
				<br />
				Cuentas  
			</a>
		</td>
		
		<td align="center" valign="middle" style="border:none" width="7%">
			<a id="btnEfectivo" onclick="formularioNoDisponible()">
				<img src="'.base_url().'img/paypal.png" width="30px;" height="30px;" style="cursor:pointer;" title="Efectivo" />
				<br />
				No disponible  
			</a>
		</td>';
		
		if($permiso[0]->activo==0)
		{
			 echo '
			<script>
				desactivarBotonSistema(\'btnOtrosIngresos\');
				desactivarBotonSistema(\'btnOtrosEgresos\');
				desactivarBotonSistema(\'btnTraspasos\');
				desactivarBotonSistema(\'btnEfectivo\');
			</script>';
		}
		?>
          <td width="30%">
        
        Reporte bancos &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        Cuenta: 
        <select class="cajas" id="selectCuentaBancos" style="width:200px" onchange="reporteBancos()">
        	<!--<option value="0">Todas</option>-->
        <?php
			foreach($cuentasBanco as $row)
			{
				echo '<option '.($row->dashboard=='1'?'selected="selected"':'').' value="'.$row->idCuenta.'">'.(strlen($row->cuenta)>0?$row->cuenta:$row->tarjetaCredito).', '.$row->nombre.'</option>';
			}
        ?>
        </select>
        </td>

        
        <td width="10%" style="text-align:left">
        Mes:
        <input onchange="reporteBancos()" type="text" class="cajas" id="txtMesReporte" style="width:80px" readonly="readonly" value="<?php echo date('Y-m')?>" /> 
        
        </td>
        
        <td width="20%" style="text-align:left">
       	<label id="lblSaldoFinal">Saldo final: $0.00</label>
        
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
        	<input type="text" class="cajas" id="txtBuscarIngreso" placeholder="<?php echo sistemaActivo=='IEXE'?'Por por descripción, Alumno/cliente':'Buscar ingreso'?>" style="width:400px" />
            
            <input type="text" class="cajas" id="txtInicioIngresoFecha" onchange="obtenerOtrosIngresos()" placeholder="Fecha" style="width:120px" value="<?php echo date('Y-m-01')?>" />
            
            <input type="text" class="cajas" id="txtFinIngresoFecha" onchange="obtenerOtrosIngresos()" placeholder="Fecha" style="width:120px" value="<?php echo date('Y-m-d')?>" />
            
            <script>
				$('#txtInicioIngresoFecha,#txtFinIngresoFecha').datepicker();
				
				$(document).ready(function()
				{
					$("#txtBuscarIngreso").keyup(function() 
					{
						clearTimeout(tiempoRetraso);
						milisegundos 	= 500; // milliseconds
						tiempoRetraso 	= setTimeout(function() 
						{
							obtenerOtrosIngresos();
						}, milisegundos);
					});
				});
			</script>
            <select class="cajas" id="selectCuentaIngresos" style="width:auto" onchange="obtenerOtrosIngresos()">
        	<option value="0">Seleccione cuenta</option>
        <?php
			foreach($cuentasBanco as $row)
			{
				echo '<option value="'.$row->idCuenta.'">'.(strlen($row->cuenta)>0?$row->cuenta:$row->tarjetaCredito).', '.$row->nombre.'</option>';
			}
        ?>
        </select>
        </td>
    </tr>
</table>
<div id="obtenerOtrosIngresos">
	<input type="hidden" value="0" id="selectProductosBusqueda" />
    <input type="hidden" value="0" id="selectDepartamentosBusqueda" />
    <input type="hidden" value="0" id="selectGastosBusqueda" />
</div>
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
                                                    
<div id="ventanaFormularioGastos" title="Tipo">
<div style="width:100%;" id="agregandoGasto"></div>
<div id="formularioGastos"></div>
</div>


<!-- OTROS EGRESOS-->
<div id="ventanaOtrosEgresos" title="Egresos">
<div style="width:100%;" id="cargandoEgresos"></div>
<div class="ui-state-error" ></div>
<table class="admintable" width="100%">
	<tr>
    	<td>
        	<input type="text" class="cajas" id="txtBuscarEgreso" placeholder="Buscar egreso" style="width:400px" />
            
            <input type="text" class="cajas" id="txtInicioEgresoFecha" onchange="obtenerOtrosEgresos()" placeholder="Fecha" style="width:120px" value="<?php echo date('Y-m-01')?>" />
            <input type="text" class="cajas" id="txtFinEgresoFecha" onchange="obtenerOtrosEgresos()" placeholder="Fecha" style="width:120px" value="<?php echo date('Y-m-d')?>"/>
            <script>
				$('#txtInicioEgresoFecha,#txtFinEgresoFecha').datepicker();
				$(document).ready(function()
				{
					$("#txtBuscarEgreso").keyup(function() 
					{
						clearTimeout(tiempoRetraso);
						milisegundos 	= 500; // milliseconds
						tiempoRetraso 	= setTimeout(function() 
						{
							obtenerOtrosEgresos();
						}, milisegundos);
					});
				});
			</script>
            <select class="cajas" id="selectCuentaEgresos" style="width:auto" onchange="obtenerOtrosEgresos()">
        	<option value="0">Seleccione cuenta</option>
			<?php
                foreach($cuentasBanco as $row)
                {
                    echo '<option value="'.$row->idCuenta.'">'.(strlen($row->cuenta)>0?$row->cuenta:$row->tarjetaCredito).', '.$row->nombre.'</option>';
                }
            ?>
            </select>
            
            <input type="button" class="btn" onclick="obtenerOtrosEgresos()" value="Buscar" style="line-height: 10px" />
        </td>
    </tr>
</table>


<div id="obtenerOtrosEgresos">
	<input type="hidden" value="0" id="selectProductosBusquedaEgreso" />
    <input type="hidden" value="0" id="selectDepartamentosBusquedaEgreso" />
    <input type="hidden" value="0" id="selectGastosBusquedaEgreso" />
    <input type="hidden" value="0" id="selectPersonalBusqueda" />
</div>
</div>

<div id="ventanaFormularioEgresos" title="Agregar egreso">
<div style="width:100%;" id="agregandoEgresos"></div>
<div id="formularioEgresos"></div>
</div>

<div id="ventanaEditarEgresos" title="Editar egreso">
<div style="width:100%;" id="editandoEgresos"></div>
<div id="obtenerEgresoEditar"></div>
</div>

<!-- TRASPASOS-->
<div id="ventanaTraspasos" title="Traspasos">
<div style="width:100%;" id="procesandoTraspasos"></div>
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


<input type="hidden" id="txtModuloCfdi" value="administracion" />
<input type="hidden" id="txtOrdenReporte" value="asc" />


<div id="ventanaFacturaIngreso" title="Facturar ingreso">
    <div id="facturandoIngreso"></div>
    <div id="formularioFacturaIngreso"></div>
</div>

<div id="ventanaDatosFiscales" title="Datos fiscales">
    <div id="editandoFiscales"></div>
    <div id="obtenerDatosFiscales"></div>
</div>

<div id="ventanaEnviarCorreoFactura" title="Enviar factura por correo electrónico">
    <div id="enviandoCorreoFactura"></div>
    <div class="ui-state-error" ></div>
    <div id="formularioCorreoFactura"></div>
</div>

<div id="ventanaEfectivo" title="SIE">
    <div id="editandoEfectivo"></div>
    <div id="formularioEfectivo"></div>
</div>

<div id="ventanaImportarEgresos" title="Importar egresos">
    <div id="importandoEgresos"></div>
    <div id="formularioImportarEgresos"></div>
</div>

<?php
$this->load->view('administracion/niveles/nivel1/modales');
$this->load->view('administracion/niveles/nivel2/modales');
$this->load->view('administracion/niveles/nivel3/modales');
?>

</div>
</div>

