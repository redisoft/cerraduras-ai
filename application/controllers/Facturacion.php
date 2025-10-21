<?php
class Facturacion extends CI_Controller
{
    protected $_fechaActual;
	protected $_iduser;
	protected $_csstyle;
    protected $_tables;
	protected $cuota;

	function __construct()
	{
		parent::__construct();

		if( ! $this->redux_auth->logged_in() )
		{//verificar si el el usuario ha iniciado sesion
 			redirect(base_url().'login');
 		}
		
		$this->load->library('MY_Letras');
		$this->config->load('datatables', TRUE);
		$this->config->load('style', TRUE);
		$this->config->load('js',TRUE);
                
        $datestring   		= "%Y-%m-%d %H:%i:%s";
	    $this->_fechaActual = mdate($datestring,now());
	    $this->_iduser 		= $this->session->userdata('id');
  	    $this->_tables 		= $this->config->item('datatables');
	    $this->_csstyle 	= $this->config->item('style');
		$this->_jss			= $this->config->item('js');
		
		$this->load->model("modelousuario","modelousuario");
		$this->load->model("modelo_configuracion","configuracion");
		$this->load->model('facturacion_modelo','facturacion');
        $this->load->model("modeloclientes","clientes");
		$this->load->model("reportes_model","reportes");
		$this->load->model("ingresos_modelo","ingresos");
		$this->load->model('facturamanual_modelo','manual');
		$this->load->model('facturaglobal_modelo','facturaGlobal');
		$this->load->model('globalingresos_modelo','globalingresos');
		$this->load->model('traslado_modelo','traslados');
		$this->load->model('pagos_modelo','pagos');
		
		$this->configuracion->accesoUsuario(); //CONTROL DE ACCESOS
		$this->cuota	= $this->configuracion->comprobarCuota(); //COMPROBAR CUOTA DE DISCO
	}
	
	public function facturasCliente($idCliente=0,$limite=0,$idFactura=0)
	{
		$Data['title']			= "Panel de Administración";
		$Data['cassadmin']		=$this->_csstyle["cassadmin"];
		$Data['csmenu']			=$this->_csstyle["csmenu"];
		$Data['csvalidate']		=$this->_csstyle["csvalidate"];
		$Data['csui']			=$this->_csstyle["csui"];
		$Data['nameusuario']	=$this->modelousuario->getUsuarios($this->_iduser);
		$Data['Fecha_actual']	=$this->_fechaActual;
		$Data['Jquical']		=$this->_jss['jquerycal'];
		$Data['Jry']			=$this->_jss['jquery'];
		$Data['Jqui']			=$this->_jss['jqueryui'];
		$Data['jvalidate']		=$this->_jss['jvalidate'];
		$Data['jFicha_cliente']	=$this->_jss['jFicha_cliente'];
		$Data['JFunMensajeria']	=$this->_jss['JFunMensajeria'];
		$Data['permisos']		=$this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']		='clientes'; 
		
		$this->load->view("cabezera",$Data);
		$this->load->view('header',$Data);
		$this->load->view("principal",$Data);

		$Pag["base_url"]		= base_url()."facturacion/facturasCliente/".$idCliente.'/';
		$Pag["total_rows"]		=$this->facturacion->numeroFacturasCliente($idCliente,$idFactura);//Total de Registros
		$Pag["per_page"]		=20;
		$Pag["num_links"]		=5;
		$Pag["uri_segment"]		=4;
		
		$this->pagination->initialize($Pag);
		
		#----------------------------------PERMISOS------------------------------------#

		$data['permiso']	= $this->configuracion->obtenerPermisosBoton('24',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			
			return;
		}
		#------------------------------------------------------------------------------#
		$data['cliente']		= $this->clientes->obtenerCliente($idCliente);
		$data['facturas'] 		= $this->facturacion->obtenerFacturasCliente($Pag["per_page"],$limite,$idCliente,$idFactura);
		
		$cliente				= substr($data['cliente']->empresa,0,300);
		
		if(sistemaActivo=='IEXE' and strlen($data['cliente']->nombre)>2) $cliente	= $data['cliente']->nombre.' '.$data['cliente']->paterno.' '.$data['cliente']->materno;
		
		$data["breadcumb"]		= '<a href="'.base_url().'clientes">Clientes</a> > <a href="'.base_url().'clientes/index/'.$idCliente.'">'.$cliente.'</a> > Facturas';

		$this->load->view('facturacion/facturasClientes',$data);
		$this->load->view("pie",$Data);
	}
	
