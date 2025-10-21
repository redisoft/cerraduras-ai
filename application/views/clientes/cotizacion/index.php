<script src="<?php echo base_url()?>js/cotizaciones/clientesCotizaciones.js"></script>
<script src="<?php echo base_url()?>js/cotizaciones/cotizacionesAdministracion.js"></script>
<script src="<?php echo base_url()?>js/cotizaciones/cotizacionesClientes.js"></script>
<script src="<?php echo base_url()?>js/cotizaciones/cancelar.js" ></script>
<script src="<?php echo base_url()?>js/cotizaciones/descuentos.js" ></script>
<script src="<?php echo base_url()?>js/ventas/ventasFacturacion.js"></script>
<script src="<?php echo base_url()?>js/ventas/sucursales.js"></script>
<script src="<?php echo base_url()?>js/informacion.js"></script>

<!--CRM DE SERVICIOS-->
<script src="<?php echo base_url()?>js/clientes/seguimiento/detalles.js"></script>
<script src="<?php echo base_url()?>js/clientes/seguimiento/archivos.js"></script>
<script src="<?php echo base_url()?>js/crm/clientes/servicios/servicios.js"></script>
<script src="<?php echo base_url()?>js/crm.js"></script>

<script type="text/javascript">
	$(document).ready(function()
	{
		$("#txtBusquedaCotizacion").autocomplete(
		{
			source:base_url+'configuracion/obtenerListaCotizaciones/<?php echo $idCliente?>',
			
			select:function( event, ui)
			{
				window.location.href=base_url+"clientes/cotizaciones/<?php echo $idCliente?>/0/"+ui.item.idCotizacion;
			}
		});
	});
