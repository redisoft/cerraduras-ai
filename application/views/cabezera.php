<!DOCTYPE HTML>
<html>
	<meta charset="utf-8">
<head>
<title>
<?php print($title);?>
</title>
<?php

if(isset($Jry)){print($Jry);}
if(isset($Jqui)){print($Jqui);}
if(isset($Jquical)){print($Jquical);}
if(isset($jvalidate)){print($jvalidate);}
if(isset($jFicha_cliente)){print($jFicha_cliente);}
if(isset($jFicha_cotiza)){print($jFicha_cotiza);}
if(isset($jFicha_historial)){print($jFicha_historial);}//jFicha_Documentos
if(isset($jFicha_Documentos)){print($jFicha_Documentos);}//jFicha_Documentos jInventario_ensamble
if(isset($JFuntProveedores)){print($JFuntProveedores);} //Proveedores  
if(isset($JFuntInventario)){print($JFuntInventario);} //Inventario de productos 
if(isset($JFuntPagClien)){print($JFuntPagClien);} //JFuntPagClien
if(isset($JFuntComprasProveedores)){print($JFuntComprasProveedores);} //JFuntPagClien
if(isset($JFuntBuscaClientes)){print($JFuntBuscaClientes);} //JFuntPagClien
if(isset($jFicha_pedidos)){print($jFicha_pedidos);} // Funciones para pedidos

?>

<link rel="manifest" href="<?=base_url()?>manifest.json?v=<?=ASSET_VERSION?>">
<script>
if('serviceWorker' in navigator)
{
	window.addEventListener('load', function()
	{
		navigator.serviceWorker.register('<?=base_url()?>service-worker.js?v=<?=ASSET_VERSION?>')
			.then(function(reg){
				console.log('Service Worker registrado', reg.scope);
			})
			.catch(function(err){
				console.warn('Fallo al registrar Service Worker', err);
			});
	});
}

$(document).ready(function()
{
    menuActivo('<?php echo $menuActivo?>','<?php echo isset($subMenu)?$subMenu:''?>');
	codigoBorrado	= '<?php echo $this->session->userdata('codigoBorrado')?>';
	codigoEditar	= '<?php echo $this->session->userdata('codigoEditar')?>';
	codigoImportar	= '<?php echo $this->session->userdata('codigoImportar')?>';
	codigoCancelar	= '<?php echo $this->session->userdata('codigoCancelar')?>';
	codigoInventario= 'f84b3d20833bedeb0aba1c45467074cf5ac641a9';
	base_url		= '<?php echo base_url()?>';
	img_loader		= '<?php echo base_url().'img/ajax-loader.gif'?>';
	
	precioVentaA	= '<?php echo obtenerNombrePrecio(1)?>';
	precioVentaB	= '<?php echo obtenerNombrePrecio(2)?>';
	precioVentaC	= '<?php echo obtenerNombrePrecio(3)?>';
	precioVentaD	= '<?php echo obtenerNombrePrecio(4)?>';
	precioVentaE	= '<?php echo obtenerNombrePrecio(5)?>';
	sistemaActivo	= '<?php echo sistemaActivo?>';
	
	alertaActiva	= '<?php echo $this->session->userdata('alertaActiva')?>';
	preciosActivo	= '<?php echo $this->session->userdata('precios')?>';
	tiendaLocal		= '<?php echo $this->session->userdata('tiendaLocal')?>';
	registroVentas	= '<?php echo $this->session->userdata('registroVentas')?>';
	//impresoraLocal	= '<?php echo $this->session->userdata('impresoraLocal')?>';
	impresoraLocal	= '0';
	
	$(document).keypress(function(e) 
	{
		if(e.which == 39) 
		{
			event.preventDefault(); 

			return false;
		}
	});
});

</script>
<script>window.base_url = '<?php echo base_url()?>';</script>
<script src="<?php echo base_url()?>js/jsruta.js?v=<?=ASSET_VERSION?>"></script>

<?php $estilo=$this->session->userdata('estilo');?>

<script src="<?php echo base_url()?>js/bibliotecas/jquery-ui-timepicker-addon.js"></script>
<script src="<?php echo base_url()?>js/bibliotecas/jquery-ui-sliderAccess.js"></script>

