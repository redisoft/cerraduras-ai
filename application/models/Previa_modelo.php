<?php
class Previa_modelo extends CI_Model 
{
    protected $_fecha_actual;
    protected $_table;
    protected $_iduser;
	protected $idLicencia;
	protected $resultado;
	protected $facturaId;

    function __construct() 
	{
        parent::__construct();
        #$this->config->load('datatables', TRUE);
        #$this->_table = $this->config->item('datatables');

        $datestring 			= "%Y-%m-%d %H:%i:%s";
        $this->_fecha_actual 	= mdate($datestring, now());
        $this->_iduser 			= $this->session->userdata('id');
		$this->idLicencia 		= $this->session->userdata('idLicencia');
		$this->resultado		= "1";
		$this->facturaId		= "0";
		
		$this->cambiarFechaActual();
    }
	
	public function cambiarFechaActual()
	{
		$sql="select date_sub('".date('Y-m-d H:i:s')."', interval 20 minute) as fechaActual";
		
		$this->_fecha_actual=$this->db->query($sql)->row()->fechaActual;
	}
	
	public function realizarVentaPrevia() 
	{
		$this->db->trans_start(); #Se Inicia una transaccion porque se inserta en mas de 2 tablas
		
		$sql=" truncate table facturas_detalles_vista;";
		$this->db->query($sql);
		
		$sql=" truncate table facturas_retenciones_vista; ";
		$this->db->query($sql);
		
		$sql="truncate table facturas_vista;";
		$this->db->query($sql);
		
		$sql="truncate table cotiza_productos_vista;";
		$this->db->query($sql);
		
		$sql="truncate table cotizaciones_vista;";
		$this->db->query($sql);
		
		$idForma		=$this->input->post('idForma');
		$folio			=$this->obtenerFolio();
		$divisa			=$this->obtenerDivisa($this->input->post('idDivisa'));
		#$tipo			=$this->tipoCotizacion($formaPago);
		
		$cantidad		=$this->input->post('cantidad');
		$productos		=$this->input->post('productos');
		$preciosTotales	=$this->input->post('preciosTotales');
		$precioProducto	=$this->input->post('precioProducto');
		
		$subtotal		=$this->input->post('subTotal');
		$iva			=$this->input->post('iva');
		$total			=$this->input->post('total');
		$descuento		=0;#$this->input->post('descuento');
		$idCliente		=$this->input->post('idCliente');
		$pago			=$this->input->post('pago');
		$cliente		=$this->obtenerCliente($idCliente);

		//$this->session->set_userdata('idVenta',$idVenta);
		
		#--------------------------ORDEN DE VENTAS----------------------------#
		$serie="COT-".date('Y-m-d').'-'.$folio;
		$venta="VEN-".$folio;
		#---------------------------------------------------------------------#

		$comentarios	="Venta directa";
		
		$data=array
		(
			'ordenCompra'		=> $venta,
			'idCliente'			=> $idCliente,
			'fecha'				=> $this->_fecha_actual,
			'fechaPedido'		=> $this->_fecha_actual,
			'fechaEntrega'		=> $this->_fecha_actual,
			'serie'				=> $serie,
			'estatus'			=> '1',
			'idUsuario'			=> $this->_iduser,
			'fechaCompra'		=> $this->_fecha_actual,
			'pago'				=> $pago,
			'cambio'			=> $this->input->post('cambio'), 
			
			'descuento'			=> $this->input->post('descuento'),
			'descuentoPorcentaje'=> $this->input->post('descuentoPorcentaje'),
			'subTotal'			=> $subtotal,
			'ivaPorcentaje'		=> $this->input->post('ivaPorcentaje'),
			'iva'				=> $this->input->post('iva'),
			'total'				=> $total,
			
			'folio'				=> $folio,
			'idLicencia'		=> $this->idLicencia,
			'comentarios'		=> $comentarios,
			'idDivisa'			=> $divisa->idDivisa,
			'tipoCambio'		=> $divisa->tipoCambio,
			#'observaciones'		=>$this->input->post('observaciones'), 
		);
		
		$this->db->insert('cotizaciones_vista',$data);
		$idCotizacion	=$this->db->insert_id();
		
		$this->procesarProductosVenta($idCotizacion,$cantidad,$productos,$preciosTotales,$precioProducto);
		
		
		$this->vistaPrevia($idCotizacion,$cliente);
		/*if($llevar==1)
		{
			$this->pagoVenta($idCotizacion); #Se supone que todas las ventas son de contado por eso se incluye este metodo
		}*/
		
		if ($this->db->trans_status() === FALSE or $this->resultado!="1")
		{
			$this->db->trans_rollback(); #Revertir si hubo algun fallo en las actualizaciones
			$this->db->trans_complete();
			
			return $this->resultado;
		}
		else
		{
			$this->db->trans_commit(); #Guargar los cambios si todo ha sido correcto
			$this->db->trans_complete();
			
			return $this->facturaId;
		}
	}
	
