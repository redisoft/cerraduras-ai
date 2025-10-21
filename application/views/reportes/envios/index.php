<script src="<?php echo base_url()?>js/ventas/catalogo/ventas.js?v=<?php echo(rand());?>"></script>
<script src="<?php echo base_url()?>js/reportes/entregas/envios.js?v=<?php echo(rand());?>"></script>
<script src="<?php echo base_url()?>js/reportes/entregas/reporte.js?v=<?php echo(rand());?>"></script>
<script src="<?php echo base_url()?>js/reportes/entregas/inventario.js?v=<?php echo(rand());?>"></script>
<script src="<?php echo base_url()?>js/informacion.js?v=<?php echo(rand());?>"></script>
<script src="<?php echo base_url()?>js/facturacion/folios.js?v=<?php echo(rand());?>"></script>

<form id="frmEnvios" action="<?=base_url()?>reportes/ticketEnvios" target="_blank" method="post">
<div class="derecha">
<div class="submenu">
<div class="breadcumb"><?php echo isset($breadcumb)?$breadcumb:''?></div>
<div class="toolbar">

	<table class="toolbar" width="100%">
        <tr>
			<td width="5%" class="toolbar" onclick="obtenerReporteEntregas()">
				<a><img id="btnExportarPdfReporte" src="<?php echo base_url()?>img/envios.png" width="22" title="PDF"  />
				<br />
				Reporte</a>
			</td>
			<td width="5%" class="toolbar" onclick="obtenerReporteInventario()">
				<a><img id="btnExportarPdfReporte" src="<?php echo base_url()?>img/devolucion.png" width="22" title="PDF"  />
				<br />
				Inventario</a>
			</td>

			<td width="5%" class="toolbar" onclick="formularioRegistroEnvio()">
				<a><img id="btnExportarPdfReporte" src="<?php echo base_url()?>img/devolucion.png" width="22" title="PDF"  />
				<br />
				Envío</a>
			</td>
            <td>
				
				
				<select name="selectFechas" id="selectFechas" class="busquedas" onchange="obtenerReporte()" style="width: 150px">
					<option value="0">Fecha venta</option>
					<option value="1">Fecha entrega</option>
				</select>
				
				<input name="FechaDia" type="text" title="Inicio" style="width:120px" id="FechaDia" class="busquedas" placeholder="Fecha inicio" value="<?=date('Y-m-d')?>" />
				<input name="FechaDia2" type="text" title="Fin" id="FechaDia2" style="width:120px" class="busquedas" placeholder="Fecha fin" value="<?=date('Y-m-d')?>" />
				<input type="button" class="btn" value="Buscar" onclick="obtenerReporte()"  />    


				<input type="text"  name="txtCriterioBusqueda" id="txtCriterioBusqueda" class="busquedas"  style="width:250px;" placeholder="Buscar por cliente, nota"/>

				<input type="text"  name="txtCriterioFolio" id="txtCriterioFolio" class="busquedas"  style="width:250px;" placeholder="Buscar folio"/>
              
				<select name="selectCobrados" id="selectCobrados" class="busquedas" onchange="obtenerReporte()" style="width: 200px">
					<option value="0">Cobrados y no cobrados</option>
					<option value="1">Cobrados</option>
					<option value="2">No cobrados</option>
				</select>
				
            </td>
        </tr>
    </table>
</div>
</div>

<div class="listproyectos" style="margin-top:25px">
	<div id="generandoReporte"></div>
	
	<div id="obtenerReporte">
		<input type="hidden" id="selectRutas" value="0" />
		<input type="hidden" id="selectChofer" value="0" />
	</div>

	</div>
</div>


<div id="ticketReporte" style="display: none"></div>

<div id="ventanaVentasInformacion" title="Detalles de venta">
<div id="obtenerVentaInformacion"></div>
</div>

<div id="ventanaCorreo" title="Enviar orden de venta por correo">
	<div id="enviandoCorreo"></div>
	<div id="formularioCorreo"></div>
</div>
	