<script src="<?php echo base_url()?>js/bibliotecas/jquery.ui.monthpicker.js"></script>
<script src="<?php echo base_url()?>js/bibliotecas/busquedas.js?v=<?=ASSET_VERSION?>"></script>
<script src="<?php echo base_url()?>js/bibliotecas/notificaciones.js"></script>
<script src="<?php echo base_url()?>js/bibliotecas/dialog_box.js"></script>
<script src="<?php echo base_url()?>js/materiales/ficha_materiales.js"></script>

<script src="<?php echo base_url()?>js/bibliotecas/eventos.js"></script>
<script src="<?php echo base_url()?>js/bibliotecas/sha1.js"></script>
<script src="<?php echo base_url()?>js/bibliotecas/confirmaciones.js?v=<?=ASSET_VERSION?>"></script>
<script src="<?php echo base_url()?>js/bibliotecas/jquery.ptTimeSelect.js"></script>
<script src="<?php echo base_url()?>js/catalogos.js?v=<?=ASSET_VERSION?>"></script>
<script src="<?php echo base_url()?>js/compras/comprasPagos.js?v=<?=ASSET_VERSION?>"></script>
<script src="<?php echo base_url()?>js/ventas/ventasPagos.js?v=<?=ASSET_VERSION?>"></script>	
<script src="<?php echo base_url()?>js/bibliotecas/fechas.js"></script>	
<script src="<?php echo base_url()?>js/conexion/offline.js"></script>	
<script src="<?php echo base_url()?>js/correos/firma.js"></script>
<script src="<?php echo base_url()?>js/configuracion/sincronizacion.js"></script>
<script src="<?php echo base_url()?>js/ventas/posCache.js?v=<?=ASSET_VERSION?>"></script>
<script src="<?php echo base_url()?>js/ventas/posSync.js?v=<?=ASSET_VERSION?>"></script>
<script src="<?php echo base_url()?>js/ventas/offlineIndicator.js?v=<?=ASSET_VERSION?>"></script>
<script src="<?php echo base_url()?>js/ventas/offlineSales.js?v=<?=ASSET_VERSION?>"></script>
<script src="<?php echo base_url()?>js/installPrompt.js?v=<?=ASSET_VERSION?>"></script>

<script src="<?php echo base_url()?>js/administracion/comprobantesIngresos.js?v=<?=ASSET_VERSION?>"></script>

<script src="<?php echo base_url()?>js/bibliotecas/jquery.PrintArea.js"></script>

<script>
if(window.posCache && typeof window.posCache.openDatabase === 'function'){
    window.posCache.openDatabase();
}
$(document).ready(function(){
    $('#btnSincronizarPOS').on('click', function(){
        var boton = $(this);
        if(typeof window.sincronizarPOS !== 'function'){
            return;
        }
        boton.addClass('en-progreso');
        window.sincronizarPOS().finally(function(){
            boton.removeClass('en-progreso');
            if(typeof window.actualizarEstadoConexion === 'function'){
                window.actualizarEstadoConexion();
            }
            if(typeof window.actualizarEstadoBotonPendientes === 'function'){
                window.actualizarEstadoBotonPendientes();
            }
        });
    });
});
</script>

<!--Start of Zopim Live Chat Script-->
<!--<script type="text/javascript">
window.$zopim||(function(d,s){var z=$zopim=function(c){z._.push(c)},$=z.s=
d.createElement(s),e=d.getElementsByTagName(s)[0];z.set=function(o){z.set.
.push(o)};z.=[];z.set._=[];$.async=!0;$.setAttribute("charset","utf-8");
$.src="//v2.zopim.com/?rzVFd69W1nxYt11IDswUMX7vaLP8NlrX";z.t=+new Date;$.
type="text/javascript";e.parentNode.insertBefore($,e)})(document,"script");
</script>-->
<!--End of Zopim Live Chat Script-->


<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/bootstrap/bootstrap.css?v=<?=ASSET_VERSION?>" />

<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/adm/menuBarra.css?v=<?=ASSET_VERSION?>">
<!--<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/adm/style<?php echo $estilo?>.css">-->

<?php $this->load->view('configuracion/estilo/css'); $this->load->view('configuracion/estilo/ui')?>

<!--<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/temas/<?php echo $estilo?>/jquery-ui.css">-->
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/jquery-time.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/adm/puntoVenta.css?v=<?=ASSET_VERSION?>">
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/adm/detallesTablas.css?v=<?=ASSET_VERSION?>">
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/escalaGrises.css?v=<?=ASSET_VERSION?>">
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/paleta/colorPicker.css" ></link>
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/breadcumb.css">
<!--<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/adm/tablasContabilidad.css">-->

