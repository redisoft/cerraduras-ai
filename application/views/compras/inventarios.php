<script src="<?php echo base_url()?>js/inventario.js"></script>
<script src="<?php echo base_url()?>js/comprobantesCompras.js"/></script>
<script src="<?php echo base_url()?>js/enviarCompra.js"/></script>
<script src="<?php echo base_url()?>js/compras/informacionCompras.js"></script>  

<div class="derecha">
<div class="submenu">
<div class="toolbar" id="toolbar">
 <table class="toolbar" style="width:100%">	
 	<tr>
    <td class="seccion" colspan="3">
    	Compras de Mobiliario/equipo
   	    </td>
    </tr>
    <tr>
     	
     <?php
		if($permiso->escribir=='1')
		{ 
			?>
     		<td align="center" valign="middle" style="width:13%" >
			<?php print('<img src="'.base_url().'img/comprasInventario.png" width="30px;" height="30px;" class="productos" 
            id="productos" style="cursor:pointer;" title="Nueva compra">'); ?>  <br />
            Nueva compra		 
            </td>
            <?php
		}
        ?>

		<td align="left" valign="middle" style="width:84%; padding-right:100px">
			<input type="text" class="busquedas" placeholder="Seleccione fecha" onchange="busquedaFecha()" style="width:120px" id="FechaDia" />
        	<input type="text" class="busquedas" placeholder="Buscar por proveedor" style="width:500px" id="txtProveedorCompra" />
        	<input type="text" class="busquedas" placeholder="Buscar por orden"  style="width:150px" id="txtBusquedaOrden" />
        
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
	?>
	<div style="width:90%; margin-bottom:1%;">
	 <?php
	 print("<ul id='pagination-digg' class='ajax-pagin'>");
	 print($this->pagination->create_links());
	 print("</ul>");
	 ?>
	</div>
	
	<table class="admintable" width="100%">
		<tr>
			<th class="encabezadoPrincipal">#</th>
			<th class="encabezadoPrincipal">Fecha</th>
			<th class="encabezadoPrincipal">Proveeedor</th>
			<th class="encabezadoPrincipal">Orden de compra</th>
			<th class="encabezadoPrincipal">Precio</th>
			<th class="encabezadoPrincipal">Pago</th>
			<th class="encabezadoPrincipal">Saldo</th>
			<th class="encabezadoPrincipal" style="width:32%">Acciones</th>             
		</tr>
	<?php
	$i=1;
	foreach ($compras as $compra)
	{
		$estilo		=$i%2>0?'class="sinSombra"':'class="sombreado"';

		$pagado		=$this->compras->obtenerPagado($compra->idCompras);
		$saldo		=$compra->total-$pagado;
		?>
	
		<tr <?php echo $estilo?>>
			<td align="left" valign="middle"> <?php echo $i ?> </td>
			<td align="center" valign="middle"><?php print(substr($compra->fechaCompra,0,11)); ?></td>
			<td align="center" valign="middle">  <?php print($compra->empresa); ?> </td>
			<td align="center" valign="middle" onclick="obtenerComprita(<?php echo $compra->idCompras?>)"> <a><?php print($compra->nombre); ?> </a></td>
			<td align="right" valign="middle">  $<?php print(number_format($compra->total,2)); ?> </td>
			<td id="tdPagado<?php echo $compra->idCompras?>" align="right" valign="middle">$<?php print(number_format($pagado,2))?></td>
			<td id="tdSaldo<?php echo $compra->idCompras?>" align="right" valign="middle">$<?php print(number_format($saldo,2))?></td>
			<td align="left"   valign="middle"> 
			&nbsp;
			 
			  <?php
			if($permiso->escribir=='1')
			{ 
				?>
				<a onclick="borrarCompra(<?php echo $compra->idCompras?>,'Borrar la compra borrara tambien sus pagos, ¿Desea continuar?','inventarios')">
					<img src="<?php echo base_url()."img/quitar.png"?>" width="22" height="22" hspace="3" title="Borrar compra" />
                </a> 
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				
				<?php
				$imagen=base_url()."img/success.png";
				
				$sql="select recibido from compra_detalles
				where idCompra='".$compra->idCompras."'";
				
				$query=$this->db->query($sql);
				
				foreach($query->result() as $row)
				{
					if($row->recibido=="0")
					{
						$imagen=base_url()."img/Cerrar.png";
						break;
					}
				}
				?>
				
                <img src="<?php echo $imagen?>" id="btnRecibirCompras<?php echo $i?>" 
                width="22" height="22" hspace="3" title="Recibido"  
                onclick="obtenerProductosComprados('<?php echo $compra->idCompras?>');"style="cursor:pointer;"/>
                
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                
                <img onclick="obtenerPagosComprasProveedor('<?php echo $compra->idCompras?>')" src="<?php echo base_url()."img/pagos.png"?>" width="22" height="22" title="Pagos a proveedor"/>
                
				
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<?php
				
				 echo '<img src="'.base_url().'img/subir.png" width="22"  onclick="obtenerComprobantesCompras('.$compra->idCompras.')"  title="Comprobantes" />
                 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
					
			}
			?>
			<a onclick="window.open('<?php echo base_url()."compras/comprasPDFInventarios/".$compra->idCompras.'/'.$this->session->userdata('idLicencia')?>')">
			<img src="<?php echo base_url()."img/pdf.png"?>" 
			width="22" height="22" hspace="3" title="PDF"  style="cursor:pointer;"/></a>
			
			&nbsp;&nbsp;&nbsp;&nbsp;
			<img onclick="formularioEnviarCompra('<?php echo $compra->idCompras?>')" src="<?php echo base_url()."img/correo.png"?>" width="20" height="20"  title="Enviar" />
		
			<br />
			
			 <?php
			if($permiso->escribir=='1')
			{
				 echo'
				 <a>Borrar </a>
				&nbsp;&nbsp; 
				<a>Recibido</a>
				&nbsp;&nbsp; 
				<a>Pagos</a>
				&nbsp;
				<a>Comprobantes</a>';
			}
			?>
			&nbsp;
			<a>PDF</a>
			 &nbsp;&nbsp;
			<a>Enviar</a
			></td>
		</tr>
		<?php
		$i++;
	 }
	?>
	</table>
	
	<div style="width:90%; margin-bottom:1%;">
	 <?php
	 print("<ul id='pagination-digg' class='ajax-pagin'>");
	 print($this->pagination->create_links());
	 print("</ul>");
	 ?>
	</div>
	
	<?php
	
}
else
{
	echo'<div class="Error_validar" style="margin-top:10px; margin-bottom: 5px;">No hay registros de compras de Mobiliario/equipo</div>';
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

<div id="ventanaRecibirProducto" title="Recibir productos comprados">
<div style="width:99%;" id="recibiendoProductos"></div>
<div id="errorRecibiendoProductos" class="ui-state-error" ></div>
	<div id="formularioProductosRecibidos"></div>
</div>


<div id="dialog-Recibido" title="Recibir productos comprados">
<div style="width:99%;" id="recibiendoCompras"></div>
<div id="errorRecibirCompras" class="ui-state-error" ></div>
	<div id="cargarRecibidos"></div>
</div>




<div id="ventanaProducto" title="Nueva compra de Mobiliario/equipo">
<div id="errorInformacionCliente" class="ui-state-error" ></div>
<div style="width:99%;" id="id_CargandoListaProductos"></div>

<div id="busquedas" style="float:left; width:25%" class="" >
<label>Buscar:</label> 
<input type="text" class="cajas" style="width:200px" id="txtCriterio" 
name="txtCriterio"  onkeyup="listaProductosServicios()"/>
</div>

<div id="listaProveedores" style="float:left; width:48">
<label>Proveedor:</label> 
<input type="hidden" id="proveedores" value="0" />
<input type="text" class="cajas" id="txtProveedores" placeholder="Buscar proveedor" style="width:500px" />

<!--<select class="cajas" style="width:auto" id="proveedores" onchange="confirmarProveedor()">
<option value="0">Todos</option>
<?php
foreach($proveedores as $row)
{
	print('<option value="'.$row->id.'">'.$row->empresa.'</option>');
}
?>
</select>-->
</div>
<div id="productosKit" style="float:left; vertical-align:top; width:100%; margin-bottom:3px" >
</div> 
<table class="admintable" style="width:100%;">
    <tr>
        <th style="font-size:12px" colspan="5">
        <input type="hidden" id="paginaActiva" value="0" />
        <input type="hidden" id="paginaActivada" value="compras" />
        Orden de  compra <input type="text" id="nombreKit" class="cajas" style="width:300px" value="OC-<?php echo $orden?>"/>  
        
        <script>
			$('#txtFechaCompra').timepicker();
		</script>
		Fecha 
		<input type="text" id="txtFechaCompra" value="<?php echo date('Y-m-d H:i')?>" class="cajas" style="width:160px" />
		
        Días de crédito 
		<input type="text" id="txtDiasCredito" value="0" class="cajas" style="width:100px" /> 
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
                    <th>Precio unitario</th>
                    <th>Cantidad</th>
                    <th>Total</th>
                </tr>
            </table>
        </td>
        <td style="width:24%; position:absolute">
        	<table class="admintable" style="width:100%;">
            	<tr>
                	<td class="key">SubTotal</td>
                    <td>
                    	<input type="text" id="kitTotal" style="width:100px;" readonly="readonly" name="kitTotal" class="cajas" value="0" />
                    </td>
                </tr>
                <tr>
                	<td class="key">Descuento</td>
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
                	<td class="key">IVA <?php echo number_format($this->session->userdata('iva'),2)?>%</td>
                    <td>
                    	<input type="hidden" id="txtIvaPorcentaje" name="txtIvaPorcentaje" value="<?php echo $this->session->userdata('iva')?>" />
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

<!--<table class="admintable" style="width:100%; margin-top:4px" id="armarKit">
<tr>
    <th>#</th>
    <th>Nombre</th>
    <th>Precio unitario</th>
    <th>Cantidad</th>
    <th>Total</th>
</tr>
</table>-->

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

</div>
<!-- Termina -->
</div>
