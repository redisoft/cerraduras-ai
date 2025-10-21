<script src="<?php echo base_url()?>js/ventas/catalogo/ventas.js"></script>
<script src="<?php echo base_url()?>js/ventas/ventasFacturacion.js"></script>
<script src="<?php echo base_url()?>js/informacion.js"></script>
<script src="<?php echo base_url()?>js/facturacion/folios.js"></script>
<script src="<?php echo base_url()?>js/ventas/entregas.js"></script>
<script src="<?php echo base_url()?>js/reportes/facturacion/administracion.js"></script>
<script src="<?php echo base_url()?>js/ventas/devoluciones/devoluciones.js"></script>
<script src="<?php echo base_url()?>js/ventas/devoluciones/notas.js"></script>
<script src="<?php echo base_url()?>js/ventas/devoluciones/dinero.js"></script>
<script src="<?php echo base_url()?>js/cotizaciones/descuentos.js" ></script>
<script src="<?php echo base_url()?>js/configuracion/motivos/catalogo.js"></script>

<!--VENTAS-->
<script src="<?php echo base_url()?>js/clientes/catalogo.js"></script>

<script src="<?php echo base_url()?>js/ventas/ventas.js"></script>
<script src="<?php echo base_url()?>js/ventas/ventasFacturas.js"></script>
<script src="<?php echo base_url()?>js/ventas/sucursales.js"></script>
<script src="<?php echo base_url()?>js/configuracion/zonas/catalogo.js"></script>

<!--CRM DE SERVICIOS-->
<script src="<?php echo base_url()?>js/clientes/seguimiento/detalles.js"></script>
<script src="<?php echo base_url()?>js/clientes/seguimiento/archivos.js"></script>
<script src="<?php echo base_url()?>js/crm/clientes/servicios/servicios.js"></script>
<script src="<?php echo base_url()?>js/crm.js"></script>

<script src="<?php echo base_url()?>js/ventas/catalogo/acrilico.js"></script>

<script src="<?php echo base_url()?>js/bibliotecas/chartjs/bundle.js"></script>
<script src="<?php echo base_url()?>js/bibliotecas/chartjs/utils.js"></script> 


<script>
var config = {
            type: 'line',
            data: {
                labels: ["January", "February", "March", "April", "May", "June", "July"],
                datasets: [
				
				{
                    label: "VENTAS",
                    backgroundColor: window.chartColors.green,
                    borderColor: window.chartColors.green,
                    data: [
                        100,
                        150,
                        320,
                        170,
                        100,
                        120,
                        130
                    ],
                    fill: true,
                }]
            },
            options: 
			{
                responsive: true,
                title:
				{
                    display:false,
                    text:'Chart.js Line Chart'
                },
                tooltips: 
				{
                    mode: 'index',
                    intersect: false,
                },
                hover: {
                    mode: 'nearest',
                    intersect: true
                },
                scales: 
				{
                    xAxes: [
					{
                        display: false,
                        scaleLabel: 
						{
                            display: false,
                            labelString: 'Month'
                        }
                    }],
                    yAxes: [{
                        display: false,
                        scaleLabel: {
                            display: false,
                            labelString: 'Value'
                        }
                    }]
                }
            }
        };

        window.onload = function() {
            var ctx = document.getElementById("canvas").getContext("2d");
            window.myLine = new Chart(ctx, config);
        };


$(document).ready(function()
{
	obtenerVentas();
	$('#txtFechaInicioVentas,#txtFechaFinVentas').datepicker();

});
</script>