	function descargarXML($idFactura) #Descargar el archivo XML
	{
		$this->load->helper('download');
	
		$factura	= $this->facturacion->obtenerFactura($idFactura);
		$emisor		= $this->facturacion->obtenerEmisor($factura->idEmisor);
		$folio		= $factura->folio;

		$fichero 	= 'cfdi'.$folio.'Timbre.xml';
		$descarga 	= $emisor->rfc.'_'.$factura->serie.$folio.'.xml';
		$data 		= file_get_contents("media/fel/".$emisor->rfc."/folio".$factura->serie.$folio."/$fichero"); 
		
		force_download($descarga, $data); 
	}
	
	public function cancelarFactura()#Cancerlar el CFDI
	{
		if(!empty($_POST))
		{
			$cancelar=$this->modelofacturas->cancelarFactura();
			print($cancelar);
		}
	}
	
	public function facturaGlobal()
	{
		if(!empty($_POST))
		{
			$configuracion=$this->configuracion->obtenerConfiguracion();
			
			$global=$this->modelofacturas->generarFactura($configuracion);
			
			if($global=="1")
			{
				$this->session->set_userdata('notificacion','La factura se ha creado correctamente');
			}
			
			print($global);
		}
	}
	
	public function facturaParcial()
	{
		$idCotizacion				=$this->input->post('idCotizacion');
		$data['cotizacion']			=$this->facturacion->obtenerCotizacion($idCotizacion);
		$data['productos']			=$this->facturacion->obtenerProductosCotizacion($idCotizacion);
		$data['cliente']			=$this->facturacion->obtenerCliente($data['cotizacion']->idCliente);
		$data['emisores']			=$this->facturacion->obtenerEmisores();
		$data['factura']			=$this->facturacion->obtenerUltimaFactura($idCotizacion);
		$data['divisas']			=$this->configuracion->obtenerDivisas();
		$data['metodos']			= $this->configuracion->obtenerMetodosPago();
		$data['idCotizacion']		=$idCotizacion;
		
		$this->load->view('facturacion/facturaParcial',$data);
	}
	
	public function obtenerDatosFactura()
	{
		$this->configuracion->actualizarAccesoFacturacion(1);
		
		$idCotizacion					= $this->input->post('idCotizacion');
		$data['cotizacion']				= $this->facturacion->obtenerCotizacion($idCotizacion);
		$data['cliente']				= $this->facturacion->obtenerCliente($data['cotizacion']->idCliente);
		$data['emisores']				= $this->facturacion->obtenerEmisores();
		$data['divisas']				= $this->configuracion->obtenerDivisas();
		$data['factura']				= $this->facturacion->obtenerUltimaFactura($idCotizacion);
		$data['productos']				= $this->facturacion->obtenerProductosCotizacion($idCotizacion);
		$data['direcciones']			= $this->clientes->obtenerDireccionesFiscales($data['cotizacion']->idCliente);
		
		$data['configuracion']			= $this->configuracion->obtenerFel();
		$data['idCotizacion']			= $idCotizacion;
		
		
		$data['metodos']				= $this->configuracion->obtenerMetodosPago();
		$data['formas']					= $this->configuracion->obtenerFormasPago();
		$data['usos']					= $this->configuracion->obtenerUsosCfdi();
		
		#PARCIALES
		#---------------------------------------------------------------------------------------------------------#
		$data['parciales']				= $this->facturacion->obtenerFacturasParciales($idCotizacion);
		$data['totalParciales']			= 0;
		
		if($data['parciales']!=null)
		{
			$data['totalParciales']		=$this->facturacion->sumarFacturasParciales($idCotizacion);
		}
		#---------------------------------------------------------------------------------------------------------#
		
		#$base	= $this->load->database('default',TRUE);
		
		#echo 'Base: '.$base->database;
		
		$this->load->view('facturacion/obtenerDatosFactura',$data);
	}
	
