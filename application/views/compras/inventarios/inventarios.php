<script src="<?php echo base_url()?>js/compras/inventarios/inventarios.js"></script>
<script src="<?php echo base_url()?>js/compras/comprobantesCompras.js"/></script>
<script src="<?php echo base_url()?>js/compras/enviarCompra.js"/></script>
<script src="<?php echo base_url()?>js/compras/informacionCompras.js"></script>  
<script src="<?php echo base_url()?>js/compras/terminos.js"></script>  
<script src="<?php echo base_url()?>js/inventarios/catalogo.js"></script>  
<script src="<?php echo base_url()?>js/inventarios/asociar.js"></script>  
<script src="<?php echo base_url()?>js/administracion/comprobantesEgresos.js"></script>
<script src="<?php echo base_url()?>js/proveedores/catalogo.js"/></script>

<!--CRM DE SERVICIOS-->
<script src="<?php echo base_url()?>js/proveedores/seguimiento/detalles.js"></script>
<script src="<?php echo base_url()?>js/proveedores/seguimiento/archivos.js"></script>
<script src="<?php echo base_url()?>js/crm/proveedores/servicios/servicios.js"></script>
<script src="<?php echo base_url()?>js/crm.js"></script>

<script>
$(document).ready(function()
{
	calcularTotales();
});
</script>