</script>
<div class="derecha">
<div class="submenu">
<div class="breadcumb"><?php echo isset($breadcumb)?$breadcumb:''?></div>
<table class="toolbar" width="100%">  
	<!--<tr>
		<td class="seccion">Cotizaciones</td>
        <td colspan="3" align="left" valign="middle" style="font-size:14px"> 
			<?php
                echo 'Cliente: '.$cliente->empresa
            ?>
       </td>
   </tr>-->
   	
    <?php
		echo '
		
		<td class="button" width="5%">
			<a id="btnPuntoVenta"  style="cursor:pointer" href="'.base_url().'ventas/puntoVenta/'.(isset($idCliente)?$idCliente:'').'">
				<img src="'.base_url().'img/ventas.png" width="30px;" height="30px;" title="Registrar venta" alt="Registrar venta" /><br />
				
			   Punto de venta        
			</a>      
		</td>
		
		<td align="center" valign="middle" style="border:none" width="6%" >
			<a id="btnVentas" href="'.base_url().'clientes/ventas/'.$idCliente.'" class="toolbar" >
				<img src="'.base_url().'img/ventas.png" width="30px" title="Ver lista de ventas" style="vertical-align:middle;display:inline-table;cursor:pointer;" /> <br />
				Ventas
			</a>
	   </td>
	   
	   <td align="center" valign="middle" style="border:none" width="10%" >
			<a id="btnCotizaciones">
				<img src="'.base_url().'img/remision.png" width="30px" title="Cotizaciones" /> <br />
				Cotizaciones
			</a>
	   </td>
	   
	   <td align="center" valign="middle" style="border:none" width="5%" >
			<a id="btnCrm" href="'.base_url().'cotizaciones/llamadas/'.$idCliente.'" class="toolbar">
				<img src="'.base_url().'img/crm.png" width="30px" title="CRM" /> <br />
				CRM
			</a>
	   </td>';
	   
	   echo '
		<td align="center" valign="middle" style="border:none" width="10%" >         
			<a id="btnContactos" class="toolbar" href="'.base_url().'ficha/contactos/'.$idCliente.'" >
				<img src="'.base_url().'img/contactos.png" width="30px" id="" title="Contactos" style="vertical-align:middle;display:inline-table;cursor:pointer;" />  <br />
				Contactos                      
			</a>      
		</td>';	
		
		echo '
		<td align="center" valign="middle" style="border:none" width="10%" >         
			<a id="btnFacturas" class="toolbar" onclick="obtenerFacturasCliente()" >
				<img src="'.base_url().'img/pdf.png" width="30px" id="" title="Contactos" style="vertical-align:middle;display:inline-table;cursor:pointer;" />  <br />
				Facturas                      
			</a>      
		</td>';
		  
		echo '
		<script>
			desactivarBotonSistema(\'btnCotizaciones\');
		</script>';
		
        if($permisoVenta[0]->activo==0)
		{
			echo '
			<script>
				desactivarBotonSistema(\'btnVentas\');
			</script>';
		}
		
		if($permisoContacto[0]->activo==0)
		{
			echo '
			<script>
				desactivarBotonSistema(\'btnContactos\');
			</script>';
		}
		
		if($permisoFactura[0]->activo==0)
		{
			echo '
			<script>
				desactivarBotonSistema(\'btnFacturas\');
			</script>';
		}
		?>

        <td align="center" valign="middle" style="border:none" width="10%" >         
            <a class="toolbar" onclick="fichaTecnicaCliente(<?php echo $idCliente; ?>)" >
            <span class="icon-option" title="Ficha tecnica">
            	<img src="<?php print(base_url()); ?>img/fichaTecnica.png" width="30px" height="30px" title="Contactos" style="vertical-align:middle;display:inline-table;cursor:pointer;" />  
            </span>
            Ficha técnica                      
            </a>      
        </td>
        
        <td width="50%">
            <input type="text"  name="txtBusquedaCotizacion" id="txtBusquedaCotizacion" class="busquedas" placeholder="Buscar cotización"  style="width:300px;"/>
        	<?php
            if($idCotizacion!=0)
			{
				echo '<img src="'.base_url().'img/borrar.png" width="22" height="22" onclick="window.location.href=\''.base_url().'clientes/cotizaciones/'.$idCliente.'\'" style="cursor:pointer" />';
			}
			?>
  	    </td>
 </tr>
</table>
</div>


<div class="listproyectos">
<input type="hidden" name="id_cli" id="id_cli" value="<?php print($idCliente); ?>"  />
<input type="hidden"  name="txtIdServicioCrm" id="txtIdServicioCrm" value="1"/>
<input type="hidden"  name="txtIdClienteCrm" id="txtIdClienteCrm" value="0"/>

<table style="margin-left:33px; margin-top:19px; display: none" class="toolbar" width="10%">
    <tr>
        <td style="border:none" width="27%" align="center" valign="middle" class="button">
            <a id="btnRegistrarCotizacion" onclick="formularioCotizaciones(<?php echo $idCliente?>)"  >
                <img src="<?php print(base_url()); ?>img/add.png" alt="a" border="0" title="Agregar cotización" /> 
                <br />
                Agregar
            </a>
        </td>
    </tr>
</table>

<?php
if($permiso[1]->activo==0)
{ 
	echo '
	<script>
		desactivarBotonSistema(\'btnRegistrarCotizacion\');
	</script>';
}

if(!empty ($cotizaciones))
{
	echo'
	<div style="width:90%; margin-bottom:1%;">
		<ul id="pagination-digg" class="ajax-pagsR">'.$this->pagination->create_links().'</ul>
	</div>';
	 ?>
     
	<div id="cargandoImpresion" align="left" style="font-size:15px"></div>
	<table class="admintable" width="100%" style="margin-top:20px" >
	 <tr>
			<th class="encabezadoPrincipal" align="center" valign="middle">#</th>
			<th class="encabezadoPrincipal" align="center" valign="middle">Serie</th>
            <th class="encabezadoPrincipal" align="center" valign="middle">Folio</th>
			<th class="encabezadoPrincipal" align="center" valign="middle">Fecha entrega</th>
            <th class="encabezadoPrincipal" align="right" valign="middle">Subtotal</th>
            <th class="encabezadoPrincipal" align="right" valign="middle">Descuento</th>
            <th class="encabezadoPrincipal" align="right" valign="middle">Iva</th>
			<th class="encabezadoPrincipal" align="right" valign="middle">Total</th>
            <th class="encabezadoPrincipal">CRM</th>
			<th class="encabezadoPrincipal" align="center" valign="middle" style="width:25%">Acciones</th>
		</tr>
	
	<?php
		#$i=$inicio+1;
		$i=1;
		foreach($cotizaciones as $row)
		{
			$estilo		= $i%2>0?'class="sombreado"':'class="sinSombra"';
			$onclick	= 'onclick="obtenerCotizacionInformacion('.$row->idCotizacion.')"';
			?>
			
			<tr <?php echo $estilo?>>
			<td align="center" <?php echo $onclick?>>  <?php echo $i; ?> </td>
			<td align="center" <?php echo $onclick?>>  <?php echo $row->serie  ?> </td>
            <td align="center" <?php echo $onclick?>>  <?php echo $row->folioCotizacion  ?> </td>
			<td align="center" <?php echo $onclick?>>  <?php echo obtenerFechaMesCortoHora($row->fechaEntrega) ?> </td>
			<td align="right"  <?php echo $onclick?>>$<?php echo(number_format($row->subTotal,2)); ?></td>
            <td align="right"  <?php echo $onclick?>>$<?php echo(number_format($row->descuento,2)); ?></td>
            <td align="right"  <?php echo $onclick?>>$<?php echo(number_format($row->iva,2)); ?></td>
            <td align="right"  <?php echo $onclick?>>$<?php echo(number_format($row->total,2)); ?></td>
            <?php
			
			$seguimiento	= null;
			if(strlen($row->idSeguimiento)>0)
			{
				$seguimiento	= $this->crm->obtenerUltimoSeguimientoCotizacion($row->idCotizacion,$permisoCrm[4]->activo);
			}
			
			$mostrarSeguimiento=false;
			
			if($permisoCrm[0]->activo==1)
			{
				$mostrarSeguimiento=true;
			}
			
			echo'
			<td align="center" title="Click para ver detalles de seguimiento" '.($mostrarSeguimiento?($seguimiento!=null?'onclick="obtenerSeguimientoServicio('.$row->idCotizacion.','.$seguimiento->idSeguimiento.')"':'onclick="obtenerSeguimientoServicio('.$row->idCotizacion.',0)"'):'').' >';
				
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
            
			<td align="center" valign="middle"> 
			<?php 

				echo'
				&nbsp;&nbsp;
				<a title="Imprimir cotización" href="'.base_url().'pdf/cotizacionPdf/'.$row->idCotizacion.'/'.$this->session->userdata('idLicencia').'" target="_black">
					<img src="'.base_url().'img/printer.png" width="22px" height="22px"/>
				</a>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				
				<a id="btnConvertirVenta'.$i.'" title="Convertir cotización en venta"  onclick="accesoConvertirVenta('.$row->idCotizacion.')" style="cursor:pointer;">
					<img src="'.base_url().'img/basket_go_32.png" width="22px" height="22px" />
				</a>
				
				&nbsp;&nbsp;&nbsp;
				<a id="btnEditarCotizacion'.$i.'" title="Editar cotización" onclick="accesoEditarCotizacion('.$row->idCotizacion.')" >
					<img src="'.base_url().'img/edit.png" width="22px" height="22px" />
				</a>
				
				&nbsp;&nbsp;&nbsp;
				<img id="btnEnviarCotizacion'.$i.'" id="'.$row->idCotizacion.'" src="'.base_url().'img/correo.png" width="20" height="20" title="Enviar correo" onclick="formularioCorreo('.$row->idCotizacion.');" />
				&nbsp;&nbsp;&nbsp;
				
				<img id="btnCancelarCotizacion'.$i.'" onclick="accesoCancelarCotizacion('.$row->idCotizacion.')"  src="'.base_url().'img/cancelame.png" width="22px" height="22px" title="Cancelar cotización"  />
				&nbsp;&nbsp;&nbsp;&nbsp;
				<img id="btnBorrarCotizacion'.$i.'" onclick="accesoBorrarCotizacion('.$row->idCotizacion.',\'¿Realmente desea borrar la cotización: '.$row->serie.'?\','.$idCliente.')"  src="'.base_url().'img/borrar.png" width="22px" height="22px" title="Borrar cotización"  />
				&nbsp;&nbsp;
			   
				<br />
				<a>Imprimir</a>
				<a id="a-btnConvertirVenta'.$i.'">Venta</a>
				<a id="a-btnEditarCotizacion'.$i.'">Editar</a>
				<a id="a-btnEnviarCotizacion'.$i.'">Enviar</a>
				<a id="a-btnCancelarCotizacion'.$i.'">Cancelar</a>
				<a id="a-btnBorrarCotizacion'.$i.'">Borrar</a>';
				
				if($permiso[1]->activo==0 or $row->cancelada=='1')
				{
					echo '
					<script>
						desactivarBotonSistema(\'btnConvertirVenta'.$i.'\');
					</script>';
				}
				
				if($row->cancelada=='1')
				{
					echo '
					<script>
						desactivarBotonSistema(\'btnEnviarCotizacion'.$i.'\');
					</script>';
				}
				
				if($permiso[2]->activo==0 or $row->cancelada=='1')
				{
					echo '
					<script>
						desactivarBotonSistema(\'btnEditarCotizacion'.$i.'\');
					</script>';
				}

				if($permiso[3]->activo==0 or $row->cancelada=='1')
				{
					echo '
					<script>
						desactivarBotonSistema(\'btnCancelarCotizacion'.$i.'\');
						desactivarBotonSistema(\'btnBorrarCotizacion'.$i.'\');
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
		<ul id="pagination-digg" class="ajax-pagsR">'.$this->pagination->create_links().'</ul>
	</div>';
}
else
{
     echo
	 '<div class="Error_validar" style="margin-top:20px; width:95%; margin-bottom: 5px;">
       	No hay registro de cotizaciones.
      </div>';
}
?>


<div id="ventanaCotizaciones" title="Cotizaciones">
<div id="realizandoCotizacion"></div>
<div id="formularioCotizaciones"></div>
</div>

<div id="ventanaEditarCotizacion" title="Cotizaciones">
<input type="hidden" id="txtRecargar" value="1" />
<div id="procen"></div>
<div id="obtenerCotizacion"></div>
</div>

<div id="ventanaCorreo" title="Enviar cotización por correo:">
<div id="enviandoCorreo"></div>
<div id="formularioCorreo"></div>
</div>

<div id="ventanaFacturasCliente" title="Facturación">
<div class="ui-state-error" ></div>
<div id="generandoReporte"></div>
<table class="admintable" width="100%">
	<tr>
    	<td class="key">Busqueda por mes:</td>
        <td>
            <input type="text" class="cajas" id="txtMes" style="width:80px" placeholder="Seleccione" onchange="obtenerFacturasCliente()" />
            <input type="hidden" class="cajas" id="txtIdCliente" value="<?php echo $idCliente?>" />
        </td>
    </tr>
</table>
<div id="obtenerFacturasCliente"></div>
</div>

<div id="ventanaFichaCliente" title="Ficha técnica del cliente">
<div id="errorInformacionCliente" class="ui-state-error" ></div>
<div id="obtenerFichaCliente"></div>
</div>

<div id="ventanaConvertirVenta" title="Convertir cotización a venta">
<div id="conviertiendoVenta"></div>
<div id="obtenerDetallesCotizacion"></div>
</div>

<div id="ventanaProcesarCotizacion" title="Registrar cotización">
    <div id="registrandoCotizacion"></div>
    <div id="formularioProcesarCotizacion"></div>
</div>

<div id="ventanaFormularioEditarCotizacion" title="Editar cotización">
    <div id="editandoCotizacion"></div>
    <div id="formularioEditarCotizacion"></div>
</div>

<div id="ventanaStockSucursales" title="Stock sucursales">
	<div id="obtenerStockSucursales"></div>
</div>

<div id="ventanaCotizacionesInformacion" title="Detalles de cotización">
<div id="obtenerCotizacionInformacion"></div>
</div>

<div id="ventanaAsignarDescuento" title="Asignar descuento">
	<table class="admintable" width="100%">
    	<tr>
        	<td class="key">Descuento:</td>
            <td><input type="text" class="cajas" id="txtAsignarDescuento" value="0" onkeypress="return soloDecimales(event)" maxlength="6" /></td>
        </tr>
    </table>
</div>

<div id="ventanaEnviarFichaCliente" title="Enviar ficha técnica del cliente">
    <div id="enviandoFichaCliente"></div>
    <div class="ui-state-error" ></div>
    <div id="formularioCorreoFichaCliente"></div>
</div>

<?php $this->load->view('clientes/seguimiento/crmServicios/modalesSeguimientoServicios');?>

</div>
</div>
