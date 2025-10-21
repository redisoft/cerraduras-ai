<script src="<?php echo base_url().carpetaJs?>contabilidad/polizas.js"></script>
<script src="<?php echo base_url().carpetaJs?>contabilidad/conceptos.js"></script>
<script src="<?php echo base_url().carpetaJs?>contabilidad/transacciones.js"></script>
<script src="<?php echo base_url().carpetaJs?>contabilidad/cheques.js"></script>
<script src="<?php echo base_url().carpetaJs?>contabilidad/transferencias.js"></script>
<script src="<?php echo base_url().carpetaJs?>contabilidad/comprobantes.js"></script>
<script src="<?php echo base_url().carpetaJs?>contabilidad/xml.js"></script>

<script src="<?php echo base_url().carpetaJs?>contabilidad/polizas/comprobantes.js"></script>

<script>
$(document).ready(function()
{
	obtenerPolizas();
	
	$("#txtFechaInicial,#txtFechaFinal").datepicker();
});
</script>

<div class="derecha">
<div class="submenu">
<div class="breadcumb"><?php echo isset($breadcumb)?$breadcumb:''?></div>
<div class="toolbar" id="toolbar" >
<?php

echo'

	<table class="toolbar">
		<td width="8%">
			<a onclick="formularioPolizas()">
				<img src="'.base_url().'img/agregar.png"  title="Registrar"  width="30"/>
				<br />
				Registrar
			</a>
		</td>
		
		
		
		
		<td align="center">
			<label>Buscar por fechas</label>
			<input type="text" id="txtFechaInicial" class="cajas" value="'.date('Y-01-d').'" onchange="obtenerPolizas()" style="width:100px"/>
			<input type="text" id="txtFechaFinal" class="cajas" value="'.date('Y-m-d').'" onchange="obtenerPolizas()" style="width:100px" />
			
			<input type="hidden" id="txtIdConceptoActivo" value="0" />
			
			<select class="cajas" id="selectPolizasBusqueda" name="selectPolizasBusqueda" style="width:200px" onchange="obtenerPolizas()">
				<option value="0">Tipo de póliza</option>
				<option value="1">Ingresos</option>
				<option value="2">Egresos</option>
				<option value="3">Diario</option>
			</select>
			
		</td>
		<td width="10%" align="center" style="display:none">
			<img src="'.base_url().'img/excel.png" class="botonesMenu" title="Importar excel" style="margin-right:9px" id="subirExcel"  />
			<br />
			<label>Importar</label>
		</td>
		
		
	</table>
</div>
</div>';

echo '<div id="obteniendoReporte"></div>';
$this->load->view('contabilidad/menu');

echo '
<div id="procesandoPolizas"></div>
<div class="contenidoMenuDatos" id="obtenerPolizas"></div>



<div title="Comprobantes póliza" id="ventanaFormularioPolizas">
	<div id="formularioPolizas"></div>
</div>


<div title="Registrar póliza" id="ventanaComprobantesConcepto">
	<div id="obtenerComprobantesConcepto"></div>
</div>

<div title="Editar póliza" id="ventanaEditarPoliza">
	<div id="obtenerPoliza"></div>
</div>

<div title="Conceptos de pólizas" id="ventanaConceptosPoliza">
	<div id="conceptosPoliza"></div>
</div>

<div id="ventanaFormularioConceptos" title="Registro de pólizas" >
	<div id="formularioConceptos"></div>
</div>

<div title="Editar conceptos de pólizas" id="ventanaEditarConcepto">
	<div id="obtenerConcepto"></div>
</div>

<div title="Transacciones de pólizas" id="ventanaTransacciones">
	<div id="obtenerTransacciones"></div>
</div>

<div title="Cheques" id="ventanaCheques">
	<div id="obtenerCheques"></div>
</div>

<div title="Transferencias" id="ventanaTransferencias">
	<div id="obtenerTransferencias"></div>
</div>

<div title="Comprobantes" id="ventanaComprobantes">
	<div id="obtenerComprobantes"></div>
</div>

<div id="ventanaConceptosTransaccion" title="Conceptos de transacción">
	<div id="obtenerConceptosTransaccion"></div>
</div> ';
?>
</div>


