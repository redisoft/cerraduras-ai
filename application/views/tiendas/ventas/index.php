<script src="<?php echo base_url()?>js/ventas/catalogo/ventas.js"></script>

<script src="<?php echo base_url()?>js/tiendas/ventasTiendas.js"></script>
<script src="<?php echo base_url()?>js/tiendas/corte.js"></script>
<script src="<?php echo base_url()?>js/clientes/catalogo.js"></script>
<script src="<?php echo base_url()?>js/facturacion/folios.js"></script>
<script src="<?php echo base_url()?>js/ventas/ventas.js"></script>
<script src="<?php echo base_url()?>js/ventas/ventasFacturas.js"></script>
<script src="<?php echo base_url()?>js/cotizaciones/descuentos.js" ></script>
<script src="<?php echo base_url()?>js/ventas/sucursales.js"></script>

<script src="<?php echo base_url()?>js/productos/envios.js"></script>
<script src="<?php echo base_url()?>js/ventas/faltantesTraspasos.js"></script>

<div class="derecha">
<div class="submenu">

<div class="toolbar" id="toolbar">
 <table class="toolbar" width="100%">
 	<tr>
    	<td class="seccion" colspan="3">
    		Ventas - <?php echo $tienda->nombre?>
   	    </td>
    </tr>
    <tr>
    
    	<?php
		#if($permiso->escribir=='1')
		{
            echo'
			<td width="10%" align="left" valign="middle" style="border:none" >
				<a onclick="formularioVentas(\'tiendas\')">
					<img src="'.base_url().'img/ventas.png" width="30px;" height="30px;"  style="cursor:pointer;" title="Registrar venta">
					<br />
					Punto de venta    
				</a>
             </td>
			 
			 <td width="10%" align="left" valign="middle" style="border:none" >
			 	<a onclick="formularioCorte()">
					<img src="'.base_url().'img/dinero.png" width="30px;" height="30px;"  style="cursor:pointer;" title="Corte de caja">
					<br />
					Corte caja  
				</a>  
             </td>';
		}
        ?>
        
        <td width="10%" align="left" valign="middle"  >
			<?php 
            echo '
			<a onclick="obtenerEnvios()">
				<img src="'.base_url().'img/envios.png" width="30px;" height="30px;" class="envios"  style="cursor:pointer;" title="Envíos a tiendas">
				<br />
				Traspasos entre tiendas
			</a>'; 
            ?>  
        </td>
        
       	<td width="80%" align="left" valign="middle" style=" padding-right:120px">
			<input type="text"  name="txtCriterio" id="txtCriterio" class="busquedas" placeholder="Buscar por orden, cliente"  style="width:400px; "/>
            <input type="hidden"  name="txtIdTienda" id="txtIdTienda" value="<?php echo $tienda->idTienda?>"/>
        </td>
    </tr>
 </table>
 </div>
</div>

<div class="listproyectos">
<div id="procesandoVentas"></div>
<div id="obtenerVentas" style="margin-top:10px"></div>

<div id="ventanaVentas" title="Punto de venta">
<div class="ui-state-error" ></div>
<div id="formularioVentas"></div>
</div>

<div id="ventanaClientes" title="Registrar cliente">
<div id="cargandoClientes"></div>
<div class="ui-state-error" ></div>
<div id="formularioClientes"></div>
</div>

<div id="ventanaCobrosVenta" title="Cobrar venta">
<div id="registrandoCobroVenta"></div>
<div id="formularioCobros"></div>
</div>

<div id="ventanaFacturacion" title="Facturar venta">
<div id="facturando"></div>
<div id="errorFacturacion" class="ui-state-error" ></div>
<div id="obtenerDatosFactura"></div>
</div>

<div id="ventanaFacturaParcial" title="Facturar parcial">
<div id="facturandoParcial"></div>
<div id="facturaParcial"></div>
</div>

<div id="ventanaStockSucursales" title="Stock sucursales">
	<div id="obtenerStockSucursales"></div>
</div>

<div id="ventanaCortes" title="Corte de caja">
<div id="registrandoCorte"></div>
<div class="ui-state-error" ></div>
<div id="formularioCorte"></div>
</div>

<div id="ventanaEnvios" title="Traspasos de productos entre tiendas">
<div class="ui-state-error" ></div>
<div id="procesandoReportes"></div>
<table class="admintable" width="100%">
    <tr>
    	<td width="14%" align="center" style="border:none">
        <?php
		echo '<img onclick="obtenerProductosEnvio()" src="'.base_url().'img/traspasos.png" width="30px;" height="30px;" id="subirFichero" style="cursor:pointer;" title="Traspasos">
		<br />
		<a>Registrar traspaso</a>';
        ?>
        </td>
    	<td align="center" style="border:none">
        
        	<script>
			$('#txtFechaInicial,#txtFechaFinal').datepicker();
			</script>
        	<input type="text" class="cajas"  id="txtCriterio" onkeyup="obtenerEnvios()" style="width:350px" placeholder="Buscar por producto, folio"  />
            
            <input type="text" class="cajas"  id="txtFechaInicial" onchange="obtenerEnvios()" style="width:90px" value="<?php echo date('Y-m-01')?>" />
            <input type="text" class="cajas"  id="txtFechaFinal" onchange="obtenerEnvios()" style="width:90px" value="<?php echo date('Y-m-d')?>" />

            </td>
    </tr>
</table>
<div id="obtenerEnvios"></div>
</div>

<div id="ventanaProductosEnvio" title="Productos para envío">
<div class="ui-state-error" ></div>
<div id="registrandoEnvio"></div>
<table class="admintable" width="100%">
    <tr>
    	<td align="center">
        	<input type="text" class="cajas"  id="txtBuscarProducto" style="width:350px"  placeholder="Buscar producto" />
            &nbsp;&nbsp;
        	<select class="cajas" id="selectTiendasEnvio" >
                <?php
				echo '<option value="'.$tienda->idTienda.'">'.$tienda->nombre.'</option>';
                ?>
            </select>
        </td>
    </tr>
</table>
<div id="obtenerProductosEnvio" style="overflow:scroll; height:460px; overflow-x: hidden"></div>
</div>

<div id="ventanaInventarioFaltante" title="Productos con inventario faltante">
    <div id="procesandoInventarioFaltante"></div>
    <div class="ui-state-error" ></div>
	<div id="formularioInventarioFaltante"></div>
</div>

<div id="ventanaAsignarDescuento" title="Asignar descuento">
	<table class="admintable" width="100%">
    	<tr>
        	<td class="key">Descuento:</td>
            <td><input type="text" class="cajas" id="txtAsignarDescuento" value="0" onkeypress="return soloDecimales(event)" maxlength="6" /></td>
        </tr>
    </table>
</div>

</div>
</div>