<div class="derecha">
<div class="submenu" <?php echo !$mostrarMenu?'style="height:0px"':'style="height:auto"'?>>
	<div class="breadcumb"><?php echo isset($breadcumb)?$breadcumb:''?></div>
    
    <?php
	if($mostrarMenu)
	{
		echo '<div style="width:50%; float: left">';
		if($cliente!=null)
		{
			echo '
			<table class="admintable" width="100%;">
				<tr>
					<th colspan="4"> Datos del cliente</th>
				</tr>
				<tr>
					<td style="width:15%" class="key">Empresa:</td>
					<td style="width:35%" >'.$cliente->empresa.'</td>
					<td style="width:15%" class="key">Grupo:</td>
					<td>'.$cliente->grupo.'</td>
				</tr>
				
				<tr>
					<td class="key">Calle:</td>
					<td>'.$cliente->calle.'</td>
					<td class="key">Número:</td>
					<td>'.$cliente->numero.'</td>
				</tr>
				
				<tr>
					<td class="key">Colonia:</td>
					<td>'.$cliente->colonia.' </td>
					<td class="key">Localidad:</td>
					<td>'.$cliente->localidad.' </td>
				</tr>
				
				<tr>
					<td class="key">Municipio:</td>
					<td>'.$cliente->municipio.' </td>
					<td class="key">Estado:</td>
					<td>'.$cliente->estado.' </td>
				</tr>
			
				<tr>
					<td class="key">Teléfono:</td>
					<td>'.$cliente->telefono.'</td>
					<td class="key">Email:</td>
					<td>'.$cliente->email.'</td>
				</tr>
			</table>';
		}
		
		echo '</div>';
		
		echo '
		<div style="width:25%; float: right">
		
			 <canvas id="canvas"></canvas>
			
		</div>';
	}
	?> 
	
    <table class="toolbar" width="100%">
    	 <tr>
         	<!--<td class="seccion">Ventas</td>-->
			<?php
            if($mostrarMenu)
            {
                #echo '<td colspan="4" align="left" style="text-align:left; font-size:14px">Cliente: '.$cliente->empresa.' </td>';
				
				#echo '<td colspan="5" align="left" style="text-align:left; font-size:14px">'.$breadcumb.'</td>';
			}
			?> 
	   <tr>
		
        
			
		<?php
		#onclick="formularioVentas()"
		echo '
		<td class="button" width="6%">
			<a id="btnPuntoVenta"  href="'.base_url().'ventas/puntoVenta/'.(isset($idCliente)?$idCliente:'').'">
				<img src="'.base_url().'img/ventas.png" width="30px;" height="30px;" title="Registrar venta" alt="Registrar venta" /><br />
				
			   Punto de venta        
			</a>      
		</td>';
		
		if($mostrarMenu)
		{
			echo '
			<td class="button" width="6%" >
				<a id="btnVentas" class="toolbar">
					<img src="'.base_url().'img/almacen.png" width="30px"  height="30px;"title="Ventas" /> <br />
					Ventas
				</a>
		   	</td>
		   
			<td align="center" valign="middle" width="6%" >
				<a id="btnCotizaciones" href="'.base_url().'clientes/cotizaciones/'.$idCliente.'" class="toolbar" id="Id_Cotizacioness">
					<img src="'.base_url().'img/remision.png" width="30px" title="Ver lista de cotizaciones" /> <br />
					Cotizaciones
				</a>
		   </td>
		   
		   <td align="center" valign="middle" style="border:none" width="6%" >
				<a id="btnCrm" href="'.base_url().'cotizaciones/llamadas/'.$idCliente.'" class="toolbar">
					<img src="'.base_url().'img/crm.png" width="30px" title="CRM" /> <br />
					CRM
				</a>
		   </td> ';
		   
		   echo '
			<td align="center" valign="middle" style="border:none" width="6%" >         
				<a id="btnContactos" class="toolbar" href="'.base_url().'ficha/contactos/'.$cliente->idCliente.'" >
					<img src="'.base_url().'img/contactos.png" width="30px" id="" title="Contactos" />  <br />
					Contactos                      
				</a>      
			</td>';	
			
			echo '
			<td align="center" valign="middle" style="border:none" width="6%" >         
				<a id="btnFacturas" class="toolbar" onclick="obtenerFacturasCliente()" >
					<img src="'.base_url().'img/pdf.png" width="30px" id="" title="Contactos" />  <br />
					Facturas                      
				</a>      
			</td>';
			
			echo '
				<script>
					desactivarBotonSistema(\'btnVentas\');
				</script>';
		   
			if($permisoCotizacion[0]->activo==0)
			{
				echo '
				<script>
					desactivarBotonSistema(\'btnCotizaciones\');
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
	
			<td align="center" valign="middle" style="border:none" width="6%" >         
				<a class="toolbar" onclick="fichaTecnicaCliente(<?php echo $cliente->idCliente; ?>)" >
				<span class="icon-option" title="Ficha técnica">
					<img src="<?php print(base_url()); ?>img/fichaTecnica.png" width="30px" height="30px" title="Ficha técnica" />  
				</span>
				Ficha técnica                      
				</a>      
			</td>
			
			<?php
		}
		?>
        
        <td></td>
       
        </tr>
        
    </table>
    
    <table width="100%">
    	<tr>
        	
       
    	 <td align="center">
        
        	 <input type="text" id="txtFechaInicioVentas" class="busquedas" value="<?php echo date('Y-01-01')?>" style="width:100px;" onchange="obtenerVentas()"/>
             <input type="text" id="txtFechaFinVentas" class="busquedas" value="<?php echo date('Y-m-d')?>" style="width:100px;" onchange="obtenerVentas()"/>
              
              
            <input type="text" id="txtBusquedaVentas" class="busquedas" placeholder="Buscar nota, factura" style="width:400px; "/>
            <input type="hidden"  name="txtPaginaActiva" id="txtPaginaActiva" value="ventas"/>
            
  	    </td>
         </tr>
    </table>
</div>
<div class="listproyectos" style="float:none">
<input type="hidden" name="txtClienteId" id="txtClienteId" value="<?php echo $idCliente ?>"  />
<input type="hidden"  name="txtIdTienda" id="txtIdTienda" value="0"/>
<input type="hidden"  name="txtModuloVentas" id="txtModuloVentas" value="1"/>
<input type="hidden"  name="txtOrdenVentas" id="txtOrdenVentas" value="desc"/>
<input type="hidden"  name="txtSeccion" id="txtSeccion" value="<?php echo $seccion?>"/>

<input type="hidden"  name="txtIdServicioCrm" id="txtIdServicioCrm" value="2"/>
<input type="hidden"  name="txtIdClienteCrm" id="txtIdClienteCrm" value="0"/>

<?php
if(!$mostrarMenu) echo '<br /><br /><br /><br />';
?>

<div id="procesandoVentas" style="margin-top:10px"></div>
<div id="obtenerVentas" style="margin-top:10px">
	<input type="hidden"  name="selectVentasBusqueda" 	id="selectVentasBusqueda" value="<?php echo $idCotizacion?>"/>
    <input type="hidden"  name="selectFacturasBusqueda" id="selectFacturasBusqueda" value="0" />
</div>

<div id="ventanaEntregarProductos" title="Entrega de productos:">
<div id="entregandoProductos"></div>
<div class="ui-state-error" ></div>
    <table class="admintable" width="100%;">
        <tr>
            <td class="key">Fecha:</td>
            <td>
            	<input name="FechaDia" id="FechaDia" type="text" class="cajasSelect" value="<?php echo date("Y-m-d");?>" />
            </td>
        </tr>	
        <tr>
            <td class="key">Cantidad:</td>
            <td>
            	<input type="text" class="cajasSelect" name="txtCantidadEntregar" id="txtCantidadEntregar" />
            </td>
        </tr>	
        <tr>
            <td class="key">Entrego:</td>
            <td>
            	<input type="text" name="txtEntrego" id="txtEntrego" class="cajas" style="width:160px;" /> 
            </td>
        </tr>
    </table>
	<div id="entregaProductos"></div>
</div>

<div style="visibility:hidden">
<div id="dialog-Entregados" title="Productos entregados:">
 <div id="ErrorEntregados" class="ui-state-error" ></div>
	<div id="productosEntregados"></div>
	</div>
</div>
</div>

<div id="ventanaCorreo" title="Enviar orden de venta por correo:">
<div id="enviandoCorreo"></div>
<div id="formularioCorreo"></div>
</div>

<!--<div id="ventanaDevolucionesVentas" title="Devoluciones de productos">
<div id="errorDevolucionesProductos" class="ui-state-error" ></div>
<div id="cargandoDevolucionesProductos"></div>
<div id="cargarDevolucionesProductos"></div>
</div>-->

<!--<div style="visibility:hidden">
    <div id="dialog-Entregados" title="Productos entregados:">
        <div id="ErrorEntregados" class="ui-state-error" ></div>
        <div id="productosEntregados"></div>
    </div>
</div>-->

<div id="ventanaFacturaParcial" title="Facturar parcial">
<div id="facturandoParcial"></div>
<div id="facturaParcial"></div>
</div>

<div id="ventanaFacturacion" title="Facturar venta">
    <div id="facturando"></div>
    <div id="errorFacturacion" class="ui-state-error" ></div>
    <div id="generandoZip"></div>
    <div id="obtenerDatosFactura"></div>
</div>



<?php
if($mostrarMenu)
{
	echo'
	<div id="ventanaFacturasCliente" title="Facturación">
		<div class="ui-state-error" ></div>
		<div id="generandoReporte"></div>
			<table class="admintable" width="100%">
				<tr>
					<td class="key">Busqueda por mes:</td>
					<td>
						<input type="text" class="cajas" id="txtMes" style="width:80px" placeholder="Seleccione" onchange="obtenerFacturasCliente()" />
						<input type="hidden" class="cajas" id="txtIdCliente" value="'.$cliente->idCliente.'" />
					</td>
				</tr>
			</table>
		<div id="obtenerFacturasCliente"></div>
	</div>
	
	<div id="ventanaFichaCliente" title="Ficha técnica del cliente">
		<div id="errorInformacionCliente" class="ui-state-error" ></div>
		<div id="obtenerFichaCliente"></div>
	</div>';
}
?>


<div id="ventanaVentasInformacion" title="Detalles de venta">
<div id="obtenerVentaInformacion"></div>
</div>

<div id="ventanaEnviarCorreoFactura" title="Enviar factura por correo electrónico">
    <div id="enviandoCorreoFactura"></div>
    <div class="ui-state-error" ></div>
    <div id="formularioCorreoFactura"></div>
</div>

<div id="ventanaDevoluciones" title="Devoluciones">
    <div id="procesandoDevoluciones"></div>
    <div class="ui-state-error" ></div>
    <div id="obtenerDevoluciones"></div>
</div>

<div id="ventanaDatosNota" title="Nota de Crédito">
    <div class="ui-state-error" ></div>
    <div id="obtenerDatosNota"></div>
</div>

<div id="ventanaDineroDevolucion" title="Devolución dinero">
    <div class="ui-state-error" ></div>
    <div id="obtenerFormularioDinero"></div>
</div>

<div id="ventanaCatalogoMotivos" title="Motivos de devolución">
    <div class="ui-state-error" ></div>
    <div id="obtenerCatalogoMotivos"></div>
</div>

<div id="ventanaEnviarFichaCliente" title="Enviar ficha técnica del cliente">
    <div id="enviandoFichaCliente"></div>
    <div class="ui-state-error" ></div>
    <div id="formularioCorreoFichaCliente"></div>
</div>


<!--PARA EL PUNTO DE VENTA-->
<div id="ventanaClientes" title="Agregar cliente">
    <div id="cargandoClientes"></div>
    <div id="ErrorClientes" class="ui-state-error" ></div>
    <div id="formularioClientes"></div>
</div>

<div id="ventanaVentas" title="Punto de venta">
    <div id="realizandoVenta"></div>
    <div id="formularioVentas"></div>
</div>

<div id="ventanaCobrosVenta" title="Cobrar venta">
    <div id="registrandoCobroVenta"></div>
    <div id="formularioCobros"></div>
</div>

<div id="ventanaAsignarDescuento" title="Asignar descuento">
	<table class="admintable" width="100%">
    	<tr>
        	<td class="key">Descuento:</td>
            <td><input type="text" class="cajas" id="txtAsignarDescuento" value="0" onkeypress="return soloDecimales(event)" maxlength="6" /></td>
        </tr>
    </table>
</div>

<div id="ventanaCatalogoZonas" title="Catálogo de <?php echo $this->session->userdata('identificador')?>">
	<div id="obtenerCatalogoZonas"></div>
</div>

<div id="ventanaFuentesContacto" title="Contacto">
    <div class="ui-state-error" ></div>
    <div id="registrandoFuenteContacto"></div>
    <div id="formularioFuentesContacto"></div> 
</div>

<div id="ventanaAcrilico" title="Devolución acrílico">
    <div id="registrandoAcrilico"></div>
    <div id="obtenerAcrilico"></div> 
</div>

<?php $this->load->view('clientes/seguimiento/crmServicios/modalesSeguimientoServicios');?>

</div>