	public function obtenerFolio()
	{
		$idEmisor			=$this->input->post('idEmisor');
		
		if($idEmisor==0)
		{
			echo 'Seleccione emisor';
			return;	
		}
		
		$data['folio']		=$this->facturacion->obtenerFolio($idEmisor);
		$data['emisor']		=$this->facturacion->obtenerEmisorFolios($idEmisor);
		
		$this->load->view('facturacion/obtenerFolio',$data);
	}
	
	//Quitar el limite para la ejecución del script de facturacion con el PAC
	//Esto incluye la conversión de archivos 
	public function crearCFDI()
	{
		if(!empty($_POST))
		{
			#error_reporting(1);
			set_time_limit(0); 

			if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}
			
			$registro	= $this->facturacion->crearCFDI();
			
			if($registro[2]>0)
			{
				$this->configuracion->actualizarAccesoFacturacion(0);
			}
			
			if(strlen($registro[3])>0)
			{
				$this->facturacion->enviarFacturaCreada($registro[2],$registro[3]);
			}
			
			echo  json_encode($registro);
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
	
	public function vistaPrevia()
	{
		if(!empty($_POST))
		{
			set_time_limit(0); 
			
			echo $this->facturacion->vistaPrevia();
		}
		else
		{
			echo "0";
		}
	}
	
	public function devolverProductosVenta()
	{
		if(!empty($_POST))
		{
			$devolver	=$this->facturacion->devolverProductosVenta();
			print($devolver);
		}
	}
	
	public function reposicionesVenta()
	{
		if(!empty($_POST))
		{
			$reposicion	=$this->facturacion->reposicionesVenta();
			print($reposicion);
		}
	}
	
	public function obtenerDevolucionesVenta()
	{
		if(!empty($_POST))
		{
			$idCotizacion		=$this->input->post('idCotizacion');
			$cotizacion			=$this->facturacion->obtenerCotizacion($idCotizacion);
			$productos			=$this->facturacion->obtenerProductosCotizacion($idCotizacion);
			$nota				=0;
			
			echo'
			<i>Nota: Realizar todas las devoluciones antes de realizar la Nota de Crédito</i>
			<table class="admintable" width="100%" style="border-collapse: collapse">
				<tr>
					<th colspan="7">Devoluciones de la orden de venta '.$cotizacion->ordenCompra.'</th>
				</tr>
				<tr>
					<th>#</th>
					<th>Producto</th>
					<th>Cantidad</th>
					<th>Precio</th>
					<th>Importe</th>
					<th>Devolución</th>
					<th>Acciones</th>
				<tr>';
			
			$i=1;
			
			echo'<input type="hidden" id="txtIdCotizacion" value="'.$idCotizacion.'" />'; #Campos ocultos
			
			foreach($productos as $row)
			{
				$estilo=$i%2>0?"class='sinSombra'":'class="sombreado"';
	
				echo'
				<tr '.$estilo.'>
					<td>'.$i.'</td>
					<td style="border-radius: 0px">'.$row->nombre.'</td>
					<td align="center">'.number_format($row->cantidad,2).'</td>
					<td align="right">$ '.number_format($row->precio,4).'</td>
					<td align="right">$ '.number_format($row->importe,4).'</td>
					<td align="center">';
					
					if($row->devueltos==0)
					{
						echo'
						<input type="text" id="txtCantidadDevolver'.$i.'" class="cajas" />
						<input type="hidden" id="txtCantidadTotal'.$i.'" value="'.$row->cantidad.'" />';
					}
					else
					{
						echo number_format($row->devueltos,2);
					}
						
					echo'</td>
					<td align="center">';
					
					if($row->entregado==0)
					{
						echo'Pendiente de entrega';
					}
					else
					{
						if($row->devueltos==0)
						{
							echo'<input type="checkbox" id="chkDevoluciones'.$i.'" title="Devolver" 
								onchange="devolverProductoVenta('.$i.','.$row->idProducto.')" /><br />
								<a>Devolver</a>'; 
						}
						else
						{
							$nota=1;
							
							if($row->repuesto==0)
							{
								echo'<img title="Reposicion" onclick="reposicionesVenta('.$row->idProducto.')" 
									src="'.base_url().'img/reposicion.png" id="reposiciones'.$i.'" width="22" />
								<br />
								<a>Reponer</a>';
							}
							else
							{
								echo'<img title="Se ha repuesto el producto" src="'.base_url().'img/success.png" width="22" />
								<br />
								<a>Repuesto</a>';
							}
						}
					}
					
					echo'
					</td>
				</tr>';
				
				$i++;
			}
			
			if($cotizacion->idFactura!=0 and $nota==1 and $cotizacion->idNota==0)
			{
				echo'
				<tr>
					<td colspan="7" align="right">
						<img src="'.base_url().'img/similares.png" width="22" style="margin-right:33px" 
							title="Crear Nota de Crédito" id="notaCredito" onclick="obtenerDetallesNota('.$idCotizacion.')" />
						<br />
						<a>Nota de Crédito</a>
					</td>
				</tr>';
			}
				
			echo'</table>
			
			<script>
			$("#notaCredito").click(function(e)
			{
				$("#ventanaNotaCredito").dialog("open");
			});
			</script>';
		}
	}
	
	public function obtenerDetallesNota()
	{
		if(!empty($_POST))
		{
			$idCotizacion	=$this->input->post('idCotizacion');
			$cotizacion		=$this->facturacion->obtenerCotizacion($idCotizacion);
			$productos		=$this->facturacion->obtenerProductosNota($idCotizacion);
			
			echo'
			<table class="admintable" width="99%" style="border-collapse: collapse">
				<tr>
					<th colspan="7">Detalles de la orden de venta '.$cotizacion->ordenCompra.' para crear la Nota de Crédito</th>
				</tr>
				<tr>
					<th>#</th>
					<th>Producto</th>
					<th>Cantidad</th>
					<th>Precio</th>
					<th>Importe</th>
				<tr>';
			
			$i			=1;
			$subTotal	=0;
			$total		=0;
			$iva		=$cotizacion->iva;
			$descuento	=$cotizacion->descuento/100;
			
			foreach($productos as $row)
			{
				$subTotal+=$row->devueltos*$row->precio;
				echo'
				<tr>
					<td>'.$i.'</td>
					<td style="border-radius: 0px">'.$row->nombre.'</td>
					<td align="center">'.number_format($row->devueltos,2).'</td>
					<td align="right">$ '.number_format($row->precio,4).'</td>
					<td align="right">$ '.number_format($row->devueltos*$row->precio,4).'</td>
				</tr>';
				
				$i++;
			}
			
			$descuento	=$subTotal*$descuento;
			$suma		=$subTotal-$descuento;
			$iva		=$suma*$iva;
			$total		=$suma+$iva;
			
			echo'
			<tr>
				<td class="totales" colspan="4" align="right">SUBTOTAL</td>
				<td align="right">$'.number_format($subTotal,4).'</td>
			</tr>
			<tr>
				<td class="totales" colspan="4" align="right">DESCUENTO '.number_format($cotizacion->descuento/100,2).'%</td>
				<td align="right">$'.number_format($descuento,4).'</td>
			</tr>
			<tr>
				<td class="totales" colspan="4" align="right">IVA '.number_format($cotizacion->iva,2).'%</td>
				<td align="right">$'.number_format($iva,4).'</td>
			</tr>
			<tr>
				<td class="totales" colspan="4" align="right">TOTAL</td>
				<td align="right">'.number_format($total,4).'</td>
			</tr>';
			
			echo '</table>';
			
			echo '
			<table class="admintable" width="99%" style="margin-top:4px">
				 <tr>
				<td class="key">Metodo de pago</td>
				<td>
					<input type="text" style="width:250px" class="cajas" id="txtMetodoPago1" name="txtMetodoPago1" value="Efectivo" />
				</td>
			</tr>
			<tr>
				<td class="key">Forma de pago</td>
				<td>
					<input type="text" style="width:250px" class="cajas" id="txtFormaPago1" name="txtFormaPago1" value="Pago en una sola exhibición" />
				</td>
			</tr>
			 <tr>
				<td class="key">Condiciones de pago</td>
				<td>
					<input type="text" style="width:250px" class="cajas" id="txtCondiciones1" name="txtCondiciones1" value="30 días a partir de la fecha de entrega" />
				</td>
			</tr>
		</table>';
		}
	}
	
	#------------------------------------------------------------------------------------------------------------------#
	#------------------------------------------------CANCELAR      FACTURA---------------------------------------------#
	#------------------------------------------------------------------------------------------------------------------#
	
	public function motivosCancelacionFactura()
	{
		if(!empty($_POST))
		{
			$idFactura				= $this->input->post('idFactura');
			$data['factura']		= $this->facturacion->obtenerFacturaCancelar($idFactura);
			$data['cancelaciones']	= $this->configuracion->obtenerMotivosCancelacion();
			$data['idFactura']		= $idFactura;
			
			if($data['factura']->pago=='1')
			{
				$data['pago'] 		= $this->pagos->obtenerPago($idFactura);
			}
			
			$this->load->view("facturacion/cancelar/cancelarCfdi",$data);
		}
	}
	
	public function cancelarCFDI()
	{
		if(!empty($_POST))
		{
			$factura=$this->facturacion->cancelarCFDI();
			
			if($factura[0]=="1")
			{
				$this->session->set_userdata('notificacion','La factura se ha cancelado correctamente');	
			}
			
			echo json_encode($factura);
		}
		else
		{
			echo json_encode(array('0','Error al cancelar el cfdi'));
		}
	}
	
	public function formularioCorreo()
	{
		if(!empty($_POST))
		{
			$data['factura']	= $this->facturacion->obtenerFactura($this->input->post('idFactura'));
			$data['usuario']	= $this->configuracion->obtenerUsuario($this->_iduser);
			$data['usuarios']	= $this->configuracion->obtenerListaUsuarios(1);
			$data['historial']	= $this->configuracion->obtenerHistorialEnvios($this->input->post('idFactura'),2);
			
			$this->load->view("facturacion/enviar/formularioCorreo",$data);
		}
	}

	public function enviarFacturaAdjunta()
	{
		$idFactura		= $this->input->post('idFactura');
		$documentos		= $this->facturacion->crearFacturaFisicaCorreo($idFactura);
		$configuracion	= $this->facturacion->obtenerConfiguracion();
		$factura		= $this->facturacion->obtenerFactura($idFactura);
		$usuario		= $this->configuracion->obtenerUsuario( $this->input->post('idUsuario'));
		$firma			= $this->input->post('firma');
		$destinatario	= $this->input->post('email');
		
		$email			= $configuracion->correo;
		$nombre			= $configuracion->nombre;
		
		if($usuario!=null)
		{
			if(strlen($usuario->correo)>0)
			{
				$email	= $usuario->correo;
				$nombre	= $usuario->nombre.' '.$usuario->apellidoPaterno.' '.$usuario->apellidoMaterno;
			}
		}
		
		$this->load->library('email');
		$this->email->from($email,$nombre);
		$this->email->to($destinatario);
		
		$imagen			= "";
			
		if(file_exists('media/fel/'.$documentos['configuracion']->rfc.'/'.$documentos['configuracion']->logotipo))
		{
			$datos='<img src="'.base_url().'media/fel/'.$documentos['configuracion']->rfc.'/'.$documentos['configuracion']->logotipo.'" width="215" height="99" />';
		}
		
		$this->email->attach($documentos['pdf']);
		$this->email->attach($documentos['xml']);
		
		$cuerpo			= $imagen.'<br />'.nl2br($firma).'<br /><strong>Por favor consulte los adjuntos</strong>';

		$this->email->subject('Factura '.$factura->serie.$factura->folio);
		$this->email->message
		(
			$cuerpo
		);
		
		if (!$this->email->send())
		{
			#echo $this->email->print_debugger();
			
			echo "0";
		}
		else
		{
			$this->configuracion->registrarBitacora('Enviar CFDI','Facturación',$factura->serie.$factura->folio.', Email: '.$destinatario); //Registrar bitácora
			
			$this->configuracion->registrarHistorialEnvios($destinatario,'2',$this->input->post('idUsuario'),$idFactura); //Registrar historial
			
			echo "1";
		}
	}
	
	//->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->
	//PARA LA FACTURACIÓN DEL SAT
	//->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->
	
	function descargarXMLSat($idFactura) #Descargar el archivo XML
	{
		$this->load->helper('download');
		
		$carpeta	= "media/sat/";
		$factura	= $this->facturacion->obtenerFacturaSat($idFactura);
		$fichero	= $factura->serie.$factura->folio.'.xml';
		
		guardarFichero($carpeta.$fichero,$factura->xml);

		$data 		= file_get_contents($carpeta.$fichero); 
		
		force_download($fichero, $data); 
	}
	
	//->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->
	//OBTENER DATOS PARA NOTA DE CRÉDITO POR DEVOLUCIONES
	//->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->
	public function obtenerDatosNota()
	{
		$data['cotizacion']			= $this->facturacion->obtenerCotizacion($this->input->post('idCotizacion'));
		$data['cliente']			= $this->facturacion->obtenerCliente($data['cotizacion']->idCliente);
		$data['emisores']			= $this->facturacion->obtenerEmisores();
		$data['divisas']			= $this->configuracion->obtenerDivisas();
		$data['metodos']			= $this->configuracion->obtenerMetodosPago();

		$this->load->view('ventas/devoluciones/notaCredito/obtenerDatosNota',$data);
	}
	
	//->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->
	//OBTENER FACTURA PARA INGRESOS
	//->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->
	
	public function registrarIngresoFactura()
	{
		if(!empty($_POST))
		{
			set_time_limit(0); 
			
			if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}
			
			echo  json_encode($this->ingresos->registrarIngresoFactura());
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
	
	public function timbrarReciboNomina()
	{
		set_time_limit(0); 
		
		$this->facturacion->timbrarReciboNomina();
	}
	
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	//FACTURA MANUAL
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	public function formularioFacturaManual()
	{
		/*$data['emisores']			= $this->facturacion->obtenerEmisores();
		$data['divisas']			= $this->configuracion->obtenerDivisas();
		$data['configuracion']		= $this->facturacion->obtenerConfiguracion();
		$data['metodos']			= $this->configuracion->obtenerMetodosPago();*/
		
		$data['emisores']			= $this->facturacion->obtenerEmisores();
		$data['configuracion']		= $this->facturacion->obtenerConfiguracion();
		$data['metodos']			= $this->configuracion->obtenerMetodosPago();
		$data['formas']				= $this->configuracion->obtenerFormasPago();
		$data['usos']				= $this->configuracion->obtenerUsosCfdi();
		$data['cliente']			= $this->facturacion->obtenerCliente($this->input->post('idCliente'));
		
		 $this->load->view('facturacion/manual/formularioFacturaManual',$data);
	}
	
	public function registrarFacturaManual()
	{
		if(!empty($_POST))
		{
			set_time_limit(0); 
			
			echo json_encode($this->manual->registrarFacturaManual());
		}
		else
		{
			echo json_encode(array('0','Error en el registro'));
		}
	}
	
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	//FACTURA GLOBAL
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	public function formularioFacturaGlobal()
	{
		$this->configuracion->actualizarAccesoFacturacion(1);
		
		$data['emisores']			= $this->facturacion->obtenerEmisores();
		$data['divisas']			= $this->configuracion->obtenerDivisas();
		$data['configuracion']		= $this->facturacion->obtenerConfiguracion();
		$data['metodos']			= $this->configuracion->obtenerMetodosPago();
		$data['formas']				= $this->configuracion->obtenerFormasPago();
		$data['usos']				= $this->configuracion->obtenerUsosCfdi();
		
		$data['periodos']			= $this->configuracion->obtenerPeriodicidad();
		$data['meses']				= $this->configuracion->obtenerMeses();
		
		 $this->load->view('facturacion/global/formularioFacturaGlobal',$data);
	}
	
	public function obtenerTotalesFactura()
	{
		$inicio						= $this->input->post('inicio');
		$fin						= $this->input->post('fin');
		$tipo						= $this->input->post('tipo');
		
		$data['ordenesVenta']		= $this->facturaGlobal->obtenerOrdenesVentaFactura($inicio,$fin);
		$data['totales']			= $this->facturaGlobal->obtenerTotalesVentaFactura($inicio,$fin,$tipo);
		$data['inicio']				= $inicio;
		$data['fin']				= $fin;
		$data['tipo']				= $tipo;
		
		 $this->load->view('facturacion/global/obtenerTotalesFactura',$data);
	}
	
	public function registrarFacturaGlobal()
	{
		if(!empty($_POST))
		{
			ini_set("memory_limit","1500M");
			set_time_limit(0); 
			
			echo json_encode($this->facturaGlobal->registrarFacturaGlobal());
		}
		else
		{
			echo json_encode(array('0','Error en el registro'));
		}
	}
	
	//FORMULARIO PARA FACTURAR INGRESO
	public function formularioGlobalIngresos()
	{
		$inicio						= $this->input->post('inicio');
		$fin						= $this->input->post('fin');
		$idCuenta					= $this->input->post('idCuenta');
		
		$idDepartamento				= $this->input->post('idDepartamento');
		$idProducto					= $this->input->post('idProducto');
		$idGasto					= $this->input->post('idGasto');
		$idCliente					= $this->input->post('idCliente');
		$idIngreso					= $this->input->post('idIngreso');
		$criterio					= $this->input->post('criterio');
		
		$idDepartamento				= strlen($idDepartamento)==0?0:$idDepartamento;
		$idProducto					= strlen($idProducto)==0?0:$idProducto;
		$idGasto					= strlen($idGasto)==0?0:$idGasto;
		
		$data['ingreso']			= $this->globalingresos->sumarIngresosGlobales($inicio,$fin,$idCuenta,$idDepartamento,$idProducto,$idGasto,$idCliente,$idIngreso,$criterio);
		$data['iva0']				= $this->globalingresos->obtenerNumeroIva0($inicio,$fin,$idCuenta,$idDepartamento,$idProducto,$idGasto,$idCliente,$idIngreso,$criterio);
		$data['iva16']				= $this->globalingresos->obtenerNumeroIva16($inicio,$fin,$idCuenta,$idDepartamento,$idProducto,$idGasto,$idCliente,$idIngreso,$criterio);
		
		$data['emisores']			= $this->facturacion->obtenerEmisores();
		$data['divisas']			= $this->configuracion->obtenerDivisas();
		
		$data['metodos']			= $this->configuracion->obtenerMetodosPago();
		$data['formas']				= $this->configuracion->obtenerFormasPago();
		$data['usos']				= $this->configuracion->obtenerUsosCfdi();

		$this->load->view('administracion/ingresos/cfdi/formularioGlobalIngresos',$data);
	}
	
	public function registrarGlobalIngresos()
	{
		if(!empty($_POST))
		{
			ini_set("memory_limit","1500M");
			set_time_limit(0); 
			
			echo json_encode($this->globalingresos->registrarGlobalIngresos());
		}
		else
		{
			echo json_encode(array('0','Error en el registro'));
		}
	}
	
	public function obtenerPreviaIngresos()
	{
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$this->load->helper('qrlib');
		$this->load->library('ccantidadletras');
		$this->load->library('mpdf/mpdf');
		
		$data['cliente'] 		= $this->clientes->obtenerDatosCliente($this->input->post('txtIdCliente'));
		$data['configuracion'] 	= $this->facturacion->obtenerEmisor($this->input->post('selectEmisores'));
		$data['cuentas']		= $this->configuracion->obtenerCuentasReportes();	
		$data['folio']			= $this->facturacion->obtenerFolio($this->input->post('selectEmisores'));
		$data['reporte']		= 'facturacion/previa/obtenerPreviaIngresos';	
		
		#generarCodigoBidimensional($data['factura'],$data['configuracion']);
		
		$this->ccantidadletras->setIdioma("ES");
        $this->ccantidadletras->setNumero($this->input->post('txtTotal'));
		$this->ccantidadletras->setMoneda('Pesos');//

		$data['cantidadLetra']	= $this->ccantidadletras->PrimeraMayuscula();

		$html					= $this->load->view('facturacion/principal',$data,true);
		$pie 					= $this->load->view('facturacion/pie',$data,true);
		
		$this->mpdf->mPDF('en-x','Letter','','',10,10,5,78,2,0);
		$this->mpdf->SetHTMLFooter($pie);
		$this->mpdf->SetHTMLFooter($pie,'E');
		$this->mpdf->mirrorMargins = 1;
		$this->mpdf->WriteHTML($html);
		
		$this->mpdf->Output(carpetaCfdi.'previa.pdf','F');
		
		echo json_encode(array($data['configuracion']->serie.$data['folio']));
	}
	
	//PAGOS DE FACTURACIÓN
	public function formularioPagos()
	{
		$idFactura				= $this->input->post('idFactura');
		$data['factura']		= $this->pagos->obtenerFactura($idFactura);
		$data['emisores']		= $this->facturacion->obtenerEmisores();
		$data['configuracion']	= $this->facturacion->obtenerConfiguracion();
		$data['formas']			= $this->configuracion->obtenerFormasPago();
		$data['pagos']			= $this->pagos->obtenerFacturasPagos($idFactura);
		$data['metodos']		= $this->configuracion->obtenerMetodosPago();
		
		$this->load->view('facturacion/pagos/formularioPagos',$data);
	}
	
	public function registrarPago()
	{
		if(!empty($_POST))
		{
			echo json_encode($this->pagos->registrarPago());
		}
		else
		{
			echo json_encode(array('0','Error al registrar el pago'));
		}
	}
	
	public function comando()
	{
		#resultado = exec('openssl x509 -inform DER -outform PEM -in media/fel/GATJ681216R7A/00001000000405897340.cer -pubkey -out media/fel/GATJ681216R7A/certificado.txt');
		#$resultado2 = exec('openssl pkcs8 -inform DER -in media/fel/GATJ681216R7A/CSD_JUAN_JOSE_GALARZA_GATJ681216R7A_20170420_163018.key -passin pass:Heraje86 -out media/fel/GATJ681216R7A/llave.txt');
		#$resultado = exec('openssl x509 -inform DER -outform PEM -in media/fel/GATJ681216R7A/00001000000405897340.cer -pubkey -out media/fel/certificado.pem');
		#$resultado2 = exec('openssl pkcs8 -inform DER -in media/fel/GATJ681216R7A/CSD_JUAN_JOSE_GALARZA_GATJ681216R7A_20170420_163018.key -passin pass:Heraje86 -out media/fel/llave.pem');
		
		$resultado3 = exec("openssl rsa -in media/fel/llave.pem -des3 -out media/fel/llaveDes3.txt -passout pass:\&ju1mDw[@17=");
		
		#echo $resultado;
		#echo $resultado2;
	}
	
	public function formularioTraslado()
	{
		#$this->configuracion->actualizarAccesoFacturacion(1);
		
		$idCotizacion					= $this->input->post('idCotizacion');
		$data['cotizacion']				= $this->facturacion->obtenerCotizacion($idCotizacion);
		$data['cliente']				= $this->facturacion->obtenerCliente($data['cotizacion']->idCliente);
		$data['emisores']				= $this->facturacion->obtenerEmisores();
		$data['divisas']				= $this->configuracion->obtenerDivisas();
		$data['productos']				= $this->facturacion->obtenerProductosCotizacion($idCotizacion);
		$data['direcciones']			= $this->clientes->obtenerDireccionesFiscales($data['cotizacion']->idCliente);
		$data['usos']					= $this->configuracion->obtenerUsosCfdi();
		$data['configuracion']			= $this->configuracion->obtenerFel();
		$data['parciales']				= $this->facturacion->obtenerTrasladosParciales($idCotizacion);
		$data['idCotizacion']			= $idCotizacion;
		
		$this->load->view('facturacion/traslado/formularioRegistro',$data);
	}
	
	public function registrarTrasldo()
	{
		if(!empty($_POST))
		{
			echo json_encode($this->traslados->registrarTrasldo());
		}
		else
		{
			echo json_encode(array('0','Error en el registro'));
		}
	}
}

?>
