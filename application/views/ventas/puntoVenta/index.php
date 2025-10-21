<script src="<?php echo base_url()?>js/ventas/cotizaciones.js?v=<?php echo(rand());?>"></script>

<!--<script src="<?php echo base_url()?>js/clientes/clientes.js"></script>
<script src="<?php echo base_url()?>js/clientes/catalogo.js"></script>-->

<script src="<?php echo base_url()?>js/bancos/bancos.js?v=<?php echo(rand());?>"></script>
<script src="<?php echo base_url()?>js/facturacion/folios.js?v=<?php echo(rand()); ?>"></script>
<script src="<?php echo base_url()?>js/informacion.js?v=<?php echo(rand()); ?>"></script>
<script src="<?php echo base_url()?>js/ventas/faltantesTraspasos.js?v=<?php echo(rand()); ?>"></script>

<script src="<?php echo base_url()?>js/ventas/ventasFacturas.js?v=<?php echo(rand()); ?>"></script>
<script src="<?php echo base_url()?>js/ventas/sucursales.js?v=<?php echo(rand()); ?>"></script>
<!--<script src="<?php echo base_url()?>js/cotizaciones/cotizacionClientes.js"></script>-->
<script src="<?php echo base_url()?>js/cotizaciones/descuentos.js?v=<?php echo(rand()); ?>" ></script>
<script src="<?php echo base_url()?>js/configuracion/zonas/catalogo.js?v=<?php echo(rand()); ?>"></script>
<script src="<?php echo base_url()?>js/clientes/contactos/catalogo.js?v=<?php echo(rand()); ?>"></script>
<script src="<?php echo base_url()?>js/informacion.js?v=<?php echo(rand()); ?>"></script>

<script src="<?php echo base_url()?>js/ventas/ventasTicket.js?v=<?php echo(rand()); ?>"></script>
<script src="<?php echo base_url()?>js/bibliotecas/barcode.js?v=<?php echo(rand()); ?>"></script>
<script src="<?php echo base_url()?>js/bibliotecas/JsBarcode.all.js?v=<?php echo(rand());?>"></script>
<script src="<?php echo base_url()?>js/lineas/lineas.js?v=<?php echo(rand()); ?>"></script>

<script src="<?php echo base_url()?>js/bibliotecas/datatables.min.js"></script>

<!--<script src="<?php echo base_url()?>js/bibliotecas/datatables.js"></script>-->

<?php
echo '<script src="'.base_url().'js/ventas/ventas.js?v='.rand().'"></script>';
?>


<script>
$(document).ready(function()
{
	//$('#barraTop').fadeOut(0)
	//$('#ulMenuPrincipal').fadeOut(0)
	//$('.footer').fadeOut(0)
	
	window.setTimeout(function() 
	{
		$('#txtFechaMes').val('')
		formularioVentas();
		
		//$( "#formularioVentas" ).trigger( "click" );
		
	}, 200);
	
	$("#txtAsignarDescuento").keypress(function(e)
	 {
		if(e.which == 13) 
		{
			e.preventDefault();
			asignarDescuentoProducto();
			
		}
	});
	
	
	$(document).keydown(function(e) 
	{
		switch(e.keyCode)
		{
			case 116: 
				recargarPaginaVenta();
				e.preventDefault();
			break;
			
			<?php
			#if($registroVentas=='1')	
			{
				?>
				// user presses the "F6"
				case 117: 
					//obtenerTraspasos(); 
					formularioProcesarCotizacion()
					e.preventDefault();
				break;
				<?php
			}
			?>
			
			case 118: 
				//obtenerTraspasos(); 
				
				accesoPrecioPermiso()
				e.preventDefault();
			break;
			
			 // user presses the "F4"
			case 115: 	
			
				/*if(sistemaActivo=='pinata')
				{
					$('#ventanaCobrosVenta').dialog('option', 'title', ' . ');
				
					tipoVenta=1;
					formularioCobros(); 
					e.preventDefault();
				}
				
				if(sistemaActivo=='olyess' || sistemaActivo=='cerraduras')
				{
					obtenerSubLineasVentas(0);
				}*/
				
			break;
			
			
				
				<?php
			#if($registroVentas=='1')	
			{
				?>
				// user presses the "F3"
				case 114: 
					tipoVenta=0;
					formularioCobros(); 
					e.preventDefault();
				break;
				<?php
			}
			?>
				
			
			// user presses the "F2"
			case 113: 
				formularioVentas(); 
				e.preventDefault();
			break;
			
			 // F6
			/*case 117: 
				for(i=0;i<fila;i++)
				{
					precio				= obtenerNumeros($('#txtTotalProducto'+i).val());
					
					if(precio>0)
					{
						accesoAsignarDescuento(0); 
						e.preventDefault();
						return;
					}
				}
				
				
			break;*/
			
			// F8
			case 119: 
				if(Fila>0)
				{
					quitarProductoProducto(Fila)
				}
				e.preventDefault();
			break;
		}
	});

});
</script>

