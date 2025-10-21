<script src="<?php echo base_url()?>js/compras/servicios/servicios.js"/></script>

<script src="<?php echo base_url()?>js/proveedores/catalogo.js"></script>
<script src="<?php echo base_url()?>js/compras/comprobantesCompras.js"/></script>
<script src="<?php echo base_url()?>js/compras/enviarCompra.js"/></script>
<script src="<?php echo base_url()?>js/compras/informacionCompras.js"></script>  
<script src="<?php echo base_url()?>js/administracion/comprobantesEgresos.js"></script>

<script src="<?php echo base_url()?>js/serviciosConsumo/catalogo.js"></script>
<script src="<?php echo base_url()?>js/serviciosConsumo/asociar.js"></script>
<script src="<?php echo base_url()?>js/compras/terminos.js"/></script>


<!--CRM DE SERVICIOS-->
<script src="<?php echo base_url()?>js/proveedores/seguimiento/detalles.js"></script>
<script src="<?php echo base_url()?>js/proveedores/seguimiento/archivos.js"></script>
<script src="<?php echo base_url()?>js/crm/proveedores/servicios/servicios.js"></script>
<script src="<?php echo base_url()?>js/crm.js"></script>


<script>
$(document).ready(function()
{
	$('#proveedores').val('0');
	$('#txtProveedores').val('');
	$('#txtDiasCredito').val('0');
	calcularTotales();
});
</script>