	public function obtenerDetallesServicio($idProducto)
	{
		$sql=" select manoObra, refacciones
		from productos
		where idProducto='$idProducto' ";
		
		return $this->db->query($sql)->row();
	}
	
	public function obtenerPeriodo($idPeriodo)
	{
		$sql="select * from produccion_periodos
		where idPeriodo='$idPeriodo' ";
		
		return $this->db->query($sql)->row();
	}
	
	public function obtenerFechaFinServicio($valor,$factor,$fecha)
	{
		$sql="select date_add('".$fecha."',interval ".$valor." $factor) as fechaFin";
		
		return $this->db->query($sql)->row()->fechaFin;
	}
	
	public function procesarProductosVenta($idCotizacion,$cantidad,$productos,$preciosTotales,$precioProducto)
	{
		$servicios		= $this->input->post('servicios');
		$fechas			= $this->input->post('fechas');
		$nombres		= $this->input->post('nombres');
		$descuentos		= $this->input->post('descuentos');
		
		for($i=0;$i<count($cantidad);$i++)
		{
			$descuento	= explode('|',$descuentos[$i]);
			
			$data=array
			(
				'idCotizacion' 		=> $idCotizacion,
				'cantidad' 			=> $cantidad[$i],
				'precio' 			=> $precioProducto[$i],
				'importe' 			=> $preciosTotales[$i],
				'idProduct' 		=> $productos[$i],
				'tipo' 				=> $precioProducto[$i],
				'nombre' 			=> $nombres[$i],
				'fechaInicio' 		=> $this->_fecha_actual,
				'fechaVencimiento' 	=> $this->_fecha_actual,
				'notificar' 		=> 0,
				'facturado' 		=> '0',
				'enviado' 			=> '1',
				'entregado' 		=> '1',
				'produccion' 		=> '1',
				'servicio' 			=> $servicios[$i],
				
				'descuento' 			=> $descuento[1],
				'descuentoPorcentaje'	=> $descuento[0],
			);
			
			if($servicios[$i]!=0)
			{
				/*$data['servicio']			=1;
				
				if($servicios[$i]!=8)
				{
					$periodo					= $this->obtenerPeriodo($servicios[$i]);
					$fechaFin					= $this->obtenerFechaFinServicio($periodo->valor*$cantidad[$i],$periodo->factor,$fechas[$i]);
					$data['fechaInicio']		= $fechas[$i];
					$data['fechaVencimiento']	= $fechaFin;
					$data['notificar']			= 1;
				}*/
			}
			
			$this->db->insert('cotiza_productos_vista',$data);
		}
	}
	
	public function obtenerFolio()
	{
		$serie			=$this->session->userdata('serie');
		$folioInicio	=$this->session->userdata('folioInicio');
		$folioFinal		=$this->session->userdata('folioFinal');
		
		$sql=" 	select max(folio) as folio
		from facturas
		where idLicencia='$this->idLicencia' ";
		
		if(strlen($serie)>0)
		{
			$sql.=" and serie='".$serie."'";
		}
		
		$folio=$this->db->query($sql)->row()->folio;
		
		if($folio==null)
		{
			$folio=$folioInicio;
		}
		else
		{
			$folio++;
		}
		
		if($folio>$folioFinal)
		{
			$folio=-1;
		}
		
		return $folio;
	}
	
	public function obtenerCliente($idCliente)
	{
		$sql="select * from clientes
		where idCliente='$idCliente'";
		
		return $this->db->query($sql)->row();
	}
	
	public function obtenerConfiguracion()
	{
		$sql="select * from configuracion ";
		
		return $this->db->query($sql)->row();
	}
	
