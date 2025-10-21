<script src="<?php echo base_url()?>js/compras/materiales/comprar.js"/></script>

<script src="<?php echo base_url()?>js/compras/comprobantesCompras.js"/></script>
<script src="<?php echo base_url()?>js/compras/enviarCompra.js"/></script>
<script src="<?php echo base_url()?>js/compras/informacionCompras.js"></script>  
<script src="<?php echo base_url()?>js/administracion/comprobantesEgresos.js"></script>
<script src="<?php echo base_url()?>js/materiales/catalogo.js"></script>
<script src="<?php echo base_url()?>js/materiales/asociar.js"></script>
<script src="<?php echo base_url()?>js/compras/terminos.js"/></script>


<!--CRM DE SERVICIOS-->
<script src="<?php echo base_url()?>js/proveedores/seguimiento/detalles.js"></script>
<script src="<?php echo base_url()?>js/proveedores/seguimiento/archivos.js"></script>
<script src="<?php echo base_url()?>js/crm/proveedores/servicios/servicios.js"></script>
<script src="<?php echo base_url()?>js/crm.js"></script>

<!--ANDEN-->
<script src="<?php echo base_url()?>js/compras/materiales/anden.js"/></script>




<script>
$(document).ready(function()
{
	calcularTotales();
});
</script>

<!--<script src="<?php echo base_url()?>js/materiales/materiales.js"/></script>-->