<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/conexion/offline-theme-chrome.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/conexion/offline-language-spanish.css">

<link href="<?php echo base_url()?>js/select2/select2.min.css?v=<?=ASSET_VERSION?>" rel="stylesheet" />
<script src="<?php echo base_url()?>js/select2/select2.min.js?v=<?=ASSET_VERSION?>"></script>

<?php

$servicios	= null;#$this->configuracion->obtenerServiciosVencidos();
$cobros		= null;#$this->configuracion->obtenerCobrosProgramados();
$pagos		= null;#$this->configuracion->obtenerPagosProgramados();
$compras	= null;#$this->configuracion->obtenerCompras();
$mostrar	= null;#$this->configuracion->obtenerConfiguracionNotificacion();
$cobranzas	= null;#$this->configuracion->obtenerVentasCobranza();


#$data='<img style="margin-top:-5px; margin-left:320px; position:absolute; cursor:pointer" src="'.base_url().'img/borrar.png" onClick="ocultarNotificacion()" title="Ocultar" width="16" /> Lista de servicios por caducar:';
$data	='<label id="btnOcultarNotificacion" onClick="minimizarNotificacion(300,1)" style="margin-top:-5px; margin-left:300px; position:absolute; cursor:pointer; color: #FFF">Ocultar</label> <label id="btnMostrarNotificacion" onClick="maximizarNotificacion()" style="margin-top:-5px; margin-left:300px; position:absolute; cursor:pointer; color: #FFF; display: none">Mostrar</label> Notificaciones pendientes:';
$i=1;

#var_dump($servicios);

