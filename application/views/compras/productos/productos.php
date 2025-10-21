<script src="<?php echo base_url()?>js/compras/productos/compras.js?v=<?=rand()?>"></script>
<script src="<?php echo base_url()?>js/compras/comprobantesCompras.js"/></script>
<script src="<?php echo base_url()?>js/compras/enviarCompra.js"/></script>
<script src="<?php echo base_url()?>js/compras/informacionCompras.js"></script> 
<script src="<?php echo base_url()?>js/compras/terminos.js"/></script>
<script src="<?php echo base_url()?>js/productos/catalogo.js"></script>  
<script src="<?php echo base_url()?>js/productos/asociar.js"></script>
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
    	<td class="seccion" colspan="2">
    		Compras de productos
   	    </td>
    </tr>-->
    <tr>
     	
     <?php
			echo '
			<td align="center" valign="middle" style="width:10%" >
				<a id="btnCompras" onclick="formularioCompras()">
				<img src="'.base_url().'img/compras.png" width="30px;" height="30px;"  style="cursor:pointer;" title="Nueva compra">
				<br />
				Nueva compra</a>	 
            </td>
			
			<td align="center" valign="middle" style="width:10%">
				<a id="btnProductos" onclick="obtenerCatalogoProductos()">
				<img src="'.base_url().'img/productos.png" width="30px;" height="30px;"  style="cursor:pointer;" title="Productos">
				<br />
				Productos</a>	 
            </td>';

		if($permiso[1]->activo==0)
		{
			 echo '
			<script>
				desactivarBotonSistema(\'btnCompras\');
			</script>';
		}
		
		if($permisoProductos[0]->activo==0)
		{
			 echo '
			<script>
				desactivarBotonSistema(\'btnProductos\');
			</script>';
		}
        ?>
      <td align="left" valign="middle" style="width:80%; ">
        <input type="text" class="busquedas" placeholder="Seleccione fecha" onchange="busquedaFecha()" style="width:120px" id="FechaDia" />
        <input type="text" class="busquedas" placeholder="Buscar por proveedor" style="width:400px" id="txtProveedorCompra" />
        <input type="text" class="busquedas" placeholder="Buscar por orden"  style="width:150px" id="txtBusquedaOrden" />
        
        <input type="hidden"  name="txtIdServicioCrm" id="txtIdServicioCrm" value="3"/>
        <input type="hidden"  name="txtIdProveedorCrm" id="txtIdProveedorCrm" value="0"/>
        <input type="hidden" id="txtFechaActual" value="<?php echo date('Y-m-d')?>" />
        
        &nbsp;
        
         <?php
		if($fecha!='fecha' or $idCompras!=0 or $idProveedor!=0)
		{
			echo '<img src="'.base_url().'img/quitar.png" style="width:22px; height:22px" title="Borrar busqueda" onclick="window.location.href=\''.base_url().'compras/productos\'" />';
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
			<th class="encabezadoPrincipal">Fecha entrega</th>
			<th class="encabezadoPrincipal">Proveeedor</th>
			<th class="encabezadoPrincipal">Orden de compra</th>
            <th class="encabezadoPrincipal">CRM</th>
			<th class="encabezadoPrincipal">Precio</th>
			<th class="encabezadoPrincipal">Pago</th>
			<th class="encabezadoPrincipal">Saldo</th>
			<th class="encabezadoPrincipal" style="width:33%">Acciones</th>             
		</tr>
	<?php
	$i=1;
	foreach ($compras as $compra)
	{
		$estilo		= $i%2>0?'class="sinSombra"':'class="sombreado"';

		$pagado		= $this->compras->obtenerPagado($compra['idCompras']);
		$saldo		= $compra['total']-$pagado;
		$onclick	= 'onclick="obtenerComprita('.$compra['idCompras'].')" title="Click para ver el detalle"';
		?>
	
		<tr <?php echo $estilo?>>
			<td align="left" valign="middle" <?php echo $onclick?>> <?php print($i); ?> </td>
			<td align="center" valign="middle" <?php echo $onclick?>><?php echo obtenerFechaMesCorto($compra['fechaEntrega']); ?></td>
			<td align="center" valign="middle" <?php echo $onclick?>>  <?php print($compra['empresa']); ?> </td>
			<td align="center" valign="middle" <?php echo $onclick?>> <a><?php print($compra['nombre']); ?> </a></td>
            
            <?php
			$seguimiento	= null;
			if(strlen($compra['idSeguimiento'])>0)
			{
				$seguimiento	= $this->crm->obtenerUltimoSeguimientoCompra($compra['idCompras']);
			}
			
			$mostrarSeguimiento=false;
			
			if($permisoCrm[0]->activo==1)
			{
				$mostrarSeguimiento=true;
			}
			
            echo'
			<td align="center" title="Click para ver detallles de seguimiento" '.($mostrarSeguimiento?($seguimiento!=null?'onclick="obtenerSeguimientoServicio('.$compra['idCompras'].','.$seguimiento->idSeguimiento.')"':'onclick="obtenerSeguimientoServicio('.$compra['idCompras'].',0)"'):'').' >';
				
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
            
            
			<td align="right" valign="middle" <?php echo $onclick?>>  $<?php print(number_format($compra['total'],2)); ?> </td>
			<td id="tdPagado<?php echo $compra['idCompras']?>" <?php echo $onclick?> align="right" valign="middle">$<?php print(number_format($pagado,2))?></td>
			<td id="tdSaldo<?php echo $compra['idCompras']?>" <?php echo $onclick?> align="right" valign="middle">$<?php print(number_format($saldo,2))?></td>
			<td align="center"   valign="middle"> 
			&nbsp;
			 
			<?php
			
			$imagen	= base_url()."img/success.png";
			
			$sql="select recibido from compra_detalles
			where idCompra='".$compra['idCompras']."'";
			
			$query=$this->db->query($sql);
			
			foreach($query->result() as $row)
			{
				if($row->recibido=="0")
				{
					$imagen=base_url()."img/Cerrar.png";
					break;
				}
			}
			
			echo '
			<img id="btnRecibirProductos'.$i.'" src="'.$imagen.'"  width="22" height="22" title="Recibido" onclick="obtenerProductosComprados('.$compra['idCompras'].');"style="cursor:pointer;"/>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <img id="btnPagosProveedor'.$i.'" onclick="obtenerPagosComprasProveedor('.$compra['idCompras'].')" src="'.base_url().'img/pagos.png" width="22" height="20" hspace="22" title="Pagos a proveedor"/>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	
			<img id="btnComprobantesCompras'.$i.'" src="'.base_url().'img/subir.png" width="22" onclick="obtenerComprobantesCompras('.$compra['idCompras'].',0)"  title="Comprobantes" />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			
			<a id="btnPdfCompras'.$i.'" onclick="window.open(\''.base_url().'compras/comprasPDFProductos/'.$compra['idCompras'].'/'.$this->session->userdata('idLicencia').'\')">
                <img src="'.base_url().'img/pdf.png" width="22" height="22"  title="PDF"  style="cursor:pointer;"/>
            </a>
                
            &nbsp;&nbsp;&nbsp;
            <img id="btnEnviarCompra'.$i.'" onclick="formularioEnviarCompra('.$compra['idCompras'].')" src="'.base_url().'img/correo.png" width="20" height="20" title="Enviar"/>
            &nbsp;&nbsp;&nbsp;&nbsp;
            <img id="btnCancelarCompra'.$i.'" onclick="cancelarCompra('.$compra['idCompras'].',\'Cancelar la compra borrara tambien sus pagos, ¿Desea continuar?\',\'productos\')" src="'.base_url().'img/cancelame.png" width="22" height="22" title="Cancelar compra" />
            &nbsp;&nbsp;&nbsp;&nbsp;
            <img id="btnBorrarCompra'.$i.'" onclick="borrarCompra('.$compra['idCompras'].',\'Borrar la compra borrara tambien sus pagos, ¿Desea continuar?\',\'productos\')" src="'.base_url().'img/quitar.png" width="22" height="22" title="Borrar compra" />
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
			
			if($compra['cancelada']=='1')
			{
				echo '
				<script>
					desactivarBotonSistema(\'btnEnviarCompra'.$i.'\');
					desactivarBotonSistema(\'btnPdfCompras'.$i.'\');
				</script>';
			}
			
			if($permiso[2]->activo==0 or $compra['cancelada']=='1')
			{
				echo '
				<script>
					desactivarBotonSistema(\'btnRecibirProductos'.$i.'\');
					desactivarBotonSistema(\'btnPagosProveedor'.$i.'\');
					desactivarBotonSistema(\'btnComprobantesCompras'.$i.'\');
				</script>';
			}
			
			if($permiso[3]->activo==0 or $compra['cancelada']=='1')
			{
				echo '
				<script>
					desactivarBotonSistema(\'btnCancelarCompra'.$i.'\');
					desactivarBotonSistema(\'btnBorrarCompra'.$i.'\');
				</script>';
			}

			
			/*if($compra['cancelada']=='0')
			{
				
				
			}*/
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
	echo'<div class="Error_validar" style="margin-top:10px; margin-bottom: 5px;">No hay registros de compras de productos</div>';
}
?>
	
<div style="visibility:hidden">

<div id="dialog-Compra" title="Compras">
<div style="width:99%;" id="id_CargandoCompra"></div>

<div id="ErrorCompra" class="ui-state-error" ></div>

<table class="admintable" width="99%;">
<form id="form1" name="form1" method="post" action="">
<tr>
	<td class="key">Concepto:</td>
    
		<td style="position:absolute; width:50%; border:none"> 
	<input type="text" style="width:90%; margin-bottom:8px"  name="inputString" id="inputString" class="cajas"   onkeyup="lookup(this.value);" onblur="fill();"/>
	<input type="hidden"  name="idPro" id="idPro"  />
    
	<div align="left" class="suggestionsBox" id="suggestions" style="display: none; ">
				<!--img src="<?php echo base_url()?>img/upArrow.png" style="position: relative; top: -12px; left: 30px;" /-->
				<div class="suggestionList" id="autoSuggestionsList">
					&nbsp;
				</div>
			</div>
	</td>
</tr>
<tr>
	<td class="key">Proveedor:</td>
	<td>
    <input type="text" name="proveedor" id="proveedor" 
    class="cajas" style="width:95%; border:none; background-color:#FFF;" value="" readonly="readonly"  /> </td>
</tr>
</form>

<tr>
	<td class="key">Precio Unitario:</td>
	<td>
    <input type="text" name="unitario" id="unitario" class="cajas" style="width:80px; border:none; background-color:#FFF"  /> 
	<td>
	</td>
</tr>


<tr>
	<td class="key">Cantidad:</td>
	<td><input type="text" name="cantidad" id="cantidad"  class="cajas" onkeyup="obtenerUnitario()" style="width:80px;" value=""  />  </td>
</tr>

<tr>
	<td class="key">Total:</td>
	<td><input type="text" name="totalCompra" id="totalCompra" onkeyup="obtenerUnitario()" class="cajas" style="width:100px;"  /> </td>
	<td>
	</td>
</tr>


</table>

</div>
</div>
<!-- Productos con sus materiales-->

<div id="dialog-Recibido" title="Recibir productos comprados">
<div style="width:99%;" id="recibiendoCompras"></div>
<div id="errorRecibirCompras" class="ui-state-error" ></div>
	<div id="cargarRecibidos"></div>
</div>

<div style="visibility:hidden">
<div id="dialogoDescuentos" title="Agregar descuento adicional">
<div style="width:99%;" id="cargandoDescuentos"></div>
<div id="ErrorDescuentos" class="ui-state-error" ></div>
<div id="cargarDescuentos"></div>
</div>
</div>

<div id="ventanaComprasProducto" title="Nueva compra de productos">
<div id="errorInformacionCliente" class="ui-state-error" ></div>
<div id="procesandoCompraProducto"></div>

<div id="busquedas" style="float:left; width:33%" class="" >
<label>Buscar:</label> 
<input type="text" class="cajas" style="width:300px" id="buscarNombre" name="buscarNombre" placeholder="Buscar producto" />
</div>
&nbsp;&nbsp;
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


<div id="productosKit" style="float:left; vertical-align:top; width:100%; margin-bottom:3px" >
</div> 
<table class="admintable" style="width:100%;">
    <tr>
        <th style="font-size:12px" colspan="5">
            <input type="hidden" id="paginaActiva" value="0" />
            <input type="hidden" id="paginaActivada" value="compras" />
            Orden de compra <input type="text" id="nombreKit" class="cajas" style="width:300px" value="OC-<?php echo $orden?>"/>  
            
            
            
            &nbsp;
			Fecha 
			<input type="text" id="txtFechaCompra" value="<?php echo date('Y-m-d H:i')?>" class="cajas" style="width:110px" readonly="readonly"/>
            <script>
				$('#txtFechaCompra').timepicker();
			</script>
            
            &nbsp;
            Fecha entrega
			<input type="text" id="txtFechaEntrega" value="<?php echo date('Y-m-d')?>" class="cajas" style="width:80px" readonly="readonly" />
            <script>
				$('#txtFechaEntrega').datepicker();
			</script>
            
            &nbsp;
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
    	<td style="width:80%">
        	<table class="admintable" style="width:100%;" id="armarKit">
                <tr>
                    <th width="3%">#</th>
					<th width="11%">Código</th>
                    <th width="14%">Nombre</th>
                    <th width="14%">Proveedor</th>
                    <th width="12%">Fecha entrega</th>
                    <th width="8%">Cantidad</th>
                    <th width="10%">Precio unitario</th>
                    <th width="8%">Descuento unitario</th>
                    <th width="12%">Total</th>
                </tr>
            </table>
        </td>
        <td style="width:19%; position:absolute">
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



<div id="ventanaRecibidoProductos" title="Recepción de productos">
<div class="ui-state-error" ></div>
<div id="recibiendoProductos"></div>
<div id="obtenerProductosRecibidos"></div>
</div>

<div id="ventanaComprobantesCompras" title="Comprobantes compras">
<div id="registrandoComprobanteCompra"></div>
<div id="obtenerComprobantesCompras"></div>
</div>

<div id="ventanaEnviarCompra" title="Enviar orden de compra por correo">
	<div id="enviandoCompra"></div>
	<div id="formularioEnviarCompra"></div>
</div>

<div id="ventanitaCompras" title="Detalles de compra">
<div id="errorComprita" class="ui-state-error" ></div>
<div  id="cargarComprita"></div>
</div>

<div id="ventanaRecibirTodosProductos" title="Recibir todos los productos">
<div class="ui-state-error" ></div>
<div id="recibiendoTodosProductos"></div>
<div id="formularioRecibirTodosProductos"></div>
</div>

<div id="ventanaCatalogoProductos" title="Catálogo de productos">
<div id="obtenerCatalogoProductos"></div>
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

<div id="ventanaAgregarProveedoresCompra" title="Enviar orden de compra por correo">
	<div id="asociandoProveedorProducto"></div>
	<div id="obtenerProveedoresCompra"></div>
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