<div class="derecha">
<div class="submenu">
<div class="breadcumb"><?php echo isset($breadcumb)?$breadcumb:''?></div>
<div class="toolbar" id="toolbar">
 <table class="toolbar" style="width:100%">	
 	<!--<tr>
    	<td class="seccion" colspan="2">
    		Compras de materia prima
   	  	</td>
    </tr>-->
    <tr>
     	
     <?php
		if($idRol!=6)
		{
			echo'
			<td align="center" valign="middle" style="width:30%" >
				<a id="btnCompras" onclick="formularioCompras()" title="Nueva compra">
					<img src="'.base_url().'img/compras.png" width="30px;" height="30px;" style="cursor:pointer;"  >
					<br />
					Nueva compra	
				</a>
				
				<a id="btnMateriales" onclick="obtenerCatalogoMateriales()" title="Catálogo de materia prima">
					<img src="'.base_url().'img/materiales.png"  width="30px;" height="30px;" style="cursor:pointer;" >
					<br />
					'.(sistemaActivo=='IEXE'?'Insumos':'Materia prima').'
				</a>	
				
				<a id="btnRequisiciones" onclick="obtenerRequisiciones()" title="Requisiciones">
					<img src="'.base_url().'img/requisicion.png"  width="30px;" height="30px;" style="cursor:pointer;" >
					<br />
					Requisiciones <br /> abiertas
				</a>
				
				<a id="btnRequisiciones" onclick="obtenerRequisicionesProcesadas()" title="Requisiciones procesadas">
					<img src="'.base_url().'img/requisicion.png"  width="30px;" height="30px;" style="cursor:pointer;" >
					<br />
					Requisiciones <br /> procesadas
				</a>
						 
			</td>';
		}

		if($permiso[1]->activo==0)
		{
			 echo '
			<script>
				desactivarBotonSistema(\'btnCompras\');
			</script>';
		}
		
		if($permisoMateriales[0]->activo==0)
		{
			 echo '
			<script>
				desactivarBotonSistema(\'btnMateriales\');
			</script>';
		}
        ?>

            
      <td align="left" valign="middle" style="width:70%; ">
      	Filtro de
        <input type="text" class="busquedas" placeholder="Fecha" onchange="obtenerComprasMateriales()" style="width:100px" id="txtInicioCompras" readonly="readonly"  value="<?php echo date('Y-m-d')?>"/>
        a
        <input type="text" class="busquedas" placeholder="Fecha" onchange="obtenerComprasMateriales()" style="width:100px" id="txtFinCompras" readonly="readonly" value="<?php echo date('Y-m-d')?>"/>
        
        <input type="text" class="busquedas" placeholder="Buscar por orden, proveedor" style="width:360px" id="txtCriterioCompras" />
        <!--<input type="text" class="busquedas" placeholder="Buscar por orden"  style="width:110px" id="txtBusquedaOrden" />-->
        
        <input type="hidden"  name="txtIdServicioCrm" id="txtIdServicioCrm" value="3"/>
        <input type="hidden"  name="txtIdProveedorCrm" id="txtIdProveedorCrm" value="0"/>
        <input type="hidden" id="txtFechaActual" value="<?php echo date('Y-m-d')?>" />
        
        &nbsp;
        
         <?php
		/*if($fecha!='fecha' or $idCompras!=0 or $idProveedor!=0)
		{
			echo '<img src="'.base_url().'img/quitar.png" style="width:22px; height:22px" title="Borrar busqueda" onclick="window.location.href=\''.base_url().'compras/administracion\'" />';
		}*/
      ?>         
         
        </td>
    </tr>
 </table>
 </div>
</div>

<div class="listproyectos">
<div class="procesandoCompras">

<div id="obtenerComprasMateriales"></div>


<!-- Productos con sus materiales-->

<div id="ventanaRecibirProducto" title="Recibir productos comprados">
<div id="recibiendoProductos"></div>
<div id="formularioProductosRecibidos"></div>
</div>

<div id="ventanaRecibirCompras" title="Recibir productos comprados">
<div id="id_CargandoRecibido"></div>
<div id="cargarProductosRecibidos"></div>
</div>


<div id="ventanaComprasMateriales" title="Nueva compra por proveedor">
<div class="ui-state-error" ></div>
<div id="procesandoComprasMateria"></div>

<div id="busquedas" style="float:left; width:35%" class="" >
    <label>Buscar:</label> 
    <input type="text" class="cajas" style="width:300px" id="buscarNombre" name="buscarNombre" placeholder="Buscar materia prima" />
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


<div id="obtenerMaterialesCompra" style="float:left; vertical-align:top; width:100%; margin-bottom:3px" >
</div> 
<table class="admintable" style="width:100%;">
    <tr>
        <th style="font-size:12px" colspan="5">
            <input type="hidden" id="paginaActiva" value="0" />
            <input type="hidden" id="paginaActivada" value="compras" />
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

<!--Es para agregar materia prima -->

<!--<div id="ventanaMateriales" title="Materia prima">
<div id="registrandoMateriaPrima"></div>
<div id="formularioMateriaPrima"></div>
</div>-->






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


<div id="ventanaRecibirTodosMateriales" title="Recibir todos los materiales">
<div class="ui-state-error" ></div>
<div id="recibiendoTodosMateriales"></div>
<div id="formularioRecibirTodosMateriales"></div>
</div>

<div id="ventanaComprobantesEgresos" title="Comprobantes gastos">
    <div id="registrandoComprobanteEgreso"></div>
    <div id="obtenerComprobantesEgresos"></div>
</div>

<div id="ventanaCatalogoMateriales" title="Catálogo de <?php echo sistemaActivo=='IEXE'?'Insumos':'Materia prima' ?>">
<div id="obtenerCatalogoMateriales"></div>
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

<div id="ventanaAgregarProveedorCompra" title="Agregar proveedor">
<div id="asociandoProveedorCompra"></div>
<div id="obtenerProveedoresCompraAsociar"> </div>
</div>

<div id="ventanaAnden" title="Recepciones anden">
<div id="recibiendoAnden"></div>
<div id="recepcionesAnden"> </div>
</div>

<?php $this->load->view('clientes/seguimiento/crmServicios/modalesSeguimientoServicios');?>

<?php $this->load->view('requisiciones/compras/index')?>

<?php $this->load->view('proveedores/catalogo/index')?>

</div>
</div>
</div>