<input type="hidden" class="cajas" id="txtIdClientePunto" 		value="<?php echo $idCliente?>" />
<input type="hidden" class="cajas" id="txtIdArqueo" 			value="<?php echo $idArqueo?>" />
<input type="hidden" class="cajas" id="txtIdTiendaLocal" 		value="<?php echo $tiendaLocal?>" />


<!--<table class="toolbar" width="30%">
    <tr>
        <td onclick="formularioFondoCaja()">
        <a>
        	<img src="<?php echo base_url()?>img/ventas.png" width="25" /><br />	
        	Fondo de caja
        </a>
        </td>
        <td onclick="formularioRetiroEfectivo()">
        	<a>
                <img src="<?php echo base_url()?>img/ventas.png" width="25" /><br />	
                Retiro de efectivo
            </a>
         </td>
        <td onclick="obtenerArqueo()">
        	<a>
                <img src="<?php echo base_url()?>img/ventas.png" width="25" /><br />
        		Arqueo
            </a>
        </td>
        <td onclick="obtenerCorteCaja()">
        	<a>
                <img src="<?php echo base_url()?>img/ventas.png" width="25" /><br />
        		Corte de caja
            </a>
        </td>
    </tr>
</table> -->
    
    
<div class="derecha" id="ventanaPuntoVenta">
<div class="submenu" style="height:20px">
<div class="breadcumb"><?php echo isset($breadcumb)?$breadcumb:''?></div>


<div class="toolbar" id="toolbar" >
  	
    
    
</div>       
</div>

<!--<div style="background-color:#000; height:970px; overflow:scroll; overflow-y: hidden; overflow-x: hidden" id="moli">-->

<div id="imprimirTicket" style="max-height:1px; height:1px; overflow: hidden; "></div>

<div class="listproyectos">
	<div id="realizandoVenta"></div>
    <div id="formularioVentas" ></div>
    
    <!--onclick="pantallaCompleta()"-->
    
</div>
 


<!--<div id="ventanaVentas" title="Punto de venta"></div>-->

<div id="ventanaCobrosVenta" title="Cobro de ventas">
    <div id="registrandoCobroVenta"></div>
    <div id="formularioCobros"></div>
</div>

<div id="ventanaStockSucursales" title="Stock sucursales">
	<div id="obtenerStockSucursales"></div>
</div>

<div id="ventanaInventarioFaltante" title="Productos con inventario faltante">
    <div id="procesandoInventarioFaltante"></div>
    <div class="ui-state-error" ></div>
	<div id="formularioInventarioFaltante"></div>
</div>

<div id="ventanaCatalogoZonas" title="Catálogo de <?php echo $this->session->userdata('identificador')?>">
	<div id="obtenerCatalogoZonas"></div>
</div>

<div id="ventanaInformacionTienda" title="Detalles de tienda">
	<div id="obtenerInformacionTienda"></div>
</div>

<div id="ventanaAsignarDescuento" title="Asignar descuento">
	<table class="admintable" width="100%">
    	<tr>
        	<td class="key">Descuento:</td>
            <td><input type="text" class="cajas" id="txtAsignarDescuento" value="0" onkeypress="return soloDecimales(event)" maxlength="6" /></td>
        </tr>
    </table>
</div>

<div id="ventanaProcesarCotizacion" title="Registrar cotización">
    <div id="registrandoCotizacion"></div>
    <div id="formularioProcesarCotizacion"></div>
</div>

<?php $this->load->view('ventas/puntoVenta/fondoCaja/modalesFondo')?>
<?php $this->load->view('ventas/puntoVenta/retiroEfectivo/modalesRetiroEfectivo')?>
<?php $this->load->view('ventas/puntoVenta/arqueo/modalesArqueo')?>
<?php $this->load->view('ventas/puntoVenta/corteCaja/modalesCorte')?>
<?php $this->load->view('ventas/pedidos/modalesPedidos')?>
<?php $this->load->view('traspasos/modalesTraspasos')?>
<?php $this->load->view('ventas/clientes/busqueda')?>
<?php 
	$this->load->view('ventas/cfdi/modales');
	$this->load->view('clientes/direcciones/catalogo');
?>


</div>

<script type="text/javascript" src="<?php echo base_url()?>js/fullscreen.js"></script>