<div class="derecha">
<div class="submenu">
<div class="breadcumb"><?php echo isset($breadcumb)?$breadcumb:''?></div>
<div class="toolbar" id="toolbar">
 <table class="toolbar" style="width:100%">	
 	<!--<tr>
    	<td class="seccion" colspan="3">
    		Compras de Mobiliario/equipo
   	    </td>
    </tr>-->
    <tr>
     	
        <td align="center" valign="middle" style="width:10%" >
            <?php 
            echo'
            <a id="btnCompraInventario" onclick="formularioCompras()" >
                <img src="'.base_url().'img/compras.png" width="30px;" height="30px;" style="cursor:pointer;" title="Nueva compra">
                <br />
                Nueva compra
            </a>';	
             ?>
        </td>
        
        <td align="center" valign="middle" style="width:10%" >
            <?php 
            echo'
            <a onclick="obtenerCatalogoInventarios()" id="btnInventario">
                <img src="'.base_url().'img/comprasInventario.png" width="30px;" height="30px;" style="cursor:pointer;" title="Nueva compra">
                <br />
                Mobiliario/Equipo
            </a>';	
             ?>
        </td>
            
            <?php
		if($permiso[1]->activo==0)
		{
			 echo '
			<script>
				desactivarBotonSistema(\'btnCompraInventario\');
			</script>';
		}
		
		if($permisoInventario[0]->activo==0)
		{
			 echo '
			<script>
				desactivarBotonSistema(\'btnInventario\');
			</script>';
		}
        ?>

		<td align="left" valign="middle" style="width:80%; padding-right:100px">
			<input type="text" class="busquedas" placeholder="Seleccione fecha" onchange="busquedaFecha()" style="width:120px" id="FechaDia" />
        	<input type="text" class="busquedas" placeholder="Buscar por proveedor" style="width:450px" id="txtProveedorCompra" />
        	<input type="text" class="busquedas" placeholder="Buscar por orden"  style="width:140px" id="txtBusquedaOrden" />
            
            <input type="hidden"  name="txtIdServicioCrm" id="txtIdServicioCrm" value="3"/>
        	<input type="hidden"  name="txtIdProveedorCrm" id="txtIdProveedorCrm" value="0"/>
        
            &nbsp;
            
            <?php
            if($fecha!='fecha' or $idCompras!=0 or $idProveedor!=0)
            {
                echo '<img src="'.base_url().'img/quitar.png" style="width:22px; height:22px" title="Borrar busqueda" onclick="window.location.href=\''.base_url().'compras/inventarios\'" />';
            }
            ?>         
        </td>
    </tr>
 </table>
 </div>
</div>

<div class="listproyectos">
<input type="hidden" id="paginador" value="<?php echo $inicio?>" />
<?php

if(!empty($compras))
{
	echo'
	<div style="width:90%; margin-bottom:1%;">
		<ul id="pagination-digg" class="ajax-pagin">'.$this->pagination->create_links().'</ul>
	</div>';
	
	?>
	<table class="admintable" width="100%">
		<tr>
			<th class="encabezadoPrincipal">#</th>
			<th class="encabezadoPrincipal">Fecha</th>
			<th class="encabezadoPrincipal">Proveeedor</th>
			<th class="encabezadoPrincipal">Orden de compra</th>
            <th class="encabezadoPrincipal">CRM</th>
			<th class="encabezadoPrincipal">Precio</th>
			<th class="encabezadoPrincipal">Pago</th>
			<th class="encabezadoPrincipal">Saldo</th>
			<th class="encabezadoPrincipal" style="width:32%">Acciones</th>             
		</tr>
	<?php
	$i=1;
	foreach ($compras as $compra)
	{
		$estilo		= $i%2>0?'class="sinSombra"':'class="sombreado"';
		$onclick	= 'onclick="obtenerComprita('.$compra->idCompras.')" title="Click para ver el detalle"';
		$pagado		= $this->compras->obtenerPagado($compra->idCompras);
		$saldo		= $compra->total-$pagado;
		
		?>
	
		<tr <?php echo $estilo?>>
			<td align="left" valign="middle" <?php echo $onclick?>> <?php echo $i ?> </td>
			<td align="center" valign="middle" <?php echo $onclick?>><?php echo obtenerFechaMesCorto($compra->fechaEntrega); ?></td>
			<td align="center" valign="middle" <?php echo $onclick?>>  <?php print($compra->empresa); ?> </td>
			<td align="center" valign="middle" <?php echo $onclick?>> <a><?php print($compra->nombre); ?> </a></td>
            
            <?php
			$seguimiento	= null;
			if(strlen($compra->idSeguimiento)>0)
			{
				$seguimiento	= $this->crm->obtenerUltimoSeguimientoCompra($compra->idCompras);
			}
			
			$mostrarSeguimiento=false;
			
			if($permisoCrm[0]->activo==1)
			{
				$mostrarSeguimiento=true;
			}
		
            echo'
			<td align="center" title="Click para ver detallles de seguimiento" '.($mostrarSeguimiento?($seguimiento!=null?'onclick="obtenerSeguimientoServicio('.$compra->idCompras.','.$seguimiento->idSeguimiento.')"':'onclick="obtenerSeguimientoServicio('.$compra->idCompras.',0)"'):'').' >';
				
				if($mostrarSeguimiento and $seguimiento!=null)
				{
					echo'
					<span >
						<div style="background-color: '.$seguimiento->color.'" class="circuloStatus"></div>
						<i style="font-weight:100">'.$seguimiento->status.'<br />'.obtenerFechaMesCortoHora($seguimiento->fecha).'</i>
					</span>';
				}
				if($mostrarSeguimiento and $seguimiento==null)
				{
					echo '<img src="'.base_url().'img/crm.png" width="22" height="22" />';
				}
				
			echo'
			</td>';
			?>
            
			<td align="right" valign="middle" <?php echo $onclick?>>$<?php print(number_format($compra->total,2)); ?> </td>
			<td id="tdPagado<?php echo $compra->idCompras?>" align="right" valign="middle" <?php echo $onclick?>>$<?php print(number_format($pagado,2))?></td>
			<td id="tdSaldo<?php echo $compra->idCompras?>" align="right" valign="middle" <?php echo $onclick?>>$<?php print(number_format($saldo,2))?></td>
			<td align="center"   valign="middle"> 

		 	<?php
			
			$imagen	= base_url()."img/success.png";
			$sql	= "select recibido from compra_detalles where idCompra='".$compra->idCompras."'";
			$query	= $this->db->query($sql);
			
			foreach($query->result() as $row)
			{
				if($row->recibido=="0")
				{
					$imagen=base_url()."img/Cerrar.png";
					break;
				}
			}
			
			echo '
			&nbsp;&nbsp;&nbsp;
			<img id="btnRecibirProductos'.$i.'" src="'.$imagen.'" width="22" height="22" hspace="3" title="Recibido" onclick="obtenerProductosComprados('.$compra->idCompras.');"style="cursor:pointer;"/>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<img id="btnPagosProveedor'.$i.'" onclick="obtenerPagosComprasProveedor('.$compra->idCompras.')" src="'.base_url().'img/pagos.png" width="22" height="22" title="Pagos a proveedor"/>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<img id="btnComprobantesCompras'.$i.'" src="'.base_url().'img/subir.png" width="22"  onclick="obtenerComprobantesCompras('.$compra->idCompras.',0)"  title="Comprobantes" />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<img id="btnPdfCompras'.$i.'" onclick="window.open(\''.base_url().'compras/comprasPDFInventarios/'.$compra->idCompras.'/'.$this->session->userdata('idLicencia').'\')" src="'.base_url().'img/pdf.png" width="22" height="22" title="PDF" />
            &nbsp;&nbsp;
            <img id="btnEnviarCompra'.$i.'" onclick="formularioEnviarCompra('.$compra->idCompras.')" src="'.base_url().'img/correo.png" width="20" height="20"  title="Enviar"/>
			&nbsp;&nbsp;&nbsp;&nbsp;
			<img id="btnCancelarCompra'.$i.'" onclick="cancelarCompra('.$compra->idCompras.',\'Cancelar la compra borrara tambien sus pagos, ¿Desea continuar?\',\'inventarios\')" src="'.base_url().'img/cancelame.png" width="22" height="22" title="Cancelar compra" />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<img id="btnBorrarCompra'.$i.'" onclick="borrarCompra('.$compra->idCompras.',\'Borrar la compra borrara tambien sus pagos, ¿Desea continuar?\',\'inventarios\')" src="'.base_url().'img/quitar.png" width="22" height="22" title="Borrar compra" />
			
			&nbsp;&nbsp; 
			<a id="a-btnRecibirProductos'.$i.'">Recibido</a>
			&nbsp;&nbsp; 
			<a id="a-btnPagosProveedor'.$i.'">Pagos</a>&nbsp;
			<a id="a-btnComprobantesCompras'.$i.'">Comprobantes</a>
			&nbsp;
			<a id="a-btnPdfCompras'.$i.'">PDF</a>
			 &nbsp;&nbsp;
			<a id="a-btnEnviarCompra'.$i.'">Enviar</a>
			<a id="a-btnCancelarCompra'.$i.'">Cancelar </a>
			<a id="a-btnBorrarCompra'.$i.'">Borrar </a>';
			
			if($compra->cancelada=='1')
			{
				echo '
				<script>
					desactivarBotonSistema(\'btnEnviarCompra'.$i.'\');
					desactivarBotonSistema(\'btnPdfCompras'.$i.'\');
				</script>';
			}
			
			if($permiso[2]->activo==0 or $compra->cancelada=='1')
			{
				echo '
				<script>
					desactivarBotonSistema(\'btnRecibirProductos'.$i.'\');
					desactivarBotonSistema(\'btnPagosProveedor'.$i.'\');
					desactivarBotonSistema(\'btnComprobantesCompras'.$i.'\');
				</script>';
			}
			
			if($permiso[3]->activo==0 or $compra->cancelada=='1')
			{
				echo '
				<script>
					desactivarBotonSistema(\'btnCancelarCompra'.$i.'\');
					desactivarBotonSistema(\'btnBorrarCompra'.$i.'\');
				</script>';
			}
			?>
			
			</td>
		</tr>
		<?php
		$i++;
	 }
	?>
	</table>
	
	<?php
	echo'
	<div style="width:90%; margin-bottom:1%;">
		<ul id="pagination-digg" class="ajax-pagin">'.$this->pagination->create_links().'</ul>
	</div>';
	
}
else
{
	echo'<div class="Error_validar" style="margin-top:10px; margin-bottom: 5px;">No hay registros de compras de Mobiliario/equipo</div>';
}
?>


<!-- Productos con sus materiales-->

<div id="ventanaRecibirProducto" title="Recibir productos comprados">
<div style="width:99%;" id="recibiendoProductos"></div>
<div id="errorRecibiendoProductos" class="ui-state-error" ></div>
	<div id="formularioProductosRecibidos"></div>
</div>


<div id="ventanaProductosRecibidos" title="Recibir productos comprados">
<div id="recibiendoCompras"></div>
<div id="errorRecibirCompras" class="ui-state-error" ></div>
	<div id="cargarRecibidos"></div>
</div>

<div id="ventanaComprasInventario" title="Nueva compra de Mobiliario/equipo">
<div id="errorInformacionCliente" class="ui-state-error" ></div>
<div id="procesandoCompraProducto"></div>

<div id="busquedas" style="float:left; width:33%" >
    <label>Buscar:</label> 
    <input type="text" class="cajas" style="width:300px" id="txtCriterio" name="txtCriterio" placeholder="Buscar Mobiliario/equipo"  />
</div>

<div id="listaProveedores" style="float:left; width:48">
    <label>Proveedor:</label> 
    <input type="hidden" id="proveedores" value="0" />
    <input type="text" class="cajas" id="txtProveedores" placeholder="Buscar proveedor" style="width:500px" />
</div>

<div style="float:right; width:14%" align="center">
    <img src="<?php echo base_url()?>img/proveedores.png" title="Agregar proveedor" style="width:30px; height:30px; cursor: pointer" onclick="accesoAgregarProveedorServicio(1)" />
    <br />    
    <a>Agregar proveedor</a>
</div>

<div id="productosKit" style="float:left; vertical-align:top; width:100%; margin-bottom:3px" ></div> 
<table class="admintable" style="width:100%;">
    <tr>
        <th style="font-size:12px" colspan="5">
        <input type="hidden" id="paginaActiva" value="0" />
        <input type="hidden" id="paginaActivada" value="compras" />
        Orden de  compra <input type="text" id="nombreKit" class="cajas" style="width:300px" value="OC-<?php echo $orden?>"/>  
        
        
		Fecha 
		<input type="text" id="txtFechaCompra" value="<?php echo date('Y-m-d H:i')?>" class="cajas" style="width:110px" />
        <script>
			$('#txtFechaCompra').timepicker();
		</script>
        
         &nbsp;
        Fecha entrega
        <input type="text" id="txtFechaEntrega" value="<?php echo date('Y-m-d')?>" class="cajas" style="width:80px" />
        <script>
            $('#txtFechaEntrega').datepicker();
        </script>
		
        Días de crédito 
		<input type="text" id="txtDiasCredito" value="0" class="cajas" style="width:100px" />
        
        &nbsp;
       	 	Más iva 
			<input type="checkbox" id="chkIva"  checked="checked" onchange="calcularTotales()"/> 
        </th>
    </tr>
</table>

<table class="admintable" style="width:100%; margin-top:4px">
	<tr>
    	<td style="width:74%">
        	<table class="admintable" style="width:100%;" id="armarKit">
                <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Proveedor</th>
                    <th>Fecha entrega</th>
                    <th>Cantidad</th>
                    <th>Precio unitario</th>
                    <th>Descuento unitario</th>
                    <th>Total</th>
                </tr>
            </table>
        </td>
        <td style="width:24%; position:absolute">
        	<table class="admintable" style="width:100%;">
            	<tr>
                	<td class="key">Subtotal</td>
                    <td>
                    	<input type="text" id="kitTotal" style="width:100px;" readonly="readonly" name="kitTotal" class="cajas" value="0" />
                    </td>
                </tr>
                <tr>
                	<td class="key">Descuento global</td>
                    <td>
                    	<input type="text" id="txtDescuentoPorcentaje" onchange="calcularTotales()" style="width:100px;" name="txtDescuentoPorcentaje" class="cajas" value="0" />
                    </td>
                </tr>
                
                <tr>
                	<td class="key">Descuento total</td>
                    <td>
                    	<input type="text" id="txtDescuentoTotal" style="width:100px;" name="txtDescuentoTotal" class="cajas" value="0" readonly="readonly" />
                    </td>
                </tr>
                
                <tr>
                	<td class="key">IVA <?php echo number_format($configuracion->iva,decimales)?>%</td>
                    <td>
                    	<input type="hidden" id="txtIvaPorcentaje" name="txtIvaPorcentaje" value="<?php echo $configuracion->iva?>" />
                    	<input type="text" id="txtIva" style="width:100px;" readonly="readonly" name="txtIva" class="cajas" value="0" />
                    </td>
                </tr>
                <tr>
                	<td class="key">Total</td>
                    <td>
                    	<input type="text" id="txtTotalCompra" style="width:100px;" readonly="readonly" name="txtTotalCompra" class="cajas" value="0" />
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

</div>

<div id="ventanaTerminos" title="Términos / condiciones">
	<table class="admintable" width="100%">
    	<tr>
        	<td class="key">Términos / condiciones:</td>
            <td>
            	<textarea class="TextArea" id="txtTerminos" name="txtTerminos" style="width:300px; height:50px"></textarea>
            </td>
        </tr>
    </table>
</div>

<div id="ventanaEnviarCompra" title="Enviar orden de compra por correo">
	<div id="enviandoCompra"></div>
	<div id="formularioEnviarCompra"></div>
</div>

<div id="ventanaComprobantesCompras" title="Comprobantes compras">
<div id="registrandoComprobanteCompra"></div>
<div id="obtenerComprobantesCompras"></div>
</div>

<div id="ventanitaCompras" title="Detalles de compra:">
<div id="errorComprita" class="ui-state-error" ></div>
<div  id="cargarComprita"></div>
</div>

<div id="ventanaRecibirTodosInventarios" title="Recibir todos los productos">
    <div class="ui-state-error" ></div>
    <div id="recibiendoTodosInventarios"></div>
    <div id="formularioRecibirTodosInventarios"></div>
</div>

<div id="ventanaAsociarProveedorCompra" title="Agregar proveedor al Mobiliario/equipo">
<div id="agregandoProveedorCompra"></div>
<div class="ui-state-error" ></div>
<div id="formularioAgregarProveedorCompra"></div>
</div>

<div id="ventanaCatalogoInventarios" title="Catálogo Mobiliario/equipo">
	<div id="obtenerCatalogoInventarios"></div>
</div>

<div id="ventanaComprobantesEgresos" title="Comprobantes egresos">
<div id="registrandoComprobanteEgreso"></div>
<div id="obtenerComprobantesEgresos"></div>
</div>

<div id="ventanaProveedores" title="Registrar proveedor">
    <div id="cargandoProveedores"></div>
    <div class="ui-state-error" ></div>
    <div id="formularioProveedores"></div>    
</div>


<?php $this->load->view('clientes/seguimiento/crmServicios/modalesSeguimientoServicios');?>

</div>
</div>
