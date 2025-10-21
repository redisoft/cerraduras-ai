<script src="<?php echo base_url()?>js/bibliotecas/barcode.js?v=<?php echo(rand());?>"></script>
<script src="<?php echo base_url()?>js/productos/productos.js?v=<?php echo(rand());?>"></script>
<script src="<?php echo base_url()?>js/productos/importar.js?v=<?php echo(rand());?>"></script>
<script src="<?php echo base_url()?>js/productos/actualizar.js?v=<?php echo(rand());?>"></script>

<script src="<?php echo base_url()?>js/productos/impuestos.js?v=<?php echo(rand());?>"></script>
<script src="<?php echo base_url()?>js/lineas/lineas.js?v=<?php echo(rand());?>"></script>

<!-- CONTABILIDAD -->
<script src="<?php echo base_url()?>js/contabilidad/asociarCuentas.js?v=<?php echo(rand());?>"></script>

<script src="<?php echo base_url()?>js/productos/porcentaje.js?v=<?php echo(rand());?>"></script>

<script>
$(document).ready(function()
{
	obtenerProductos();
	
	$('#txtFechaInicial,#txtFechaFinal').datepicker();
});
</script>


<div class="derecha">
<div class="submenu">
<div class="breadcumb"><?php echo isset($breadcumb)?$breadcumb:''?></div>
<div class="toolbar" id="toolbar">
 <table class="toolbar" width="100%">
 	<!--<tr>
    	<td class="seccion" colspan="2">
    	Catálogo de productos
   	    </td>
    </tr>-->
    <tr>
    
    	<?php
		$tiendaLocal='0';
		if($tiendaLocal=='0')
		{
			echo '
			<td width="8%" align="left" valign="middle" style="border:none"  >
				<a id="btnAgregarProducto" onclick="formularioProductos()">
					<img src="'.base_url().'img/productos.png" width="30px;" height="30px;" style="cursor:pointer;" title="Añadir nuevo producto">
					<br />
					Nuevo producto    
				</a>
			 </td>

			 <td width="8%" align="left" valign="middle"  >
				<a id="btnTraspasos" onclick="obtenerTraspasos()">
					<img src="'.base_url().'img/envios.png" width="30px;" height="30px;" class="envios"   style="cursor:pointer;" title="Traspasos a tiendas">
					<br />
					Traspasos entre tiendas
				</a>
			</td>

			<td width="8%" align="left" valign="middle" >
				<img src="'.base_url().'img/printer.png" width="30px;" height="30px;" class="envios" onclick="imprimirProductos()" style="cursor:pointer;" title="Imprimir">
				<br />
				Imprimir PDF
			 </td>';

			echo '
			<td class="button" width="8%" style="display: none" >
				<a id="btnImportar" class="toolbar" onclick="accesoImportarProductos()">
					<img src="'.base_url().'img/excel.png" width="30px;" height="30px;" title="Importar" alt="Importar" /><br />
					Importar  
				</a>      
			</td>

			<td class="button" width="8%">
				<a id="btnExportar" class="toolbar" onclick="exportarProductos()">
					<img src="'.base_url().'img/excel.png" width="30px;" height="30px;" title="Exportar" alt="Exportar" /><br />
					Exportar  
				</a>      
			</td>';

			if(sistemaActivo!='olyess' and sistemaActivo!='IEXE')
			{
				echo'
				<td class="button" width="8%">
					<a id="btnListas" class="toolbar" onclick="obtenerListas()">
						<img src="'.base_url().'img/descuento.png" width="30px;" height="30px;" title="Listas" /><br />
						Descuentos  
					</a>      
				</td>

				<td class="button" width="8%">
					<a id="btnActualizarProducto" class="toolbar" onclick="formularioActualizarProducto()">
						<img src="'.base_url().'img/mermas.png" width="30px;" height="30px;" title="Actualizar" /><br />
						Actualizar  
					</a>      
				</td>

				<td class="button" width="8%">
					<a id="btnActualizarProducto" class="toolbar" onclick="formularioAsignarProveedor()">
						<img src="'.base_url().'img/proveedores.png" width="30px;" height="30px;" title="Proveedor" /><br />
						Proveedor  
					</a>      
				</td>
				
				<td class="button" width="8%">
					<a id="btnBorrarInventario" class="toolbar" onclick="accesoBorrarInventarioSucursal('.rand().')">
						<img src="'.base_url().'img/borrar.png" width="30px;" height="30px;" title="Borrar inventario" /><br />
						Borrar inventario  
					</a>      
				</td>';
			}
		}

		echo '
		<td width="60%" align="left" valign="middle" style=" padding-right:120px">
			<input type="text"  name="txtBuscarProductoInventario" id="txtBuscarProductoInventario" class="busquedas" placeholder="Buscar por producto, código interno, código de barras"  style="width:500px; height: 25px; font-size: 14px "/>
			<input type="hidden" value="asc" id="txtOrdenProductos" />
        </td>';
		
		if($permiso[1]->activo==0)
		{ 
			echo '
			<script>
				desactivarBotonSistema(\'btnAgregarProducto\');
				
				desactivarBotonSistema(\'btnImportar\');
				desactivarBotonSistema(\'btnTraspasos\');
			</script>';
		}
	
		if($permiso[6]->activo==0)
		{ 
			echo '
			<script>
				desactivarBotonSistema(\'btnBorrarInventario\');
			</script>';
		}
	
		if($permiso[5]->activo==0)
		{
			echo '
			<script>
				desactivarBotonSistema(\'btnExportar\');
			</script>';
		}
		
		if(sistemaActivo!='olyess' and sistemaActivo!='IEXE')
		{
			if($permiso[4]->activo==0)
			{
				 echo '
				<script>
					desactivarBotonSistema(\'btnListas\');
				</script>';
			}
		}
        ?>
       	
    </tr>
 </table>
 </div>
</div>
<div class="listproyectos">


	<div id="exportandoDatos"></div>
    
	
		<table class="admintable" width="100%">
			<tr>
				<th class="encabezadoPrincipal" style="width:3%; ">#</th>
				<th class="encabezadoPrincipal" width="15%" >Código</th>
				<th class="encabezadoPrincipal" width="10%" >
				Código interno
				<br>
					<input type="text"  name="txtBuscarProductoCodigo" id="txtBuscarProductoCodigo" class="busquedas" placeholder="Código interno"  style="width:95%; height: 25px; font-size: 14px "/>
				</th>
				<th class="encabezadoPrincipal" width="10%" >Imagen</th>
				<th class="encabezadoPrincipal" width="20%">
					Producto
					<?php
					#echo '<img onclick="ordenInventarioProductos('.($orden=='asc'?'\'desc\'':'\'asc\'').')" src="'.base_url().'img/'.($orden=='asc'?'ocultar':'mostrar').'.png" width="17" />';
					?>
				</th>
				<!--<th class="encabezadoPrincipal" width="20%">Departamento</th>-->
				<th class="encabezadoPrincipal" width="8%">

					<?php
					if(sistemaActivo=='cerraduras')
					{
						echo '
						<select class="cajas" id="selectStockBusqueda" onchange="obtenerProductos()" style="width:80px">
							<option value="0">Stock</option>
							<option value="1">Stock mínimo</option>
						</select>';
					}
					else
					{
						echo 'Stock';
					}
					?>

				</th>
				<th class="encabezadoPrincipal" width="5%">Mínimo</th>
				<th class="encabezadoPrincipal" width="7%"><?=obtenerNombrePrecio(1)?></th>
				<th class="encabezadoPrincipal" style="width:22%;">Acciones </th>
			</tr>
		</table>
	<div id="obtenerProductos">
	</div>

    <div id="ventanaDetallesProducto" title="Detalles del producto">
    <div id="errorDetallesProducto" class="ui-state-error" ></div>
    <div id="cargarDetallesProducto"></div>
    </div>

    <div id="ventanaAgregarProveedores" title="Agregar proveedor a producto">
		<div id="cargandoAgregarProveedor"></div>
		<div class="ui-state-error" ></div>
		<div id="cargarAgregarProveedor"></div>
    </div>

    <div id="ventanaEditarProductoInventario" title="Editar producto de reventa">
    <div id="editandoProducto"></div>
    <div id="obtenerDetallesProducto"></div>
    </div>
    
    <div id="ventanaRegistrarInventario" title="Agregar producto de reventa">
    <div id="registrandoInventario"></div>
    <div id="formularioProductos"></div>
    </div>
    
    <div id="ventanaLineas" title="Líneas">
    <div id="agregandoLinea"></div>
    <div id="formularioLineas"></div>
    </div>

    <div id="ventanaImportarProductos" title="Importar productos">
        <div id="importandoProductos"></div>
        <div class="ui-state-error" ></div>
        <div id="formularioImportarProductos"></div>
    </div>
    
    <div id="ventanaActualizarProducto" title="Actualizar productos">
        <div id="actualizandoProducto"></div>
        <div class="ui-state-error" ></div>
        <div id="formularioActualizarProducto"></div>
    </div>
    
    
    <input type="hidden"  name="txtGrupoActivo" id="txtGrupoActivo" value="Activo" />
    <div id="ventanaFormularioAsociarCuenta" title="Cuentas contables">
        <div id="asociandoCuentas"></div>
        <div class="ui-state-error" ></div>
        <div id="formularioAsociarCuenta"></div>
    </div>
	
	<div id="ventanaAsignarProveedor" title="Asignar proveedor">
		<div id="asignandoProveedor"></div>
		<div id="formularioAsignarProveedor"></div>
    </div>
	
	<div id="ventanaPorcentaje" title="Porcentaje">
		<div id="registrandoPorcentaje"></div>
		<div id="formularioPorcentaje"></div>
	</div>
    
    <?php $this->load->view('traspasos/modalesTraspasos')?>
    <?php $this->load->view('inventarioProductos/listas/modalListas')?>
	<?php $this->load->view('pedimentos/catalogo')?>

</div>
</div>