	public function obtenerProductosCotizacion($idCotizacion)
	{
		$sql="select a.*, b.nombre as producto, 
		b.precioA, b.unidad, b.codigoInterno
		from cotiza_productos_vista as a
		inner join productos as b
		on a.idProduct=b.idProducto
		where a.idCotizacion='$idCotizacion'";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerCotizacion($idCotizacion)
	{
		$sql=" select * from cotizaciones_vista
		where idCotizacion='$idCotizacion' ";
		
		return $this->db->query($sql)->row();
	}

	public function obtenerDivisa($idDivisa)
	{
		$sql="select * from divisas
		where idDivisa='$idDivisa' ";
		
		return $this->db->query($sql)->row();
	}

	#------------------------------------------------------------------------------------------------------#
	public function vistaPrevia($idCotizacion,$cliente)
	{

		$this->load->helper('sat');
		
		#$idCotizacion	=$this->input->post('idCotizacion');
		$idEmisor		=$this->input->post('idEmisor');
		$configuracion	=$this->facturacion->obtenerEmisor($idEmisor);
		$cotizacion		=$this->obtenerCotizacion($idCotizacion);
		#$cliente		=$this->obtenerCliente($idCliente);
		$comprobante	='ingreso';
		
		$retenciones['importe']	=0;
		$retenciones['tasa']	=0;
		$retenciones['nombre']	=0;
		
		$divisa					=$this->obtenerDivisa($this->input->post('idDivisa'));
		 
		if($comprobante=="ingreso")
		{
			$productos	=$this->obtenerProductosCotizacion($cotizacion->idCotizacion);
		}

		if(strlen($cliente->rfc)<12 
		or strlen($cliente->empresa) <3 
		or strlen($cliente->pais) <3 )
		{
			$this->db->trans_rollback(); #Revertir si hubo algun fallo en las actualizaciones
			$this->db->trans_complete();
			
			$this->resultado="2";
		}
		
		$folio	=$this->facturacion->obtenerFolio($idEmisor);
		
		if($folio<1)
		{
			$this->db->trans_rollback(); #Revertir si hubo algun fallo en las actualizaciones
			$this->db->trans_complete();
			
			$this->resultado="4";; #Los folios se han terminado
		}
		
		$carpetaFel			='media/fel/';
		$carpetaUsuario		='media/fel/'.$configuracion->rfc.'/';
		$carpetaFolio		=$carpetaUsuario.'vistaPrevia/';
		$cfd				=$carpetaFolio.'cfd'.$folio.'.xml';
		
		crearDirectorio($carpetaFolio);
		
		$sello				="";
		$certificado		="";

		
		if($comprobante=="ingreso")
		{
 			#$ficheroXML	=RegresaXMLFormato($configuracion,$cliente,$productos,$sello,$certificado,$this->_fecha_actual,$folio,$cotizacion,$retenciones,$divisa,$refacciones,$mano);
		}

		#guardarArchivoXML($cfd,$ficheroXML);
		
		exec("xsltproc ".$carpetaFel.'cadenaoriginal_3_2.xslt'." ".$cfd." > ".$carpetaFolio.'cadena.txt'); #Comentado mejor quitarlo jaja
		exec("openssl pkcs8 -inform DER -in ".$carpetaUsuario.$configuracion->llave." -passin pass:".$configuracion->passwordLlave." -out ".$carpetaFolio.'certificado.txt');
		exec("openssl dgst -sha1 -sign ".$carpetaFolio."certificado.txt ".$carpetaFolio."cadena.txt | openssl enc -base64 -A > ".$carpetaFolio.'sello.txt');
		exec("openssl enc -base64 -in ".$carpetaUsuario.$configuracion->certificado." -out ".$carpetaFolio.'certificadoImprimir.txt');
		
		$certificado 	='';#QuitarEspaciosXML($certificado,"B");
		$sello 			='';#$QuitarEspaciosXML($sello,"B");
		$cadena			='';#openFile($carpetaFolio.'cadena.txt',"READ","");
		

		if($comprobante=="ingreso")
		{
			#$ficheroXML=RegresaXMLFormato($configuracion,$cliente,$productos,$sello,$certificado,$this->_fecha_actual,$folio,$cotizacion,$retenciones,$divisa,$refacciones,$mano); 
		}

		$this->agregarFacturaVista('',$folio,$sello,$cadena,$cotizacion,$cliente,$configuracion);
		
		
	}
 	
	public function obtenerProductosProductos($idCotizacion)
	{
		$sql="  select a.precio, a.cantidad, a.importe,
		b.nombre as descripcion, a.servicio, c.nombre as periodo,
		a.nombre as producto, b.medida, b.rin, b.codigoInterno,
		a.servicio, b.unidad, a.descuento, a.descuentoPorcentaje,
		a.idProducto, a.nombre, a.idProduct
		from cotiza_productos as a
		inner join productos as b
		on a.idProduct=b.idProducto
		inner join produccion_periodos as c
		on b.idPeriodo=c.idPeriodo
		where a.idCotizacion='$idCotizacion' 
		and a.servicio=0 ";
		
		return $this->db->query($sql)->result();
	}

	public function agregarFacturaVista($xml,$folio,$sello,$cadena,$cotizacion,$cliente,$configuracion)
	{
		//VACIAR LAS VISTAS PREVIAS
		$subTotal	=$cotizacion->subTotal;
		$iva		=$cotizacion->iva;
		$descuento	=$cotizacion->descuento;
		$total		=$cotizacion->total;

		$productos	=$this->obtenerProductosCotizacion($cotizacion->idCotizacion); 
		/*$productos	=$this->obtenerProductosProductos($cotizacion->idCotizacion); 
		$refacciones=$this->obtenerProductosRefacciones($cotizacion->idCotizacion);
		$mano		=$this->obtenerProductosMano($cotizacion->idCotizacion);*/
		
		$retenciones['importe']	=0;//$this->input->post('retencion');
		$retenciones['tasa']	=0;//$this->input->post('tasa');
		$retenciones['nombre']	='';//$this->input->post('nombre');
		
		if($retenciones['importe']>0)
		{
			$total	=$total-$retenciones['importe'];
		}
		
		$divisa		=$this->obtenerDivisa($this->input->post('idDivisa'));
		
		$comentarios	="";
		$formaPago		=$this->configuracion->obtenerForma($this->input->post('idForma'));
		$formaPago		=$formaPago!=null?$formaPago->nombre:'';
		
		$data=array
		(
			'rfc'				=>$cliente->rfc,
			'pais'				=>$cliente->pais,
			'direccion'			=>$cliente->calle,
			'estado'			=>$cliente->estado,
			'ciudad'			=>$cliente->localidad,
			'numero'			=>$cliente->numero,
			'codigoPostal'		=>$cliente->codigoPostal,
			'empresa'			=>$cliente->empresa,
			'telefono'			=>$cliente->telefono,
			'email'				=>$cliente->email,
			'colonia'			=>$cliente->colonia,
			'subTotal'			=>$cotizacion->subTotal,
			'iva'				=>$cotizacion->iva,
			'descuento'			=>$cotizacion->descuento,
			'ivaPorcentaje'				=>$cotizacion->ivaPorcentaje,
			'descuentoPorcentaje'			=>$cotizacion->descuentoPorcentaje,
			'total'				=>$cotizacion->total,
			'folio'				=>$folio,
			'fecha'				=>$this->_fecha_actual,
			'xml'				=>$xml,
			'cadenaOriginal'	=>$cadena,
			'selloSat'			=>'',
			'selloDigital'		=>$sello,
			'UUID'				=>'',
			'certificadoSat'	=>'',
			'cadenaTimbre'		=>'',	
			'fechaTimbrado'		=>'',
			'idLicencia'		=>$this->idLicencia,
			'idCotizacion'		=>$cotizacion->idCotizacion,
			'idCliente'			=>$cotizacion->idCliente,
			'documento'			=>'FACTURA',
			'tipoComprobante'	=>'I',
			'serie'				=>$configuracion->serie,
			
			'condicionesPago'	=>$this->input->post('condiciones'),
			'metodoPago'		=>$this->input->post('metodoPagoTexto'),
			'formaPago'			=>$this->input->post('formaDePago'),
			'observaciones'		=>$this->input->post('observaciones'),
			
			'divisa'			=>$divisa->nombre,
			'claveDivisa'		=>$divisa->clave,
			'tipoCambio'		=>$divisa->tipoCambio,
			'idEmisor'			=>$this->input->post('idEmisor'),
		);
		
		$this->db->insert('facturas_vista',$data);
		$idFactura 			= $this->db->insert_id();
		$this->facturaId	=$idFactura;
		
		#-------------------------------------------------------------------------------------#
		$data=array();
		$data['encriptacion']	=sha1("'".$idFactura.$this->_fecha_actual."'"); 
		
		$this->db->where('idFactura',$idFactura); 
		$this->db->update('facturas',$data);
		
		#GUARDAR EL DETALLE DE PRODUCTOS
		#-------------------------------------------------------------------------------------#
		foreach($productos as $row)
		{
			$data=array
			(
				'idFactura'				=>$idFactura,
				'idProducto'			=>$row->idProducto,
				'nombre'				=>$row->nombre,
				'unidad'				=>$row->unidad,
				'precio'				=>$row->precio,
				'importe'				=>$row->importe,
				'cantidad'				=>$row->cantidad,
				'codigoInterno'			=>$row->codigoInterno,
				'descuento'				=>$row->descuento,
				'descuentoPorcentaje'	=>$row->descuentoPorcentaje,
			);
			
			$this->db->insert('facturas_detalles_vista',$data);
		}

		#-------------------------------------------------------------------------------------#

		if($retenciones['importe']>0)
		{
			$data=array
			(
				'idFactura'		=>$idFactura,
				'retencion'		=>$retenciones['nombre'],
				'tasa'			=>$retenciones['tasa'],
				'importe'		=>$retenciones['importe'],
				'idCotizacion'	=>$cotizacion->idCotizacion,
			);
			
			$this->db->insert('facturas_retenciones_vista',$data);
		}
	}
	
	public function obtenerFacturaVista()
	{
		$sql="select * from facturas_vista 
		order by fecha desc 
		limit 1 ";
		
		return $this->db->query($sql)->row();
	}
}
?>