/*foreach($servicios as $row)
{
	$clase=$i%2>0?'':'class="colorNotificacion"';
	
	$data.='<div '.$clase.' id="servicio'.$i.'">';
	$data.='<img src="'.base_url().'img/borrar.png" onClick="quitarNotificacion('.$i.','.$row->idProducto.')" width="14" title="Quitar notificación" style="cursor:pointer" />';
	$data.=$row->empresa.', '.$row->nombre.', Caducidad: '.$row->fechaVencimiento;
	$data.='</div>';
	
	$i++;
}

$i=1;
foreach($cobros as $row)
{
	$cliente	=$this->configuracion->obtenerCliente($row->idCliente);
	$cliente	=$cliente!=null?$cliente->empresa.', ':'';
	$clase		=$i%2>0?'':'class="colorNotificacion"';
	
	$data.='<div '.$clase.' id="cobro'.$i.'">';
	$data.='<img src="'.base_url().'img/borrar.png" onClick="quitarNotificacionCobro('.$i.','.$row->idIngreso.')" width="14" title="Quitar notificación" style="cursor:pointer" />';
	$data.='Cobros: '.substr($row->fecha,0,10).', '.$cliente.$row->producto.', Monto: $'.number_format($row->pago,2);
	$data.='</div>';
	
	$i++;
}

$i=1;
foreach($pagos as $row)
{
	$proveedor	=$this->configuracion->obtenerProveedor($row->idProveedor);
	$proveedor	=$proveedor!=null?$proveedor->empresa.', ':'';
	$clase		=$i%2>0?'':'class="colorNotificacion"';
	
	$data.='<div '.$clase.' id="pago'.$i.'">';
	$data.='<img src="'.base_url().'img/borrar.png" onClick="quitarNotificacionPago('.$i.','.$row->idEgreso.')" width="14" title="Quitar notificación" style="cursor:pointer" />';
	$data.='Pagos: '.substr($row->fecha,0,10).', '.$proveedor.$row->producto.', Monto: $'.number_format($row->pago,2);
	$data.='</div>';
	
	$i++;
}

$i=1;
foreach($compras as $row)
{
	$clase		=$i%2>0?'':'class="colorNotificacion"';
	
	$data.='<div '.$clase.' id="compra'.$i.'">';
	#$data.='<img src="'.base_url().'img/borrar.png" onClick="quitarNotificacionCompra('.$i.','.$row->idCompras.')" width="14" title="Quitar notificación" style="cursor:pointer" />';
	$data.='<img src="'.base_url().'img/remision.png" onClick="obtenerPagosComprasProveedor('.$row->idCompras.')" width="14" title="Pagos de compras" style="cursor:pointer" />';
	$data.='Compras: '.obtenerFechaMesCorto($row->fechaCompra).', '.$row->nombre.', <span style="cursor:pointer" onClick="obtenerFichaTecnicaProveedor('.$row->idProveedor.')">'.$row->empresa.'</span>, Monto: $'.number_format($row->total,2);
	$data.='</div>';
	
	$i++;
}

$i=1;
$cobranza	='<label id="btnOcultarNotificacion2" onClick="minimizarNotificacion(300,1)" style="margin-top:-5px; margin-left:300px; position:absolute; cursor:pointer; color: #FFF">Ocultar</label> <label id="btnMostrarNotificacion2" onClick="maximizarNotificacion()" style="margin-top:-5px; margin-left:300px; position:absolute; cursor:pointer; color: #FFF; display: none">Mostrar</label> Cobros pendientes:';
foreach($cobranzas as $row)
{
	$fecha		= "Pendiente de facturación";
	$dias		= 0;
	
	if($row->idFactura>0)
	{
		$fecha	= obtenerFechaMesCorto($this->configuracion->obtenerFechaFactura($row->idFactura,$row->diasCredito));
		$dias	= $this->configuracion->obtenerDiasRestantes($fecha);
	}
	
	$clase		= $i%2>0?'':'class="colorNotificacion"';
	
	$cobranza.='<div '.$clase.' id="cobranza'.$i.'">';
	$cobranza.='<img src="'.base_url().'img/remision.png" onClick="obtenerPagosClientes('.$row->idVenta.')" width="14" title="Cobros de ventas" style="cursor:pointer" />';
	$cobranza.='Cobros: '.$fecha.', <span style="cursor:pointer" onClick="obtenerPagosClientes('.$row->idVenta.')">'.$row->empresa.'</span>, Saldo: $'.number_format($row->saldo,2);
	$cobranza.='</div>';
	
	$i++;
}

if($this->session->userdata('rol')==1 and $this->session->userdata('notificacionesActivas')==1)
{
	if($cobranzas!=null)
	{
		echo 
		'<script>
		$(document).ready(function()
		{
			notificacionPago(\''.$cobranza.'\',500,1000,\'detalles\',1,1);
		})
		</script>';
	}
	
	if($servicios!=null or $cobros!=null or $pagos!=null or $compras!=null)
	{
		#$data.='<div align="right"><img src="'.base_url().'img/borrar.png" onClick="ocultarEventos()" style="cursor:pointer" title="Ocultar" width="16" /></div>';
		
		echo 
		'<script>
		$(document).ready(function()
		{
			notificacion(\''.$data.'\',500,1000,\'detalles\',68,1);
		})
		</script>';
		
		if($mostrar==0)
		{
			echo '
			<script>
			$(document).ready(function()
			{
				//minimizarNotificacion(0,0)
			})
			</script>';
		}
	}
}*/


if(strlen($this->session->userdata('notificacion'))>2)
{
	echo 
	'<script>
	$(document).ready(function()
	{
		notify("'.$this->session->userdata('notificacion').'",500,5000);
	})
	</script>';
}

$this->session->set_userdata('notificacion','');

if(strlen($this->session->userdata('errorNotificacion'))>2)
{
	echo 
	'<script>
	$(document).ready(function()
	{
		//notify("'.$this->session->userdata('errorNotificacion').'",500,5000,"error");
	})
	</script>';
}

$this->session->set_userdata('errorNotificacion','');


?>

<script>
/*COMPROBAR LA CONEXIÓN A INTERNET*/
/*Offline.options = 
{
	game: true,
	checks: {xhr: {url: '<?php echo base_url()?>clientes/revisarConexion'}}
}

var run = function()
{
  	if (Offline.state === 'up')
    Offline.check();
}

setInterval(run, 300000);*/


/*var run = function()
{
  	$("#ventanaSaldoPendiente").dialog('open');
}

setInterval(run, 1800000);*/

</script>


</head>

<body id="cuerpoPrincipal" >

<div class="container-fluid">

<div id="contenidoLoader" class=""></div>

<div class="main">

<!--div id="ventanaSaldoPendiente" title="Saldo pendiente">
	<div class="text-center">
	<h2>Existe un saldo pendiente, favor de enviar comprobante lo antes posible</h2>
	</div>
</div-->