<div class="derecha">
<div class="submenu">
<div class="breadcumb"><?php echo isset($breadcumb)?$breadcumb:''?></div>
<div class="toolbar" id="toolbar">
 <table class="toolbar" style="width:100%">	
 	<!--<tr>
    	<td class="seccion" colspan="2">
    		Compras de servicios
   	  	</td>
    </tr>-->
    <tr>
     	
     <?php
		echo'
		<td align="center" valign="middle" style="width:22%" >
			<a id="btnCompras" onclick="formularioCompras()" >
				<img src="'.base_url().'img/compras.png" width="30px;" height="30px;" style="cursor:pointer;" title="Nueva compra" >
				<br />
				Nueva compra	
			</a>
			
			<a onclick="obtenerCatalogoServicios()" id="btnServicios">
				<img src="'.base_url().'img/servicios.png"  width="30px;" height="30px;" style="cursor:pointer;" title="Catálogo de materia servicios">
				<br />
				Servicios
			</a>	 	 
		</td>';

		if($permiso[1]->activo==0)
		{
			 echo '
			<script>
				desactivarBotonSistema(\'btnCompras\');
			</script>';
		}
		
		if($permisoServicio[0]->activo==0)
		{
			 echo '
			<script>
				desactivarBotonSistema(\'btnServicios\');
			</script>';
		}
        ?>

            
      <td align="left" valign="middle" style="width:78%; padding-right:100px">
        <input type="text" class="busquedas" placeholder="Seleccione fecha" onchange="busquedaFecha()" style="width:120px" id="FechaDia" />
        <input type="text" class="busquedas" placeholder="Buscar por proveedor" style="width:450px" id="txtProveedorCompra" />
        <input type="text" class="busquedas" placeholder="Buscar por orden"  style="width:150px" id="txtBusquedaOrden" />
        
        <input type="hidden"  name="txtIdServicioCrm" id="txtIdServicioCrm" value="3"/>
        <input type="hidden"  name="txtIdProveedorCrm" id="txtIdProveedorCrm" value="0"/>
        
         <input type="hidden" 	id="paginaActiva" value="0" />
        <input type="hidden" 	id="paginaActivada" value="compras" />
        <input type="hidden"  	name="txtPaginaActivada" 	id="txtPaginaActivada"  value="servicios"/>
        
        &nbsp;
        
         <?php
		if($fecha!='fecha' or $idCompras!=0 or $idProveedor!=0)
		{
			echo '<img src="'.base_url().'img/quitar.png" style="width:22px; height:22px" title="Borrar busqueda" onclick="window.location.href=\''.base_url().'servicios/compras\'" />';
		}
      ?>         
         
        </td>
    </tr>
 </table>
 </div>
</div>

<div class="listproyectos">

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
			<th class="encabezadoPrincipal" style="width:36%">Acciones</th>             
		</tr>
	<?php
	$i=1;
	foreach ($compras as $row)
	{
		$estilo		= $i%2>0?'class="sinSombra"':'class="sombreado"';
	
		$pagado		= $this->compras->obtenerPagado($row->idCompras);
		$saldo		= $row->total-$pagado;
		$onclick	= 'onclick="obtenerComprita('.$row->idCompras.')" title="Click para ver el detalle"';
		
		?>
	
		<tr <?php echo $estilo?>>
			<td align="left" valign="middle" <?php echo $onclick?>><?php echo $i; ?> </td>
			<td align="center" valign="middle" <?php echo $onclick?>><?php echo obtenerFechaMesCorto($row->fechaEntrega); ?></td>
			<td align="center" valign="middle" <?php echo $onclick?>><?php print($row->empresa); ?> </td>
			<td align="center" valign="middle" <?php echo $onclick?>><a><?php print($row->nombre); ?> </a></td>
            
            <?php
			$seguimiento	= null;
			if(strlen($row->idSeguimiento)>0)
			{
				$seguimiento	= $this->crm->obtenerUltimoSeguimientoCompra($row->idCompras);
			}
			
			$mostrarSeguimiento=false;
			
			if($permisoCrm[0]->activo==1)
			{
				$mostrarSeguimiento=true;
			}
			
            echo'
			<td align="center" title="Click para ver detallles de seguimiento" '.($mostrarSeguimiento?($seguimiento!=null?'onclick="obtenerSeguimientoServicio('.$row->idCompras.','.$seguimiento->idSeguimiento.')"':'onclick="obtenerSeguimientoServicio('.$row->idCompras.',0)"'):'').' >';
				
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
			            
			<td align="right" valign="middle" <?php echo $onclick?>>  $<?php print(number_format($row->total,2)); ?> </td>
			<td id="tdPagado<?php echo $row->idCompras?>" align="right" valign="middle" <?php echo $onclick?>>$<?php print(number_format($pagado,2))?></td>
			<td id="tdSaldo<?php echo $row->idCompras?>" align="right" valign="middle" <?php echo $onclick?>>$<?php print(number_format($saldo,2))?></td>
			<td align="left"   valign="middle"> 
			&nbsp;
			 
			<?php
			
			 $imagen	= base_url()."img/success.png";
			 
			 $sql	= "select recibido from compra_detalles where idCompra='".$row->idCompras."'";
			 $query	= $this->db->query($sql);
			 
			 foreach($this->db->query($sql)->result() as $recibido)
			 {
				 if($recibido->recibido=="0")
				 {
					 $imagen=base_url()."img/Cerrar.png";
					 break;
				 }
			 }
			 
			echo '
			&nbsp;&nbsp;&nbsp;
			<img id="btnRecibirProductos'.$i.'" src="'.$imagen.'" width="22" height="22"  title="Recibido" onclick="obtenerProductosComprados('.$row->idCompras.');"style="cursor:pointer;"/>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			
			<img id="btnPagosProveedor'.$i.'" onclick="obtenerPagosComprasProveedor('.$row->idCompras.')" src="'.base_url().'img/pagos.png" width="22" height="20" title="Pagos a proveedor" />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<img id="btnComprobantesCompras'.$i.'" src="'.base_url().'img/subir.png" width="22"  onclick="obtenerComprobantesCompras('.$row->idCompras.',0)"  title="Comprobantes" />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<a id="btnPdfCompras'.$i.'" onclick="window.open(\''.base_url().'compras/comprasPDFServicios/'.$row->idCompras.'/'.$this->session->userdata('idLicencia').'\')" >
                <img src="'.base_url().'img/pdf.png" width="22" height="22" title="PDF" />
            </a>
			&nbsp;&nbsp;&nbsp;&nbsp;
			<img id="btnEnviarCompra'.$i.'" onclick="formularioEnviarCompra('.$row->idCompras.')" src="'.base_url().'img/correo.png" width="20" height="20"  title="Enviar" />
			&nbsp;&nbsp;&nbsp;&nbsp;
			<img id="btnCancelarCompra'.$i.'" onclick="cancelarCompra('.$row->idCompras.',\'Cancelar la compra borrara tambien sus pagos, ¿Desea continuar?\',\'servicios\')" src="'.base_url().'img/cancelame.png" width="22" height="22"  title="Cancelar compra" />
			
			&nbsp;&nbsp;&nbsp;
			<img id="btnBorrarCompra'.$i.'" onclick="borrarCompra('.$row->idCompras.',\'Borrar la compra borrara tambien sus pagos, ¿Desea continuar?\',\'servicios\')" src="'.base_url().'img/quitar.png" width="22" height="22"  title="Borrar compra" />
			
			<br />
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
					
			if($row->cancelada=='1')
			{
				echo '
				<script>
					desactivarBotonSistema(\'btnEnviarCompra'.$i.'\');
					desactivarBotonSistema(\'btnPdfCompras'.$i.'\');
				</script>';
			}
			
			if($permiso[2]->activo==0 or $row->cancelada=='1')
			{
				echo '
				<script>
					desactivarBotonSistema(\'btnRecibirProductos'.$i.'\');
					desactivarBotonSistema(\'btnPagosProveedor'.$i.'\');
					desactivarBotonSistema(\'btnComprobantesCompras'.$i.'\');
				</script>';
			}
			
			if($permiso[3]->activo==0 or $row->cancelada=='1')
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
	 }//Foreach del Cliente
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
	echo'<div class="Error_validar" style="margin-top:10px; margin-bottom: 5px;">No hay registros de compras</div>';
}
?>

<div id="ventanaRecibirProducto" title="Recibir productos comprados">
<div id="recibiendoProductos"></div>
<div id="formularioProductosRecibidos"></div>
</div>

<div id="ventanaRecibirCompras" title="Recibir productos comprados">
<div id="id_CargandoRecibido"></div>
<div id="cargarProductosRecibidos"></div>
</div>


<div id="ventanaComprasServicios" title="Nueva compra">
<div id="procesandoCompraServicios"></div>
<div class="ui-state-error" ></div>

<div id="busquedas" style="float:left; width:35%" class="" >
    <label>Buscar:</label> 
    <input type="text" class="cajas" style="width:300px" id="txtBuscarServicio" name="txtBuscarServicio" placeholder="Buscar servicio" />
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

<div id="obtenerServiciosCompra" style="float:left; vertical-align:top; width:100%; margin-bottom:3px" ></div> 
<table class="admintable" style="width:100%;">
    <tr>
        <th style="font-size:12px" colspan="5">
           
            Orden de compra <input type="text" id="nombreKit" class="cajas" style="width:300px" value="OC-<?php echo $orden?>" />  

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
        	<table class="admintable" style="width:100%;" id="tablaCompras">
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

<div id="ventanaEnviarCompra" title="Enviar orden de compra por correo">
	<div id="enviandoCompra"></div>
	<div id="formularioEnviarCompra"></div>
</div>

<div id="ventanaComprobantesCompras" title="Comprobantes compras">
<div id="registrandoComprobanteCompra"></div>
<div id="obtenerComprobantesCompras"></div>
</div>

<div id="ventanitaCompras" title="Detalles de compra">
<div id="errorComprita" class="ui-state-error" ></div>
<div  id="cargarComprita"></div>
</div>


<div id="ventanaRecibirTodosMateriales" title="Recibir todos los servicios">
<div class="ui-state-error" ></div>
<div id="recibiendoTodosMateriales"></div>
<div id="formularioRecibirTodosMateriales"></div>
</div>

<div id="ventanaComprobantesEgresos" title="Comprobantes egresos">
<div id="registrandoComprobanteEgreso"></div>
<div id="obtenerComprobantesEgresos"></div>
</div>

<div id="ventanaCatalogoServicios" title="Catálogo de servicios">
<div id="obtenerCatalogoServicios"></div>
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

<div id="ventanaProveedores" title="Registrar proveedor">
    <div id="cargandoProveedores"></div>
    <div class="ui-state-error" ></div>
    <div id="formularioProveedores"></div>    
</div>

<div id="ventanaAsociarProveedorCompra" title="Agregar proveedor a servicio">
<div id="asociandoProveedorCompra"></div>
<div id="formularioAgregarProveedorCompra"> </div>
</div>

<?php $this->load->view('clientes/seguimiento/crmServicios/modalesSeguimientoServicios');?>

</div>
</div>