<div id="ventanaEnvios" title="Envíos">
	<div id="generandoTicket"></div>

	<table class="admintable" width="100%">
        <tr>
            <td class="key">Chofer:</td>
			<td>
				<select name="selectPersonal" id="selectPersonal" class="cajas" style="width: 200px">
					<option value="0">Seleccione</option>
					<?php
					foreach($personal as $row)
					{
						echo '<option value="'.$row->idPersonal.'">'.$row->nombre.'</option>';
					}
					?>
				</select>
			</td>
		</tr>
		
		<tr>
            <td class="key">Vehículo:</td>
			<td>
				<select name="selectVehiculo" id="selectVehiculo" class="cajas" style="width: 200px">
					<option value="0">Seleccione</option>
					<?php
					foreach($vehiculos as $row)
					{
						echo '<option value="'.$row->idVehiculo.'">'.$row->modelo.', '.$row->marca.'</option>';
					}
					?>
				</select>
			</td>
		</tr>
	</table>
</div>


<div id="ventanaReporteEntregas" title="Reporte envíos">
	<div id="procesandoReporte"></div>
	<div>
		De
		<input name="txtFechaInicio" type="text" title="Inicio" style="width:90px" id="txtFechaInicio" class="busquedas" placeholder="Fecha inicio" value="<?=date('Y-m-d')?>"/>
		a
		<input name="txtFechaFin" type="text" title="Fin" id="txtFechaFin" style="width:90px" class="busquedas" placeholder="Fecha fin" value="<?=date('Y-m-d')?>" />

		<select name="selectPersonalReporte" id="selectPersonalReporte" class="cajas" style="width: 150px" onchange="obtenerReporteEntregas()">
			<option value="0">Chofer</option>
			<?php
			foreach($personal as $row)
			{
				echo '<option value="'.$row->idPersonal.'">'.$row->nombre.'</option>';
			}
			?>
		</select>

		<select name="selectVehiculoReporte" id="selectVehiculoReporte" class="cajas" style="width: 150px" onchange="obtenerReporteEntregas()">
			<option value="0">Vehículo</option>
			<?php
			foreach($vehiculos as $row)
			{
				echo '<option value="'.$row->idVehiculo.'">'.$row->modelo.', '.$row->marca.'</option>';
			}
			?>
		</select>

		<input type="text"  name="txtCriterioReporte" id="txtCriterioReporte" class="busquedas"  style="width:380px;" placeholder="Buscar por folio"/>


		<input type="button" class="btn" value="Buscar" onclick="obtenerReporteEntregas()"  />  
              
	</div>

	<div id="obtenerReporteEntregas"></div>
</div>


<div id="ventanaReporteInventario" title="Inventario entregas">
	<div id="procesandoReporteInventario"></div>
	<div>
		De
		<input id="txtFechaInicioInventario" type="text" title="Inicio" style="width:110px" class="busquedas" placeholder="Fecha inicio" value="<?=date('Y-m-d')?>" onchange="obtenerReporteInventario()"/>
		a
		<input id="txtFechaFinInventario" type="text" title="Fin" style="width:110px" class="busquedas" placeholder="Fecha fin" value="<?=date('Y-m-d')?>" onchange="obtenerReporteInventario()"/>

		<input type="text"  name="txtBuscarProducto" id="txtBuscarProducto" class="busquedas"  style="width:200px;" placeholder="Buscar por codigo, producto"/>

		<input type="text"  name="txtBuscarOrden" id="txtBuscarOrden" class="busquedas"  style="width:200px;" placeholder="Buscar por nota"/>

		<input type="text"  name="txtBuscarFolioTicket" id="txtBuscarFolioTicket" class="busquedas"  style="width:200px;" placeholder="Buscar por folio"/>
              
	</div>

	<div id="obtenerReporteInventario"></div>
</div>

<div id="ventanaRegistroEnvios" title="Registrar envío">
	<div id="generandoEnvios"></div>
	<div id="formularioRegistroEnvio"></div>
	
</div>


<?php $this->load->view('facturacion/traslado/modales');
$this->load->view('ventas/entregas/modales');
echo '<input type="hidden" id="txtModuloTraslado" value="envios" />
<input type="hidden" id="txtModuloEnvios" value="envios" />';	
?>
	
</form>

