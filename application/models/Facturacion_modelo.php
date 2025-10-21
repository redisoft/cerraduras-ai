<?php
class Facturacion_modelo extends CI_Model 
{
    protected $_fecha_actual;
    protected $_table;
    protected $_iduser;
	protected $idLicencia;
	protected $resultado;
	protected $idFactura;

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
		$this->idFactura		= 0;
		
		#$this->cambiarFechaActual();
		/*$this->load->library('ccantidadletras');
		$this->load->library('mpdf/mpdf');*/
		
		#$this->cambiarFechaActual();
		
    }
	
	public function cambiarFechaActual()
	{
		$sql="select date_sub('".date('Y-m-d H:i:s')."', interval 60 minute) as fechaActual";
		
		$this->_fecha_actual=$this->db->query($sql)->row()->fechaActual;
	}
	
	public function numeroFacturasCliente($idCliente,$idFactura)
	{
		$sql="select idFactura
		from facturas
		where idCliente='$idCliente'
		and idLicencia='$this->idLicencia'
		and pendiente='0' ";
		
		$sql.=$idFactura>0?" and idFactura='$idFactura' ":'';
		
		return $this->db->query($sql)->num_rows();
	}
	
	public function obtenerFacturasCliente($num,$Limite,$idCliente,$idFactura)
	{
		$sql="select * from facturas
		where idCliente='$idCliente'
		and idLicencia='$this->idLicencia'
		and pendiente='0' ";
		
		$sql.=$idFactura>0?" and idFactura='$idFactura' ":'';
		
		$sql.=" order by fecha desc
		 limit $Limite, $num";
		
		return $this->db->query($sql)->result();
	}
	
	//OBTENER LOS EMISORES PARA LA FACTURACIÓN
	public function obtenerEmisores()
	{
		$sql="select idEmisor, rfc, nombre, serie
		 from configuracion_emisores
		 where idLicencia='$this->idLicencia'
		 and activo='1' ";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerEmisor($idEmisor)
	{
		$sql=" select a.*, 
		b.nombre as regimen, b.clave as claveRegimen,
		
		c.url, c.usuario as usuarioPac, c.password as passwordPac, c.pac
		
		from configuracion_emisores as a
		inner join fac_catalogos_regimen_fiscal as b
		on a.idRegimen=b.idRegimen
		
		inner join configuracion_pacs as c
		on a.idPac=c.idPac
		
		where a.idEmisor='$idEmisor' ";
		
		return $this->db->query($sql)->row();
	}
	
	public function obtenerEmisorFolios($idEmisor)
	{
		$sql="select idEmisor, folioInicial, 
		folioFinal, serie, rfc
		from configuracion_emisores
		where idEmisor='$idEmisor' ";
		
		return $this->db->query($sql)->row();
	}
	
	public function comprobarFoliosDisponibles()
	{
		$comprados	= $this->configuracion->obtenerFoliosComprados();

		$folios		= $this->configuracion->obtenerFoliosConsumidosTotal();
		
		if($comprados-$folios<1)
		{
			return false;
		}
		else
		{
			return true;
		}
	}
	
	public function obtenerFolio($idEmisor)
	{
		$emisor		= $this->obtenerEmisorFolios($idEmisor);
		#$errores	= $this->configuracion->obtenerErrores($idEmisor);
		
		$sql=" select coalesce(max(folio),0) as folio
		from facturas
		where idEmisor='$idEmisor' ";
		
		if(strlen($emisor->serie)>0)
		{
			$sql.=" and serie='".$emisor->serie."'";
		}
		
		$folio	=$this->db->query($sql)->row()->folio;
		
		if($folio==0)
		{
			$folio	=$emisor->folioInicial;
		}
		else
		{
			$folio++;
		}
		
		/*if(($folio+$errores)>$emisor->folioFinal)
		{
			$folio="0";
		}*/
		
		return $folio;
	}
	
	public function obtenerUltimaFactura($idCotizacion)
	{
		$sql=" select idFactura, condicionesPago, metodoPago,
		formaPago, pendiente 
		from facturas
		where idCotizacion='$idCotizacion' 
		order by fecha desc
		limit 1 ";
		
		return $this->db->query($sql)->row();
	}
	
	
	
	/*public function obtenerFolio()
	{
		$serie			=$this->session->userdata('serie');
		$folioInicio	=$this->session->userdata('folioInicio');
		$folioFinal		=$this->session->userdata('folioFinal');
		
		$sql="select max(folio) as folio
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
	}*/
	
	public function obtenerCliente($idCliente)
	{
		$sql="select * from clientes
		where idCliente='$idCliente'";
		
		return $this->db->query($sql)->row();
	}
	
	public function obtenerConfiguracion()
	{
		$sql="select * from configuracion
		where idLicencia='$this->idLicencia' ";
		
		return $this->db->query($sql)->row();
	}
	
	public function obtenerProductosCotizacion($idCotizacion)
	{
		$sql=" select a.*, b.nombre as producto, 
		b.precioA,  b.codigoInterno,
		c.nombre as unidad, c.clave as claveUnidad,
		d.nombre as claveDescripcion, d.clave as claveProducto,
		
		e.tasa, e.importe as importeImpuesto, e.tipo, e.nombre as nombreImpuesto,
		f.exento, g.clave as claveImpuesto,
		
		(select coalesce(sum(h.cantidad),0) from cotizaciones_devoluciones_detalles as h
		inner join cotizaciones_devoluciones as i
		on i.idDevolucion=h.idDevolucion
		where a.idProducto=h.idProducto
		and i.idTipo!=1) as devueltos,
		
		i.fecha, i.anio, i.aduana, i.patente, i.digitos
		
		from cotiza_productos as a
		inner join productos as b
		on a.idProduct=b.idProducto
		
		inner join fac_catalogos_unidades as c
		on c.idUnidad=b.idUnidad
		
		inner join fac_catalogos_claves_productos as d
		on d.idClave=b.idClave
		
		inner join cotiza_productos_impuestos as e
		on e.idProducto=a.idProducto
		
		inner join configuracion_impuestos as f
		on e.idImpuesto=f.idImpuesto
		
		inner join fac_impuestos as g
		on f.idCatalogoImpuesto=g.idCatalogoImpuesto

		left join productos_pedimentos as i
		on b.idPedimento=i.idPedimento
		
		where a.idCotizacion='$idCotizacion' 
		order by a.idProducto asc ";
		
		return $this->db->query($sql)->result();
	}
	
	public function sumarProductosParciales($idCotizacion,$idProducto)
	{
		$sql=" select coalesce(sum(b.cantidad),0) as cantidad
		from facturas as a
		inner join facturas_detalles as b
		on a.idFactura=b.idFactura
		where a.idCotizacion='$idCotizacion' 
		and b.idProducto='$idProducto'
		and a.cancelada=0 ";
		
		#echo $sql.'<br />';
		
		return $this->db->query($sql)->row()->cantidad;
	}
	
	public function obtenerCotizacion($idCotizacion)
	{
		$sql=" select a.*, 
		(select b.nombre from tiendas as b where b.idTienda=a.idTienda) as tienda,
		(select b.nombre from configuracion_estaciones as b where b.idEstacion=a.idEstacion) as estacion,
		(select b.empresa from clientes as b where b.idCliente=a.idCliente) as cliente,
		(select coalesce(sum(b.importe),0) from cotizaciones_devoluciones as b where b.idCotizacion=a.idCotizacion) as devoluciones,
		(select coalesce(sum(b.pago),0) from catalogos_ingresos as b where b.idVenta=a.idCotizacion and b.idForma!=4) as pagado
		from cotizaciones as a
		where a.idCotizacion='$idCotizacion' ";
		
		return $this->db->query($sql)->row();
	}
	
	#------------------------------------------------------------------------------------------------------#
	#-----------------------------------------NOTAS DE CRÉDITO --------------------------------------------#
	#------------------------------------------------------------------------------------------------------#
	
	public function obtenerProductosNota($idCotizacion)
	{
		$sql="select a.*, b.*
		from cotiza_productos as a
		inner join productos as b
		on a.idProduct=b.idProducto
		where a.idCotizacion='$idCotizacion'
		and devueltos>0";
		
		$query = $this->db->query($sql);
		
		return ($query->num_rows() > 0) ? $query->result() : NULL;
	}
	
	#------------------------------------------------------------------------------------------------------#
	
	public function verificarStockProductos($productos)
	{
		foreach($productos as $row)
		{
			$sql="select b.stock
			from rel_producto_produccion as a
			inner join produccion_productos as b
			on b.idProducto=a.idProductoProduccion
			where a.idProducto='$row->idProduct'";
			
			$query=$this->db->query($sql)->row();
			
			if($row->cantidad>$query->stock)
				return 1;
		}
		
		return 0;
	}
	
	public function obtenerImpuestosProductos($idProducto)
	{
		$sql=" select *
		from cotiza_productos_impuestos as a
		where a.idProducto='$idProducto'";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerImpuestosProductosCotizacion($idCotizacion)
	{
		$sql=" select sum(a.importe) as importe, a.tasa, a.tipo, a.nombre, a.idImpuesto
		from cotiza_productos_impuestos as a
		inner join cotiza_productos as b
		on a.idProducto=b.idProducto
		where b.idCotizacion='$idCotizacion'
		and a.importe>0 
		group by a.tipo, a.tasa";
		
		return $this->db->query($sql)->result();
	}
	
	public function procesarImpuestos($idCotizacion)
	{
		$impuestos		= $this->obtenerImpuestosProductosCotizacion($idCotizacion);
		$data			= array();
		$i				= 1;
		$importeTotal	= 0;
		
		foreach($impuestos as $row)
		{
			$data[$i]['tasa']		= $row->tasa;
			$data[$i]['tipo']		= $row->tipo;
			$data[$i]['nombre']		= $row->nombre;
			$data[$i]['importe']	= $row->importe;
			
			$importeTotal			+=$row->importe;
			
			$i++;
		}
		
		$data[0]['tasa']		= 0;
		$data[0]['tipo']		= '';
		$data[0]['nombre']		= '';
		$data[0]['importe']		= $importeTotal;
		
		return $data;
	}
	
	#------------------------------------------------------------------------------------------------------#
	
	public function comprobarTablasBloqueadas()
	{
		$base	= $this->load->database('default',TRUE);
		
		$sql	= "SHOW OPEN TABLES WHERE `Database` = '$base->database' and In_use > 0";	
		
		$tablas	= $this->db->query($sql)->result();
		$numero	= 0;
		
		foreach($tablas as $row)
		{
			if($row->Table=='configuracion' or $row->Table=='configuracion_emisores' or $row->Table=='facturas' or $row->Table=='facturas_errores')
			{
				$numero++;
			}
		}
		
		return $numero;
	}
	
	public function revisarFacturaRealizada($idCotizacion)
	{
		$sql=" select count(a.idFactura) as numero
		from facturas as a
		inner join rel_factura_cotizacion as b
		on a.idFactura=b.idFactura
		where b.idCotizacion='$idCotizacion' 
		and a.cancelada='0'
		and a.tipoComprobante!='traslado' ";
		
		return $this->db->query($sql)->row()->numero>0?false:true;
	}
	
	public function crearCFDI()
	{
		#$this->db->trans_start(); #Se Inicia una transaccion porque se inserta y actualiza
		
		#sleep(rand(2,5));
		
		if(!$this->comprobarFoliosDisponibles())
		{
			return array('0','Los folios se han terminado, por favor consulte con el administrador','','','');
		}
		
		$this->load->helper('sat');
		$this->load->helper('cfdi');
		
		$idCotizacion			= $this->input->post('txtIdCotizacion');
		$idEmisor				= $this->input->post('selectEmisores');
		$idDireccion			= $this->input->post('selectDireccionesCfdi');
		
		$fechaFactura			= $this->input->post('txtFechaFactura');
		$diferencia				= $this->reportes->obtenerDiferenciaFechas($fechaFactura,date('Y-m-d H:i'),'minute');
		
		if($diferencia>4319)
		{
			return array('0',"La fecha es mayor a las 72 horas permitidas",'','','');
		}
		
		if($diferencia<0)
		{
			return array('0',"La fecha esta en el futuro",'','','');
		}
		
		if(!$this->revisarFacturaRealizada($idCotizacion))
		{
			return array('0',"La factura ya ha sido registrada",'','','');
		}
		
		if($this->comprobarTablasBloqueadas()>=4)
		{
			return array('0',"Hay una factura en proceso, por favor espere que termine de ejecutarse",'','','');
		}
		
		$this->_fecha_actual	= $fechaFactura.':00';
		
		
		
		#$configuracion			= $this->obtenerConfiguracion();
		$configuracion			= $this->obtenerEmisor($idEmisor);
		$cotizacion				= $this->obtenerCotizacion($idCotizacion);
	#	$cliente				= $this->obtenerCliente($cotizacion->idCliente);
		$cliente				= $this->clientes->obtenerDireccionesEditar($idDireccion);
		
		$comprobante			= $this->input->post('tipoComprobante');
		$divisa					= $this->obtenerDivisa($this->input->post('selectDivisas'));
		
		$retenciones['importe']	= 0;#$this->input->post('retencion');
		$retenciones['tasa']	= 0;#$this->input->post('tasa');
		$retenciones['nombre']	= 0;#$this->input->post('nombre');
		
		$data	=array();
		
		#$impuestos				= $this->procesarImpuestos($idCotizacion);
		
		$productos				= $this->obtenerProductosCotizacion($cotizacion->idCotizacion);

		if(strlen($cliente->rfc)<12 or strlen($cliente->razonSocial) <3 or strlen($cliente->codigoPostal) !=5 )
		{
			#$this->db->trans_rollback(); #Revertir si hubo algun fallo en las actualizaciones
			#$this->db->trans_complete();

			return array('0',"El cliente no tiene los datos fiscales necesarios para crear la factura",'','','');
		}
		
		$sql=" lock table facturas write, configuracion_emisores write, facturas_errores write, configuracion write "; //Bloquear el folio, para evitar duplicidad
		$this->db->query($sql);
		
		
		$folio		= $this->obtenerFolio($idEmisor);
		
		if($folio<1)
		{
			#$this->db->trans_rollback(); #Revertir si hubo algun fallo en las actualizaciones
			#$this->db->trans_complete();
			
			$sql=" unlock tables";
			$this->db->query($sql);
			
			return array('0',"Error de servidor SAT",'','','');
		}
		
		$carpetaUsuario		= carpetaCfdi.$configuracion->rfc.'/';
		$carpetaFolio		= $carpetaUsuario.'folio'.$configuracion->serie.$folio.'/';
		$cfd				= $carpetaFolio.'cfd'.$folio.'.xml';
		
		crearDirectorio($carpetaFolio);
		
		$sello				="";
		$certificado		="";

		$ficheroXML		= xmlCfd($configuracion,$cliente,$productos,$sello,$certificado,$this->_fecha_actual,$folio,$cotizacion,$retenciones,$divisa);
		
		guardarArchivoXML($cfd,$ficheroXML);
		
		exec("xsltproc ".carpetaCfdi.'cadenaoriginal4.xslt'." ".$cfd." > ".$carpetaFolio.'cadena.txt');
		exec("openssl pkcs8 -inform DER -in ".$carpetaUsuario.$configuracion->llave." -passin pass:".$configuracion->passwordLlave." -out ".$carpetaFolio.'certificado.txt');
		exec("openssl dgst -sha256 -sign ".$carpetaFolio."certificado.txt ".$carpetaFolio."cadena.txt | openssl enc -base64 -A > ".$carpetaFolio.'sello.txt');
		exec("openssl enc -base64 -in ".$carpetaUsuario.$configuracion->certificado." -out ".$carpetaFolio.'certificadoImprimir.txt');
		
		$certificado	= leerFichero($carpetaFolio.'certificadoImprimir.txt',"READ","");
		$certificado 	= QuitarEspaciosXML($certificado,"B");
		$sello			= leerFichero($carpetaFolio.'sello.txt',"READ","");
		$sello 			= QuitarEspaciosXML($sello,"B");
		$cadena			= leerFichero($carpetaFolio.'cadena.txt',"READ","");

		$ficheroXML		= xmlCfd($configuracion,$cliente,$productos,$sello,$certificado,$this->_fecha_actual,$folio,$cotizacion,$retenciones,$divisa); 
		
		if(guardarArchivoXML($cfd,$ficheroXML))
		{
			if($configuracion->pac=='4gFactor')
			{
				$this->timbrarFactor($ficheroXML,$folio,$carpetaFolio,$sello,$cadena,$cotizacion,$cliente,$configuracion,$productos,$divisa);
			}
			
			if($configuracion->pac=='finkok')
			{
				$this->timbrarFinkok($ficheroXML,$folio,$carpetaFolio,$sello,$cadena,$cotizacion,$cliente,$configuracion,$productos,$divisa);
			}
		}
	
		#if ($this->db->trans_status() === FALSE or $this->resultado!="1")
		if($this->resultado!="1")
		{
			#$this->db->trans_rollback(); #Revertir si hubo algun fallo en las actualizaciones
			#$this->db->trans_complete();
			
			$sql=" unlock tables";
			$this->db->query($sql);
			
			$data	= array('0',$this->resultado,'','','');
		}
		else
		{
			#$this->db->trans_commit(); #Guargar los cambios si todo ha sido correcto
			#$this->db->trans_complete();
			
			#$this->session->set_userdata('notificacion','La factura se ha creado correctamente');
			
			
			
			$data	= array('1','La factura se ha creado correctamente',$this->idFactura,$cliente->email,strlen($cliente->email)>0?'1':'0');
		}
		
		return $data;
	}
	
	public function timbrarFinkok($ficheroXML,$folio,$carpetaFolio,$sello,$cadena,$cotizacion,$cliente,$configuracion,$productos,$divisa)
	{
		$this->load->library('finkok');
		$this->load->helper('xmlcfdi');

		$timbrado 		= new Finkok();
		$respuesta 		= $timbrado->obtenerTimbre($configuracion->usuarioPac,$configuracion->passwordPac,$configuracion->url,$ficheroXML);

		if(!$respuesta['estatus'])
		{
			$this->resultado	= $respuesta['mensaje'];
			
			if($respuesta['codigoError']!='0')
			{
				$this->registrarError($respuesta['codigoError'],$respuesta['mensaje'],$configuracion->idEmisor);	
			}

			return 0;
		}
		
		if($respuesta['estatus'])
		{
			$timbre		=$carpetaFolio.'cfdi'.$folio.'Timbre.xml'; #Es el archivo XML Timbrado
			$fichero	=fopen($timbre,"w");	
			fwrite($fichero,$respuesta['xml']);
			fclose($fichero);
			
			$data['xml']			= $respuesta['xml'];
			$data['folio']			= $folio;
			$data['cadenaOriginal']	= $cadena;
			$data['selloDigital']	= $sello;
			$data['UUID']			= $respuesta['valores'][40];
			$data['fechaTimbrado']	= $respuesta['valores'][39];
			$data['selloSat']		= $respuesta['valores'][43];
			$data['certificado']	= $respuesta['valores'][41];
			
			$data['cadenaTimbre']	= '||1.1|'.$data['UUID'].'|'.$data['fechaTimbrado'].'|'.$respuesta['valores'][38].'|'.$data['selloDigital'].'|'.$data['certificado'].'||';
			
			$this->session->set_userdata('notificacion',"El cfdi se ha creado correctamente");
			
			$this->agregarFactura($data,$cotizacion,$cliente,$configuracion,$productos,$divisa);
			
			$this->borrarArchivosTemporales($carpetaFolio);
		}
	}

	public function agregarFactura($timbre,$cotizacion,$cliente,$configuracion,$productos,$divisa)
	{
		$subTotal	= 0;
		$total		= 0;
		$iva		= 0;
		$ivas		= 0;
		$ieps		= 0;
		$descuentos	= 0;
		
		foreach($productos as $row)
		{
			$cantidad			= $row->cantidad-$row->devueltos;
	
			if($cantidad>0)
			{
				$importe	= $cantidad*$row->precio;
				$importe	= round($importe,decimales);

				$descuento	= $importe*($row->descuentoPorcentaje/100);
				$descuento	= round($descuento,decimales);

				$diferencia	= $importe-$descuento;
				$diferencia	= round($diferencia,decimales);

				$impuesto	= $diferencia*($row->tasa/100);
				$impuesto	= round($impuesto,decimales);

				$subTotal	+=$importe;
				$ivas		+=$impuesto;
				$descuentos	+=$descuento;
			}
		}
		
		$total			= $subTotal-$descuentos+$ivas;
		$total			= round($total,decimales);
		
		$data=array
		(
			'rfc'					=> $cliente->rfc,
			'empresa'				=> $cliente->razonSocial,
			'calle'					=> $cliente->calle,
			'numeroExterior'		=> $cliente->numero,
			'colonia'				=> $cliente->codigoPostal,
			'localidad'				=> $cliente->localidad,
			'municipio'				=> $cliente->municipio,
			'estado'				=> $cliente->estado,
			'pais'					=> $cliente->pais,
			'codigoPostal'			=> $cliente->codigoPostal,
			'telefono'				=> $cliente->telefono,
			'email'					=> $cliente->email,
			'colonia'				=> $cliente->colonia,
			'idDireccion'			=> $cliente->idDireccion,
			
			'subTotal'				=> $subTotal,
			'iva'					=> $ivas,
			'descuento'				=> $descuentos,
			'total'					=> $total,
			'folio'					=> $timbre['folio'],
			'fecha'					=> $this->_fecha_actual,
			'xml'					=> $timbre['xml'],
			'cadenaOriginal'		=> $timbre['cadenaOriginal'],
			'selloSat'				=> $timbre['selloSat'],
			'selloDigital'			=> $timbre['selloDigital'],
			'UUID'					=> $timbre['UUID'],
			'certificadoSat'		=> $timbre['certificado'],
			'cadenaTimbre'			=> $timbre['cadenaTimbre'],	
			'fechaTimbrado'			=> $timbre['fechaTimbrado'],
			'idLicencia'			=> $this->idLicencia,
			'idCotizacion'			=> $cotizacion->idCotizacion,
			'idCliente'				=> $cliente->idCliente,
			'documento'				=> $this->input->post('documento'),
			'tipoComprobante'		=> $this->input->post('tipoComprobante'),
			'serie'					=> $configuracion->serie,
			'condicionesPago'		=> $this->input->post('txtCondiciones'),
			'parcial'				=> '0',
			'observaciones'			=> $this->input->post('txtObservaciones'),
			'divisa'				=> $divisa->nombre,
			'claveDivisa'			=> $divisa->clave,
			'tipoCambio'			=> $divisa->tipoCambio,
			'idEmisor'				=> $this->input->post('selectEmisores'),
			
			'metodoPago'			=> $this->input->post('metodoPagoTexto'),
			'formaPago'				=> trim($this->input->post('formaPagoTexto').' '.$this->input->post('txtCuentaPago')),
			'usoCfdi'				=> $this->input->post('usoCfdiTexto'),
			
			'version'				=> '1',
			
			'idPac'					=> $configuracion->idPac,

			'versionCfdi'			=> '4.0',
			'regimenFiscalCliente'	=> $cliente->claveRegimen.', '.$cliente->regimenFiscal,
			'certificadoEmisor'		=> $configuracion->numeroCertificado,
		);
		
		$this->db->insert('facturas',$data);
		$idFactura 			= $this->db->insert_id();
		$this->idFactura	= $idFactura;
		
		
		$sql=" unlock tables";
		$this->db->query($sql);
		
		#-------------------------------------------------------------------------------------#
		$data=array();
		$data['encriptacion']		= sha1("'".$idFactura.$timbre['fechaTimbrado']."'"); 
		
		$this->db->where('idFactura',$idFactura); 
		$this->db->update('facturas',$data);
		
		#GUARDAR EL DETALLE DE PRODUCTOS Y ASOCIAR LA FACTURA PARCIAL EN CASO DE SER NECESARIO
		#-------------------------------------------------------------------------------------#
		
		$data=array
		(
			'idCotizacion'		=> $cotizacion->idCotizacion,
			'idFactura'			=> $idFactura,
			'porcentaje'		=> 100,
		);
		
		$this->db->insert('rel_factura_cotizacion',$data);
		
		#GUARDAR EL DETALLE DE PRODUCTOS
		#-------------------------------------------------------------------------------------#
		
		#$productosParcial	= $_POST['productos'];
		$i					= 1;
		
		foreach($productos as $row)
		{
			$cantidad			= $row->cantidad-$row->devueltos;
	
			if($cantidad>0)
			{
				$importe		= $cantidad*$row->precio;
				$importe		= round($importe,decimales);

				$descuento		= $importe*($row->descuentoPorcentaje/100);
				$descuento		= round($descuento,decimales);

				$diferencia		= $importe-$descuento;
				$diferencia		= round($diferencia,decimales);

				$impuesto		= $diferencia*($row->tasa/100);
				$impuesto		= round($impuesto,decimales);

				$pedimento1		= trim($this->input->post('txtPedimento1'.$i));
				$pedimento2		= trim($this->input->post('txtPedimento2'.$i));
				$pedimento3		= trim($this->input->post('txtPedimento3'.$i));
				$pedimento4		= trim($this->input->post('txtPedimento4'.$i));
				$fecha			= trim($this->input->post('txtFecha'.$i));

				$pedimento		= $pedimento1.'  '.$pedimento2.'  '.$pedimento3.'  '.$pedimento4;

				$data=array
				(
					'idFactura'				=> $idFactura,
					'idProducto'			=> $row->idProducto,
					'nombre'				=> trim($this->input->post('txtDescripcionProductoFactura'.$i)),
					'precio'				=> $row->precio,
					'importe'				=> $importe,
					'cantidad'				=> $cantidad,
					'descuento'				=> $descuento,
					'descuentoPorcentaje'	=> $row->descuentoPorcentaje,
					'unidad'				=> $row->unidad,
					'claveUnidad'			=> $row->claveUnidad,
					'claveProducto'			=> $row->claveProducto,
					'claveDescripcion'		=> $row->claveDescripcion,
					'codigoInterno'			=> $row->codigoInterno,
					
					'claveObjetoImpuesto'	=> '02',
					'objetoImpuesto'		=> 'Si objeto de impuesto',
				);

				if(strlen($pedimento1)==2 and strlen($pedimento2)==2 and strlen($pedimento3)==4 and strlen($pedimento4)==7 and strlen($fecha)==10)
				{
					$data['fecha']		= $fecha;
					$data['pedimento']	= $pedimento;
				}

				$this->db->insert('facturas_detalles',$data);
				$idDetalle		= $this->db->insert_id();

				$data=array
				(
					'idDetalle'				=> $idDetalle,
					#'idImpuesto'			=> 0,
					'tasa'					=> $row->tasa,
					'importe'				=> $impuesto,
					'impuesto'				=> $row->claveImpuesto,
					'nombreImpuesto'		=> $row->nombreImpuesto,
					'base'					=> $importe-$descuento,
					'exento'				=> $row->exento,
				);

				$this->db->insert('facturas_detalles_impuestos',$data);

				$i++;
			}
			
		}
	
		
		$data=array
		(
			'idFactura'	=> $idFactura,
			'idCliente'	=> $cliente->idCliente
		);
		
		$this->db->where('idCotizacion',$cotizacion->idCotizacion); 
		$this->db->update('cotizaciones',$data);
		
		
		//REVISAR LA ÚLTIMA FACTURA SI ESTA PENDIENTE
		
		$idFacturaPendiente	= $this->input->post('txtIdUltimaFactura');
		$pendiente			= $this->input->post('txtPendiente');
		
		if($idFacturaPendiente>0 and $pendiente==1)
		{
			$this->borrarFacturaPendiente($idFacturaPendiente);
		}
	}
	
	public function borrarFacturaPendiente($idFactura)
	{
		$sql=" select idDetalle
		from facturas_detalles
		where idFactura='$idFactura' ";
		
		$detalles	= $this->db->query($sql)->result();
		
		foreach($detalles as $row)
		{
			$this->db->where('idDetalle',$row->idDetalle); 
			$this->db->delete('facturas_detalles_impuestos');
		}
		
		$this->db->where('idFactura',$idFactura); 
		$this->db->delete('facturas');
		
		$this->db->where('idFactura',$idFactura);  
		$this->db->delete('facturas_detalles');
	}
	
	
	public function timbrarFactor($ficheroXML,$folio,$carpetaFolio,$sello,$cadena,$cotizacion,$cliente,$configuracion,$productos,$divisa)
	{
		$this->load->library('factor');
		
		$timbrado 		= new Factor();
		$respuesta 		= $timbrado->obtenerTimbre($configuracion->usuarioPac, $configuracion->passwordPac, $ficheroXML,$configuracion->url);

		if(!$respuesta['estatus'])
		{
			if(strlen($respuesta['codigoError'])>0)
			{
				$this->registrarError($respuesta['codigoError'],$respuesta['comentarios'],$configuracion->idEmisor);	
			}
			
			$this->resultado	=$respuesta['mensaje'];

			return 0;
		}
		
		if($respuesta['estatus'])
		{
			$timbre		=$carpetaFolio.'cfdi'.$folio.'Timbre.xml'; #Es el archivo XML Timbrado
			$fichero	=fopen($timbre,"w");	
			fwrite($fichero,$respuesta['xml']);
			fclose($fichero);
			
			$data['xml']			=$respuesta['xml'];
			$data['folio']			=$folio;
			$data['cadenaTimbre']	=$respuesta['cadenaTimbre'];
			$data['cadenaOriginal']	=$cadena;
			$data['selloDigital']	=$sello;
			$data['UUID']			=$respuesta['uuid'];
			$data['fechaTimbrado']	=$respuesta['fechaTimbrado'];
			$data['selloSat']		=$respuesta['selloSat'];
			$data['certificado']	=$respuesta['certificado'];
			
			$this->session->set_userdata('notificacion',"El cfdi se ha creado correctamente");
			$this->agregarFactura($data,$cotizacion,$cliente,$configuracion,$productos,$divisa);
			
			$this->borrarArchivosTemporales($carpetaFolio);
			
		}
	}
	
	
	public function enviarFacturaCreada($idFactura,$destinatario)
	{
		error_reporting(0);
		
		$documentos		= $this->crearFacturaFisicaCorreo($idFactura);
		$configuracion	= $this->facturacion->obtenerConfiguracion();
		$factura		= $this->facturacion->obtenerFactura($idFactura);
		$usuario		= $this->configuracion->obtenerUsuario( $this->_iduser);

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
		
		$cuerpo			= $imagen.'<br />'.nl2br($usuario->firma).'<br /><strong>Por favor consulte los adjuntos</strong>';

		$this->email->subject('Factura '.$factura->serie.$factura->folio);
		$this->email->message
		(
			$cuerpo
		);
		
		if (!$this->email->send())
		{
			#echo $this->email->print_debugger();
			
			#$this->configuracion->registrarBitacora('Enviar CFDI','Facturación',$factura->serie.$factura->folio.', Email: '.$destinatario); //Registrar bitácora
			
			#$this->configuracion->registrarHistorialEnvios($destinatario,'2',$this->_iduser,$idFactura); //Registrar historial
		}
		else
		{
			$this->configuracion->registrarBitacora('Enviar CFDI','Facturación',$factura->serie.$factura->folio.', Email: '.$destinatario); //Registrar bitácora
			
			$this->configuracion->registrarHistorialEnvios($destinatario,'2',$this->_iduser,$idFactura); //Registrar historial

		}
	}
	
	
	public function crearFacturaFisicaCorreo($idFactura)
	{
		ini_set("memory_limit","1500M");
		set_time_limit(0);
		
		$this->load->library('ccantidadletras');
		$this->load->library('mpdf/mpdf');
		$this->load->helper('sat');
		$this->load->helper('qrlib');
		
		$data['factura'] 		= $this->facturacion->obtenerFactura($idFactura);
		$data['configuracion'] 	= $this->configuracion->obtenerEmisor($data['factura']->idEmisor);
		$data['relaciones']		= $this->facturacion->obtenerRelaciones($idFactura);
		
		$ruta					='media/fel/'.$data['configuracion']->rfc.'/folio'.$data['factura']->serie.$data['factura']->folio.'/';
		
		
		if(!file_exists($ruta))
		{
			crearDirectorio($ruta);
		}
		
		generarCodigoBidimensional($data['factura'],$data['configuracion']);
		
		$this->ccantidadletras->setIdioma("ES");
        $this->ccantidadletras->setNumero($data['factura']->total);
		$this->ccantidadletras->setMoneda($data['factura']->divisa);//
		$data['cantidadLetra']	=$this->ccantidadletras->PrimeraMayuscula();
		
		if($data['factura']->documento=='Recibo de Nómina')
		{
			$data['percepciones'] 	=$this->reportes->obtenerPercepciones($idFactura);
			$data['deducciones'] 	=$this->reportes->obtenerDeducciones($idFactura);
			$data['empleado'] 		=$this->reportes->obtenerEmpleado($idFactura);
			$data['emisor']			=$this->configuracion->obtenerEmisor($data['factura']->idEmisor);
			$data['reporte']		='nomina/reciboNomina';
			
			$html	=$this->load->view('nomina/principal',$data,true);
			$pie 	= $this->load->view('nomina/pieNomina',$data,true);
			
			$this->mpdf->mPDF('en-x','Letter','','',10,10,10,10,2,1);
			$this->mpdf->SetHTMLFooter($pie);
			$this->mpdf->SetHTMLFooter($pie,'E');
			
			$this->mpdf->mirrorMargins = 1;
			$this->mpdf->WriteHTML($html);
		}
		
		else
		{
			$data['cliente'] 		= $this->clientes->obtenerDatosCliente($data['factura']->idCliente);
			$data['retencion'] 		= $this->facturacion->obtenerRetencion($idFactura);
			$data['productos'] 		= $this->facturacion->obtenerProductosFacturados($idFactura);
			$data['reporte']		= 'facturacion/factura';
			
			if($data['factura']->pago=='1')
			{
				$data['pago'] 		= $this->pagos->obtenerPago($idFactura);
				$data['relacion'] 	= $this->pagos->obtenerFacturaRelacion($idFactura);
				$data['reporte']	= 'facturacion/pagos/pago';	
			}
	
			$html	=$this->load->view('facturacion/principal',$data,true);
			#$pie 	= $this->load->view('facturacion/pie',$data,true);
			
			if($data['factura']->pago=='0')
			{
				$pie 					= $this->load->view('facturacion/pie',$data,true);
			}
			else
			{
				$pie 					= $this->load->view('facturacion/pagos/pie',$data,true);
			}
	
			$this->mpdf->mPDF('en-x','Letter','','',10,10,10,70,6,0);
			$this->mpdf->SetHTMLFooter($pie);
			$this->mpdf->SetHTMLFooter($pie,'E');
			$this->mpdf->mirrorMargins = 1;
			$this->mpdf->WriteHTML($html);
		}

		$pdf				=$ruta.$data['configuracion']->rfc.'_'.$data['factura']->serie.$data['factura']->folio.'.pdf';
		$xml				=$ruta.$data['configuracion']->rfc.'_'.$data['factura']->serie.$data['factura']->folio.'.xml';

		$documentos['pdf']				=$pdf;
		$documentos['xml']				=$xml;
		$documentos['configuracion']	=$data['configuracion'];
		
		$this->mpdf->Output($pdf,'F');
		
		#CREAR EL XML FISICO
		$fichero	=fopen($xml,"w");	
		fwrite($fichero,$data['factura']->xml);
		fclose($fichero);

		return $documentos;
	}
	
	public function borrarArchivosTemporales($carpeta)
	{
		try
		{
			unlink($carpeta.'cadena.txt');
			unlink($carpeta.'certificado.txt');
			unlink($carpeta.'certificadoImprimir.txt');
			unlink($carpeta.'sello.txt');
		}
		catch(Exception $ex)
		{
			return "0";
		}
	}
	
	public function registrarError($codigoError,$comentarios,$idEmisor=0)
	{
		$data=array
		(
			'idUsuario'		=> $this->_iduser,
			'fecha'			=> $this->_fecha_actual,
			'codigoError'	=> $codigoError,
			'comentarios'	=> $comentarios,
			'idEmisor'		=> $idEmisor,
		);
		
		$this->db->insert('facturas_errores',$data);
	}
	
	

	public function obtenerDivisa($idDivisa)
	{
		$sql="select * from divisas
		where idDivisa='$idDivisa' ";
		
		return $this->db->query($sql)->row();
	}
	
	
	public function comprobarParcial($idFactura)
	{
		$sql="select parcial
		from facturas
		where idFactura='$idFactura' ";
		
		
		return $this->db->query($sql)->row()->parcial;
	}
	
	public function cancelarCFDI()
	{
		$estatus			= false;
		$mensaje			= "";
		
		$configuracion		= $this->obtenerConfiguracion();
		$idFactura			= $this->input->post('txtIdFacturaCancelar');
		$factura			= $this->obtenerFacturaCancelar($idFactura);
		$emisor				= $this->obtenerEmisor($factura->idEmisor);
		$pac				= $this->configuracion->obtenerPac($factura->idPac);
		$carpetaFel			= carpetaCfdi.$emisor->rfc.'/';
		
		$data = array
		(
			'UUIDCancelado' 		=>$factura->UUID,
			#'xmlAcuse' 				=>"",
			'motivosCancelacion' 	=>$this->input->post('motivosCancelacion'),
			'cancelada' 			=>1,
			'fechaCancelacion'		=>$this->_fecha_actual,
		);

		if($pac->pac=='4gFactor')
		{
			$this->load->library('factor');
			$timbrado 			= new Factor();
			#$respuesta 			= $timbrado->cancelarCfdi($configuracion->usuarioFactor, $configuracion->passwordFactor, $factura->UUID);

			$respuesta 			= $timbrado->cancelarCfdi($pac->usuario, $pac->password, $factura->UUID,$carpetaFel.$emisor->certificado,$carpetaFel.$emisor->llave,$emisor->passwordLlave,$pac->url);
			$estatus			= $respuesta['estatus'];
			$mensaje			= $respuesta['mensaje'];
			#var_dump($configuracion);
		}
		
		if($pac->pac=='finkok')
		{
			$this->load->library('finkok');

			$certificado64			= encriptarCertificado($carpetaFel.$emisor->certificado,carpetaCfdi);
			$llave64				= encriptarLlave($carpetaFel.$emisor->llave,carpetaCfdi,$emisor->passwordLlave,$pac->password);

			if(strlen($certificado64)>4 and strlen($llave64)>0)
			{
				$timbrado 				= new Finkok();
				$respuesta 				= $timbrado->cancelarCfdi($pac->usuario, $pac->password, $factura->UUID,$certificado64,$llave64,$pac->urlCancelacion,$emisor->rfc,$this->input->post('selectMotivoCancelacion'),$this->input->post('txtUuidSustitucion'));
				$estatus				= $respuesta['estatus'];
				$mensaje				= $respuesta['mensaje'];
				$acuse					= $respuesta['acuse'];

				if($respuesta['codigoError']!='798')
				{
					if($estatus)
					{
						if(strlen($acuse)>1)
						{
							$data['xmlAcuse']	= $acuse;
						}
					}
					else
					{
						return array('0',$mensaje);
					}
				}
				else
				{
					$estatus	= true;
				}
			}
			else
			{
				return array('0','No es posible obtener el certificado del emisor');
			}
		}
		
		
		if($estatus)
		{
			$this->db->where('idFactura',$idFactura);
			$this->db->update('facturas',$data);
			
			//SUSTITUCIÓN DE CFDI
			$data = array
			(
				'idFactura' 			=> $idFactura,
				'idFacturaSustitucion' 	=> $this->input->post('txtIdFacturaSustitucion'),
				'motivosCancelacionSat' => $this->input->post('motivosCancelacionSat'),
			);

			$this->db->insert('facturas_sustituciones',$data);

			$this->configuracion->registrarBitacora('Cancelar CFDI','Reportes - Facturación',$factura->serie.$factura->folio); 
			
			return array('1','El cfdi se ha cancelado correctamento');
		}
		else
		{
			return array('0',$mensaje);
		}
	}
	
/*	public function cancelarCFDI()
	{
		$this->load->library('timbrado');
		$this->load->library('seguridad');
		
		$configuracion		=$this->obtenerConfiguracion();
		$idFactura			=$this->input->post('idFactura');
		$factura			=$this->obtenerFacturaCancelar($idFactura);

		$integrador 		= "2b3a8764-d586-4543-9b7e-82834443f219";
		#$integrador 		= "d8ff5d28-9fc3-4097-b683-966a1a634f75";
		#$rfc = "SUL010720JN8";
		$rfc 				= $configuracion->rfc;
		
		$token 				= new Seguridad();
		$transaccion 		= rand(1, 10000);
		$generaToken 		= $token->setToken($rfc, $transaccion, $integrador);
		$getToken 			= $token->getToken();

		$cancelacion 		= new Timbrado();
		$cancelar 			= $cancelacion->setCancela($rfc, $getToken, $transaccion, $factura->UUID);                               
		$resultadoCancelar 	= $cancelacion->resultadoCancelar();
		
		#echo $resultadoCancelar;
			
		$data = array
		(
			'UUIDCancelado' 		=>$factura->UUID,
			'xmlAcuse' 				=>"",
			'motivosCancelacion' 	=>$this->input->post('motivos'),
			'cancelada' 			=>1,
			'fechaCancelacion'		=>$this->_fecha_actual,
		);
		
		if($cancelar>0)
		{
			$this->db->where('idFactura',$idFactura);
			$this->db->update('facturas',$data);
			
			return "1";
		}
		else
		{
			return $resultadoCancelar;
		}
	}*/

	public function obj2array($obj) 
	{
		$out = array();
		
		foreach ($obj as $key => $val) 
		{
			switch(true) 
			{
				case is_object($val):
				$out[$key] = $this->obj2array($val);
				break;
				
				case is_array($val):
				$out[$key] = $this->obj2array($val);
				break;
				
				default:
				$out[$key] = $val;
			}
		}
		
		return $out;
	}
	
	

	
	public function obtenerXML($idFactura)
	{
		$sql="select name_xml, folio
			  from facturas_ventas 
			  where idf='".$idFactura."'";
		
		$query=$this->db->query($sql);
		
		return($query->row());
	}
	
	public function cancelarFactura()
	{
		$this->db->trans_start(); #Se Inicia una transaccion porque se actualiza mas de una tabla
		
		$idFactura=$this->input->post('idFactura');
		
		$data = array
		(
			'motivosCancelacion' => $this->input->post('motivos'),
			'cancelada'			 => "1"
		);
		
		$this->db->where('idf',$idFactura);
		$this->db->update('facturas_ventas',$data);
		
		$sql="	select a.idProducto, b.facturado
				from factura_productos_detalles as a
				inner join cotiza_productos as b
				on(a.idProducto=b.id)
				where a.idFactura='".$idFactura."'";
				
		$query= $this->db->query($sql);
		$query= $query->result();
		
		foreach($query as $row)
		{
			$data = array
			(
				'facturado' => "0"
			);
			
			$this->db->where('id',$row->idProducto);
			$this->db->update('cotiza_productos',$data);
		}
		
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback(); #Revertir si hubo algun fallo en las actualizaciones
			$this->db->trans_complete();
			
			return "0";
		}
		else
		{
			$this->db->trans_commit(); #Guargar los cambios si todo ha sido correcto
			$this->db->trans_complete();
			
			return "1";
		}
	}
	
	public function obtenerRetencion($idFactura)
	{
		$sql="select * from facturas_retenciones
		where idFactura='$idFactura'";
		
		return $this->db->query($sql)->row();
	}
	
	public function obtenerRetenciones($idFactura)
	{
		$sql="select * from facturas_retenciones
		where idFactura='$idFactura'";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerFactura($idFactura)
	{
		$sql=" select a.*, b.anio, b.mes, b.periodicidad
		from facturas as a
		left join facturas_global as b
		on a.idFactura=b.idFactura
		where a.idFactura='".$idFactura."'";
		
		return $this->db->query($sql)->row();
	}
	
	public function obtenerFacturasParciales($idCotizacion)
	{
		$sql=" select a.* 
		from facturas as a
		inner join rel_factura_cotizacion as b
		on a.idFactura=b.idFactura
		where b.idCotizacion='$idCotizacion' ";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerTrasladosParciales($idCotizacion)
	{
		$sql=" select a.* 
		from facturas as a
		inner join rel_factura_cotizacion as b
		on a.idFactura=b.idFactura
		where b.idCotizacion='$idCotizacion'
		and a.documento='TRASLADO' ";
		
		return $this->db->query($sql)->result();
	}
	
	public function sumarRetenciones($idCotizacion)
	{
		$sql="select coalesce(sum(importe),0) as importe
		from facturas_retenciones
		where idCotizacion=$idCotizacion";	
		
		return $this->db->query($sql)->row()->importe;
	}
	
	public function sumarFacturasParciales($idCotizacion)
	{
		$sql=" select sum(a.total) as total
		from facturas as a
		inner join rel_factura_cotizacion as b
		on a.idFactura=b.idFactura
		where b.idCotizacion='$idCotizacion'
		and cancelada=0 ";
		
		$retenciones=$this->sumarRetenciones($idCotizacion);
		
		return $this->db->query($sql)->row()->total+$retenciones;
	}
	
	public function obtenerProductosFactura($idCotizacion)
	{
		$sql=" select a.idProducto, a.cantidad, a.precio, 
		a.importe, b.nombre,
		(select c.descripcion from unidades as c where c.idUnidad=b.idUnidad) as unidad
		from cotiza_productos as a
		inner join productos as b
		on(a.idProduct=b.idProducto)
		where a.idCotizacion='".$idCotizacion."'";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerProductosFacturados($idFactura,$pago=0)
	{
		$sql=" select a.*, b.tasa, b.tipoFactor, b.impuesto,
		b.base, b.nombreImpuesto, b.importe as importeImpuesto,
		b.exento
		from facturas_detalles as a
		inner join facturas_detalles_impuestos as b
		on a.idDetalle=b.idDetalle
		where a.idFactura='$idFactura' ";
		
		if($pago==1)
		{
			$sql=" select a.*
			from facturas_detalles as a
			where a.idFactura='$idFactura' ";
		}
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerProductoCotizado($idProducto)
	{
		$sql="select a.idProduct, c.stock, 
		c.idProducto, a.devueltos 
		from cotiza_productos as a
		inner join rel_producto_produccion as b
		on a.idProduct=b.idProducto
		inner join produccion_productos as c
		on b.idProductoProduccion=c.idProducto
		where a.idProducto='$idProducto'";
		
		return $this->db->query($sql)->row();
	}
	
	public function devolverProductosVenta()
	{
		$this->db->trans_start(); #Se Inicia una transaccion porque se actualiza mas de una tabla
		
		$idProducto=$this->input->post('idProducto');
		$inventario=$this->input->post('inventario');
		
		$data=array
		(
			'devueltos'	=>$this->input->post('cantidad')
		);
		
		$this->db->where('idProducto',$idProducto);
		$this->db->update('cotiza_productos',$data);
		
		if($inventario=="1")
		{
			$producto=$this->obtenerProductoCotizado($idProducto);
			
			$data=array
			(
				'stock'	=>$producto->stock+$this->input->post('cantidad')
			);
			
			$this->db->where('idProducto',$producto->idProducto);
			$this->db->update('produccion_productos',$data);
		}
		
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback(); #Revertir si hubo algun fallo en las actualizaciones
			$this->db->trans_complete();
			
			return "0";
		}
		else
		{
			$this->db->trans_commit(); #Guargar los cambios si todo ha sido correcto
			$this->db->trans_complete();
			
			return "1";
		}
	}
	
	public function reposicionesVenta() #Reposición de productos en las ventas
	{
		$this->db->trans_start(); #Se Inicia una transaccion porque se actualiza mas de una tabla
		
		$idProducto=$this->input->post('idProducto');
		
		$data=array
		(
			'repuesto'	=>1
		);
		
		$this->db->where('idProducto',$idProducto);
		$this->db->update('cotiza_productos',$data);
		
		$producto=$this->obtenerProductoCotizado($idProducto);
		
		$data=array
		(
			'stock'	=>$producto->stock-$producto->devueltos
		);
		
		$this->db->where('idProducto',$producto->idProducto);
		$this->db->update('produccion_productos',$data);
		
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback(); #Revertir si hubo algun fallo en las actualizaciones
			$this->db->trans_complete();
			
			return "0";
		}
		else
		{
			$this->db->trans_commit(); #Guargar los cambios si todo ha sido correcto
			$this->db->trans_complete();
			
			return "1";
		}
	}
	
	#===================================================================================//
	#===============================CANCELACIÓN DE FACTURA==============================//
	#===================================================================================//
	
	public function obtenerFacturaCancelar($idFactura)
	{
		$sql="select folio, serie, total, idFactura, UUID, idEmisor, cancelada,
		cancelada, pago, idPac,
		concat(serie,folio) as cfdi
		from facturas 
		where idFactura='$idFactura'";
		
		$query=$this->db->query($sql);
		
		return $query->row();
	}
	
	
	
	public function cancelarCFDIs()
	{
		$this->load->helper('sat');
		
		$conexion=new SoapClient('https://www.fel.mx/WS-TFD/WS-TFD.asmx?WSDL');
		
		$configuracion=$this->obtenerConfiguracion();
		$idFactura=$this->input->post('idFactura');
		$factura=$this->obtenerFacturaCancelar($idFactura);
		
		$carpetaFel='media/fel/';
		$carpetaUsuario=$carpetaFel.$this->idLicencia.'_facturacion/';
		$carpetaFolio=$carpetaUsuario.'cfdi/folio'.$factura->folio.'/';
		
		#$ruta='ficheros/factura/'.$carpeta."/";
		
		$folio64=$carpetaFolio.'cancelarFolio'.'64.txt'; //Envio al web services para poder cancelar la factura
		
		$certificado=$carpetaFolio.'certificadoCancelar.txt';
		#$llave=$carpetaFolio.'llaveCancelar'.'.txt';
		$llave=$carpetaFolio.'certificado.txt';
		
		$archivoPFX=$carpetaFolio.'archivoPFX'.'.pfx';
		
		$fichero='folio'.$factura->folio; #Es el nombre del archivo con distintas extensiones
		
		exec('openssl x509 -inform DER -in '.$carpetaUsuario.$configuracion->certificado.' -out '.$certificado);
		
		exec('openssl pkcs12 -export -out '.$archivoPFX.' -inkey '.$llave.' -in '.$certificado.' -passout pass:'.$configuracion->passwordLlave.'');
		
		exec("openssl enc -base64 -in ".$archivoPFX." -out ".$folio64);
		
		$certificado64=openFile($folio64,"READ","");
		
		$listaCFDI=array();
		$listaCFDI[0]=$factura->UUID;
		
		$cancelacion=array #Son datos reales de CIEUD
		(
			'usuario'					=>$configuracion->usuarioFEL,
			'password'					=>$configuracion->passwordFEL,
			'RFCEmisor'					=>$configuracion->rfc,
			'listaCFDI'					=>$listaCFDI,
			'certificadoPKCS12_Base64'	=>$certificado64,
			'passwordPKCS12'			=>$configuracion->passwordLlave,
		);
		
		$respuesta=array();
		$respuesta=$conexion->CancelarCFDI($cancelacion);
		
		$datosFactura = $this->obj2array($respuesta);//Convertir el resultado a un valor asociativo
		$arregloXml=$datosFactura['CancelarCFDIResult'];
		
		#var_dump($arregloXml);
		#var_dump($cancelacion);
		#return;
		
		$xml= array($arregloXml["string"]);
		
		$archivo=$carpetaFolio.'acuse.xml';
		$archivoXml=fopen($archivo,"w");	
		$datos=$xml[0][2];
		
		fwrite($archivoXml,$datos);
		fclose($archivoXml);
		 
		$data = array
		(
			'UUIDCancelado' 		=>$xml[0][1],
			'xmlAcuse' 				=>$xml[0][2],
			'motivosCancelacion' 	=>$this->input->post('motivosCancelacion'),
			'cancelada' 			=>1,
		);
		
		if(strlen($xml[0][1])>4)
		{
			$this->db->where('idFactura',$idFactura);
			$this->db->update('facturas',$data);
			
			return "1";
		}
		else
		{
			return "0";
		}
	}
	
	
	//PARA LAS VISTAS PREVIAS
	
	#------------------------------------------------------------------------------------------------------#
	public function vistaPrevia()
	{
		$this->db->trans_start(); #Se Inicia una transaccion porque se inserta y actualiza
		$this->load->helper('sat');
		
		$idCotizacion			= $this->input->post('idCotizacion');
		$idEmisor				= $this->input->post('idEmisor');
		$configuracion			= $this->obtenerEmisor($idEmisor);
		$cotizacion				= $this->obtenerCotizacion($idCotizacion);
		$cliente				= $this->obtenerCliente($cotizacion->idCliente);
		$comprobante			= $this->input->post('tipoComprobante');
		
		$retenciones['importe']	= $this->input->post('retencion');
		$retenciones['tasa']	= $this->input->post('tasa');
		$retenciones['nombre']	= $this->input->post('nombre');
		
		$divisa					= $this->obtenerDivisa($this->input->post('idDivisa'));

		$productos				= $this->obtenerProductosCotizacion($cotizacion->idCotizacion);
	
		if(strlen($cliente->rfc)<12 or strlen($cliente->razonSocial) <3 or strlen($cliente->pais) <3 )
		{
			$this->db->trans_rollback(); #Revertir si hubo algun fallo en las actualizaciones
			$this->db->trans_complete();
			
			return "2";
		}
		
		
		$folio	=$this->obtenerFolio($idEmisor);
		
		if($folio<1)
		{
			$this->db->trans_rollback(); #Revertir si hubo algun fallo en las actualizaciones
			$this->db->trans_complete();
			
			return "4"; #Los folios se han terminado
		}
		
		$carpetaFel			= 'media/fel/';
		$carpetaUsuario		= 'media/fel/'.$configuracion->rfc.'/';
		$carpetaFolio		= $carpetaUsuario.'vistaPrevia/';
		$cfd				= $carpetaFolio.'cfd'.$folio.'.xml';
		
		crearDirectorio($carpetaFolio);
		
		$sello				= "";
		$certificado		= "";

		$ficheroXML			= RegresaXMLFormato($configuracion,$cliente,$productos,$sello,$certificado,$this->_fecha_actual,$folio,$cotizacion,$retenciones,$divisa);
		
		guardarArchivoXML($cfd,$ficheroXML);
		
		exec("xsltproc ".$carpetaFel.'cadenaoriginal_3_2.xslt'." ".$cfd." > ".$carpetaFolio.'cadena.txt'); #Comentado mejor quitarlo jaja

		exec("openssl pkcs8 -inform DER -in ".$carpetaUsuario.$configuracion->llave." -passin pass:".$configuracion->passwordLlave." -out ".$carpetaFolio.'certificado.txt');
		
		exec("openssl dgst -sha1 -sign ".$carpetaFolio."certificado.txt ".$carpetaFolio."cadena.txt | openssl enc -base64 -A > ".$carpetaFolio.'sello.txt');
		
		exec("openssl enc -base64 -in ".$carpetaUsuario.$configuracion->certificado." -out ".$carpetaFolio.'certificadoImprimir.txt');
		
		$certificado	= leerFichero($carpetaFolio.'certificadoImprimir.txt',"READ","");
		$certificado 	= QuitarEspaciosXML($certificado,"B");
		$sello			= leerFichero($carpetaFolio.'sello.txt',"READ","");
		$sello 			= QuitarEspaciosXML($sello,"B");
		$cadena			= leerFichero($carpetaFolio.'cadena.txt',"READ","");

		$ficheroXML=RegresaXMLFormato($configuracion,$cliente,$productos,$sello,$certificado,$this->_fecha_actual,$folio,$cotizacion,$retenciones,$divisa); 
		
		if(guardarArchivoXML($cfd,$ficheroXML))
		{
			$this->agregarFacturaVista($ficheroXML,$folio,$sello,$cadena,$cotizacion,$cliente,$configuracion);
		}
		
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
			
			return "1";
		}
	}
 	
	public function agregarFacturaVista($xml,$folio,$sello,$cadena,$cotizacion,$cliente,$configuracion)
	{
		//VACIAR LAS VISTAS PREVIAS
		
		$sql=" delete from facturas_detalles_vista where idFactura>0 ";
		$this->db->query($sql);
		
		$sql=" delete from facturas_retenciones_vista where idFactura>0 ";
		$this->db->query($sql);
		
		$sql=" delete from facturas_vista where idFactura>0 ";
		$this->db->query($sql);
		
		$subTotal	= $cotizacion->subTotal;
		$iva		= $cotizacion->iva;
		$descuento	= $cotizacion->descuento;
		$total		= $cotizacion->total;

		$porcentajeParcial	=0;
		
		if($this->input->post('parcial')=="1")
		{
			$subTotal			= $_POST['subTotal'];
			$descuento			= $_POST['descuento'];
			$iva				= $_POST['iva'];
			$total				= $_POST['total'];
			
			$porcentajeParcial	= $_POST['porcentaje'];
			
			$productos	=$this->obtenerProductosCotizacion($cotizacion->idCotizacion); 
		}
		else
		{
			$productos	=$this->obtenerProductosCotizacion($cotizacion->idCotizacion); 
		}
		
		$retenciones['importe']	=$this->input->post('retencion');
		$retenciones['tasa']	=$this->input->post('tasa');
		$retenciones['nombre']	=$this->input->post('nombre');
		
		if($retenciones['importe']>0)
		{
			$total=$total-$retenciones['importe'];
		}
		
		$divisa		=$this->obtenerDivisa($this->input->post('idDivisa'));
		
		
		//SE AGREGARON LAS RETENCIONES
		
		$subTotal			= $_POST['subTotal'];
		$descuento			= $_POST['descuento'];
		$iva				= $_POST['iva'];
		$total				= $_POST['total'];
		
		$tasaIeps			= isset($_POST['tasaIeps'])?$_POST['tasaIeps']:0;
		$totalIeps			= isset($_POST['totalIeps'])?$_POST['totalIeps']:0;
		
		$retencionIva		= isset($_POST['retencionIva'])?$_POST['retencionIva']:0;
		$tasaRetencionIva	= isset($_POST['tasaRetencionIva'])?$_POST['tasaRetencionIva']:0;
		
		$retencionIeps		= isset($_POST['retencionIeps'])?$_POST['retencionIeps']:0;
		$tasaRetencionIeps	= isset($_POST['tasaRetencionIeps'])?$_POST['tasaRetencionIeps']:0;
		
		$data=array
		(
			'rfc'				=> $cliente->rfc,
			'pais'				=> $cliente->pais,
			'direccion'			=> $cliente->calle,
			'estado'			=> $cliente->estado,
			'ciudad'			=> $cliente->localidad,
			'numero'			=> $cliente->numero,
			'codigoPostal'		=> $cliente->codigoPostal,
			'empresa'			=> $cliente->razonSocial,
			'telefono'			=> $cliente->telefono,
			'email'				=> $cliente->email,
			'colonia'			=> $cliente->colonia,
			'subTotal'			=> $subTotal,
			'iva'				=> $iva,
			'descuento'				=> $descuento,
			'descuentoPorcentaje'	=> $cotizacion->descuentoPorcentaje,
			'ivaPorcentaje'			=> $cotizacion->ivaPorcentaje,
			'total'				=> $total,
			
			'ieps'				=> $totalIeps,
			'tasaIeps'			=> $tasaIeps,
			
			
			'folio'				=> $folio,
			'fecha'				=> $this->_fecha_actual,
			'xml'				=> $xml,
			'cadenaOriginal'	=> $cadena,
			'selloSat'			=> '',
			'selloDigital'		=> $sello,
			'UUID'				=> '',
			'certificadoSat'	=> '',
			'cadenaTimbre'		=> '',	
			'fechaTimbrado'		=> '',
			'idLicencia'		=> $this->idLicencia,
			'idCotizacion'		=> $cotizacion->idCotizacion,
			'idCliente'			=> $cotizacion->idCliente,
			'documento'			=> $this->input->post('documento'),
			'tipoComprobante'	=> $this->input->post('tipoComprobante'),
			'serie'				=> $configuracion->serie,
			'condicionesPago'	=> $this->input->post('condiciones'),
			'metodoPago'		=> $this->input->post('metodoPagoTexto'),
			'formaPago'			=> $this->input->post('formaPago'),
			'observaciones'		=> $this->input->post('observaciones'),
			
			'divisa'			=> $divisa->nombre,
			'claveDivisa'		=> $divisa->clave,
			'tipoCambio'		=> $divisa->tipoCambio,
			
			'idEmisor'			=> $this->input->post('idEmisor'),
		);
		
		$this->db->insert('facturas_vista',$data);
		$idFactura = $this->db->insert_id();
		
		#-------------------------------------------------------------------------------------#
		$data=array();
		$data['encriptacion']	=sha1("'".$idFactura.$this->_fecha_actual."'"); 
		
		$this->db->where('idFactura',$idFactura); 
		$this->db->update('facturas',$data);
		
		#GUARDAR EL DETALLE DE PRODUCTOS
		#-------------------------------------------------------------------------------------#
		if($_POST['parcial']==1)
		{
			$productosParcial	= $_POST['productos'];
			$porcentajeParcial	= $porcentajeParcial/100;
			$cantidadParcial	= $_POST['cantidad'];
			$descuentos			= $_POST['descuentos'];
			$i=1;

			foreach($productos as $row)
			{
				
				#$cantidad			=$row->cantidad*$porcentajeParcial;
				$cantidad			= $cantidadParcial[$i];
				#$importe			= $cantidad*$row->precio;
				$importe			= $cantidad*$row->precio - $descuentos[$i];
				
				$data=array
				(
					'idFactura'				=> $idFactura,
					'idProducto'			=> $row->idProducto,
					'nombre'				=> $productosParcial[$i],
					'unidad'				=> $row->unidad,
					'precio'				=> $row->precio,
					'importe'				=> $importe,
					'cantidad'				=> $cantidad,
					'codigoInterno'			=> $row->codigoInterno,
					'descuento'				=> $descuentos[$i],
					'descuentoPorcentaje'	=> $row->descuentoPorcentaje,
				);
				
				$this->db->insert('facturas_detalles_vista',$data);
				
				$i++;
			}
		}
		else
		{
			$productosParcial	= $_POST['productos'];
			$i=1;
			
			foreach($productos as $row)
			{
				$data=array
				(
					'idFactura'				=> $idFactura,
					'idProducto'			=> $row->idProducto,
					#'nombre'				=> $row->nombre,
					'nombre'				=> $productosParcial[$i],
					'unidad'				=> $row->unidad,
					'precio'				=> $row->precio,
					'importe'				=> $row->importe,
					'cantidad'				=> $row->cantidad,
					'codigoInterno'			=> $row->codigoInterno,
					
					'descuento'				=> $row->descuento,
					'descuentoPorcentaje'	=> $row->descuentoPorcentaje,
				);
				
				$this->db->insert('facturas_detalles_vista',$data);
				
				$i++;
			}
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
		
		if($retencionIeps>0)
		{
			$data=array
			(
				'idFactura'		=> $idFactura,
				'retencion'		=> 'RET IEPS',
				'tasa'			=> $tasaRetencionIeps,
				'importe'		=> $retencionIeps,
				'idCotizacion'	=> $cotizacion->idCotizacion,
			);
			
			$this->db->insert('facturas_retenciones_vista',$data);
		}
		
		if($retencionIva>0)
		{
			$data=array
			(
				'idFactura'		=> $idFactura,
				'retencion'		=> 'RET IVA',
				'tasa'			=> $tasaRetencionIva,
				'importe'		=> $retencionIva,
				'idCotizacion'	=> $cotizacion->idCotizacion,
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
	
	public function obtenerRetencionVista($idFactura)
	{
		$sql="select * from facturas_retenciones_vista
		where idFactura='$idFactura'";
		
		return $this->db->query($sql)->row();
	}
	
	public function obtenerProductosFacturadosVista($idFactura)
	{
		$sql=" select * from facturas_detalles_vista
		where idFactura='$idFactura' ";
		
		$query = $this->db->query($sql);
		
		return ( $query->num_rows() > 0) ? $query->result() : NULL;
	}
	
	public function timbrarEdiFact($ficheroXML,$folio,$carpetaFolio,$sello,$cadena,$cotizacion,$cliente,$configuracion)
	{
		$this->load->library('edifact');
		$timbrado 		= new Edifact();
		
		$respuesta 	= $timbrado->obtenerTimbre($configuracion->rfc, $configuracion->rfc, $ficheroXML);
		
		if($respuesta[0]==1000)
		{
			$this->resultado='Error al procesar el comprobante, por favor verifique su conexión';
			return 0;
		}
		
		if($respuesta[0]!=100)
		{
			$this->resultado='Error al procesar el comprobante, el mensaje del servidor es el siguiente: '.$respuesta[1];
			return 0;
		}
		
		if($respuesta[0]==100)
		{
			$timbre		=$ruta.'cfdi'.$folio.'Timbre.xml'; #Es el archivo XML Timbrado
			$fichero	=fopen($timbre,"w");	
			fwrite($fichero,$respuesta['xml']);
			fclose($fichero);
			
			#=================================================================================#
			$UUID			="";
			$fechaTimbrado	="";
			$sello			="";
			$certificado	="";
			$cadenaTimbre	="";
			$rutaFicheros	='media/fel/';
			$cadenaOriginal	=$ruta.'cadenaTimbre'.$folio.'.txt';
			$selloSat		=$ruta.'selloSat'.$folio.'.txt';
			
			$timbre			=$ruta.'cfdi'.$folio.'Timbre.xml'; #Es el archivo XML Timbrado

			exec("xsltproc ".$rutaFicheros.'convertir.xslt'." ".$timbre." > ".$cadenaOriginal);
			$cadenaTimbre	=openFile($cadenaOriginal,"READ","");
			
			$selloSat		=$ruta.'selloSat'.$folio.'.txt';
			
			exec("xsltproc ".$rutaFicheros.'selloSat.xslt'." ".$timbre." > ".$selloSat);
			$datosSello		=openFile($selloSat,"READ","");
			$tamano			=strlen($datosSello);
	
			$b=0;
			
			for($i=0;$i<$tamano;$i++)
			{
				if($b==1)
				{
					if($datosSello[$i]!="*")
					{
						$UUID.=$datosSello[$i];
					}
				}
				
				if($b==2)
				{
					if($datosSello[$i]!="*")
					{
						$fechaTimbrado.=$datosSello[$i];
					}
				}
				
				if($b==3)
				{
					if($datosSello[$i]!="*")
					{
						$sello.=$datosSello[$i];
					}
				}
				
				if($b==4)
				{
					if($datosSello[$i]!="*")
					{
						$certificado.=$datosSello[$i];
					}
				}
				
				if($datosSello[$i]=="*")
				{
					$b++;
				}
			}
			
			$data['xml']			=$cfdi;
			$data['folio']			=$folio;
			$data['cadenaTimbre']	=$cadenaTimbre;
			$data['cadenaOriginal']	=$cadena;
			$data['selloDigital']	=$selloDigital;
			$data['UUID']			=$UUID;
			$data['fechaTimbrado']	=$fechaTimbrado;
			$data['selloSat']		=$sello;
			$data['certificado']	=$certificado;
			
			if(strlen($UUID)>3)
			{
				$this->session->set_userdata('notificacion',"El cfdi se ha creado correctamente");
				$this->agregarFactura($data,$cotizacion,$cliente);
			}
			else
			{
				$this->db->trans_rollback(); #Revertir si hubo algun fallo en las actualizaciones
				$this->db->trans_complete();
				
				$this->resultado=$cfdi;
			}
		}
	}

	public function timbrarXML($archivoXML,$folio,$ruta,$selloDigital,$cadena,$cotizacion,$cliente,$configuracion)
	{
		#$ComprobanteXML = $comprobante;
		$this->load->library('timbrado');
		$this->load->library('seguridad');
		
		$integrador 		= "2b3a8764-d586-4543-9b7e-82834443f219";
		#$integrador 		= "d8ff5d28-9fc3-4097-b683-966a1a634f75";
		#$rfc = "SUL010720JN8";
		$rfc 			= $configuracion->rfc;
		
		$token 			= new Seguridad();
		$transaccion 	= rand(1, 10000);
		$generaToken 	= $token->setToken($rfc, $transaccion, $integrador);
		$tokenGenerado 	= $token->getToken();

		$Timbra 		= new Timbrado();
		$timbrar 		= $Timbra->setTimbrado($archivoXML, $rfc, $transaccion, $tokenGenerado);                               
		$cfdi 			= $Timbra->getTimbrado();
		
		#echo $archivoXML;
		#echo 'cfdi: '.$cfdi;
		
		if($timbrar)
        {
			#=================================================================================#
			$timbre		=$ruta.'cfdi'.$folio.'Timbre.xml'; #Es el archivo XML Timbrado
			#$timbre='media/timbre.xml'; #Es el archivo XML Timbrado
			$fichero	=fopen($timbre,"w");	
			fwrite($fichero,$cfdi);
			fclose($fichero);
			
			#=================================================================================#
			$UUID			="";
			$fechaTimbrado	="";
			$sello			="";
			$certificado	="";
			$cadenaTimbre	="";
			$rutaFicheros	='media/fel/';
			$cadenaOriginal	=$ruta.'cadenaTimbre'.$folio.'.txt';
			$selloSat		=$ruta.'selloSat'.$folio.'.txt';
			
			$timbre			=$ruta.'cfdi'.$folio.'Timbre.xml'; #Es el archivo XML Timbrado
			
			/*$XML 			= new DOMDocument();
			$XML->load($timbre);
			
			# INICIAR XSLT
			$xslt 			= new XSLTProcessor();
			
			# IMPORTAR STYLESHEET
			$XSL 			= new DOMDocument();
			$XSL->load($rutaFicheros.'convertir.xslt');
			$xslt->importStylesheet($XSL);
			$cadenaTimbre	= $xslt->transformToXML($XML);
			#-------------------------------------------------------------#
			$XML 			= new DOMDocument();
			$XML->load($timbre);
			
			# INICIAR XSLT
			$xslt = new XSLTProcessor();
			
			# IMPORTAR STYLESHEET
			$XSL 			= new DOMDocument();
			$XSL->load($rutaFicheros.'selloSat.xslt');
			$xslt->importStylesheet($XSL);
			$datosSello		= $xslt->transformToXML($XML);
			$tamano=strlen($datosSello);*/
			#-------------------------------------------------------------#
			
			exec("xsltproc ".$rutaFicheros.'convertir.xslt'." ".$timbre." > ".$cadenaOriginal);
			$cadenaTimbre	=openFile($cadenaOriginal,"READ","");
			
			$selloSat		=$ruta.'selloSat'.$folio.'.txt';
			
			exec("xsltproc ".$rutaFicheros.'selloSat.xslt'." ".$timbre." > ".$selloSat);
			$datosSello		=openFile($selloSat,"READ","");
			$tamano			=strlen($datosSello);
	
			$b=0;
			
			for($i=0;$i<$tamano;$i++)
			{
				if($b==1)
				{
					if($datosSello[$i]!="*")
					{
						$UUID.=$datosSello[$i];
					}
				}
				
				if($b==2)
				{
					if($datosSello[$i]!="*")
					{
						$fechaTimbrado.=$datosSello[$i];
					}
				}
				
				if($b==3)
				{
					if($datosSello[$i]!="*")
					{
						$sello.=$datosSello[$i];
					}
				}
				
				if($b==4)
				{
					if($datosSello[$i]!="*")
					{
						$certificado.=$datosSello[$i];
					}
				}
				
				if($datosSello[$i]=="*")
				{
					$b++;
				}
			}
			
			$data['xml']			=$cfdi;
			$data['folio']			=$folio;
			$data['cadenaTimbre']	=$cadenaTimbre;
			$data['cadenaOriginal']	=$cadena;
			$data['selloDigital']	=$selloDigital;
			$data['UUID']			=$UUID;
			$data['fechaTimbrado']	=$fechaTimbrado;
			$data['selloSat']		=$sello;
			$data['certificado']	=$certificado;
			
			#$selloDigital=openFile($sello,"READ","");
			
			if(strlen($UUID)>3)
			{
				$this->session->set_userdata('notificacion',"El cfdi se ha creado correctamente");
				$this->agregarFactura($data,$cotizacion,$cliente);
			}
			else
			{
				#$this->session->set_userdata('errorNotificacion',$cfdi);
				
				$this->db->trans_rollback(); #Revertir si hubo algun fallo en las actualizaciones
				$this->db->trans_complete();
				
				$this->resultado=$cfdi;
			}
		}
		else
		{
			#$this->session->set_userdata('errorNotificacion',$cfdi);
			
			$this->db->trans_rollback(); #Revertir si hubo algun fallo en las actualizaciones
			$this->db->trans_complete();
				
			$this->resultado=$cfdi;
		}
	}
	
	public function crearFacturaFisica($idFactura)
	{
		ini_set("memory_limit","1500M");
		set_time_limit(0);
		
		$this->load->library('ccantidadletras');
		$this->load->library('mpdf/mpdf');
		$this->load->helper('sat');
		
		$data['factura'] 		=$this->obtenerFactura($idFactura);
		$data['configuracion'] 	=$this->configuracion->obtenerEmisor($data['factura']->idEmisor);
		
		$ruta					='media/fel/'.$data['configuracion']->rfc.'/folio'.$data['factura']->serie.$data['factura']->folio.'/';
		
		if(!file_exists($ruta))
		{
			crearDirectorio($ruta);
		}
		
		$data['cliente'] 		=$this->clientes->obtenerDatosCliente($data['factura']->idCliente);
		$data['retencion'] 		=$this->obtenerRetencion($idFactura);
		$data['productos'] 		=$this->obtenerProductosFacturados($idFactura);

		generarCodigoBidimensional($data['factura'],$data['configuracion']);
		
		$this->ccantidadletras->setIdioma("ES");
        $this->ccantidadletras->setNumero($data['factura']->total);
		$this->ccantidadletras->setMoneda($data['factura']->divisa);//
		
		$CantidadLetras			=$this->ccantidadletras->PrimeraMayuscula();
		$data['cantidadLetra']	=$CantidadLetras;

		/*$html	=$this->load->view('facturacion/principal',$data,true);
		$pie 	= $this->load->view('facturacion/pie',$data,true);*/
		$html	=$this->load->view('facturacion/factura',$data,true);
		$pie 	= $this->load->view('facturacion/pie',$data,true);
		
		
		$this->mpdf->mPDF('en-x','Letter','','',10,10,10,70,6,0);
		$this->mpdf->SetHTMLFooter($pie);
		$this->mpdf->SetHTMLFooter($pie,'E');
		$this->mpdf->mirrorMargins = 1;
		$this->mpdf->WriteHTML($html);

		$pdf				=$ruta.$data['configuracion']->rfc.'_'.$data['factura']->serie.$data['factura']->folio.'.pdf';
		$xml				=$ruta.$data['configuracion']->rfc.'_'.$data['factura']->serie.$data['factura']->folio.'.xml';
		
		$this->mpdf->Output($pdf,'F');
		
		#CREAR EL XML FISICO
		$fichero	=fopen($xml,"w");	
		fwrite($fichero,$data['factura']->xml);
		fclose($fichero);
		
		return true;
	}
	
	//->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->
	//PARA LA FACTURACIÓN DEL SAT
	//->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->
	public function obtenerFacturaSat($idFactura)
	{
		$sql=" select * from facturas_sat
		where idFactura='$idFactura' ";
		
		return $this->db->query($sql)->row();
	}
	
	//CREAR ARCHIVOS PARA CFDI
	
	
	public function crearFactura($idFactura,$imprimir='0')
	{
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$this->load->helper('qrlib');
		$this->load->library('ccantidadletras');
		$this->load->library('mpdf/mpdf');
		
		$data['factura'] 		= $this->obtenerFactura($idFactura);
		$data['cliente'] 		= $this->clientes->obtenerDatosCliente($data['factura']->idCliente);
		$data['retencion'] 		= $this->obtenerRetencion($idFactura);
		$data['productos'] 		= $this->obtenerProductosFacturados($idFactura);
		$data['configuracion'] 	= $this->obtenerEmisor($data['factura']->idEmisor);
		$data['cuentas']		= $this->configuracion->obtenerCuentasReportes();	
		
		generarCodigoBidimensional($data['factura'],$data['configuracion']);
		
		$this->ccantidadletras->setIdioma("ES");
        $this->ccantidadletras->setNumero($data['factura']->total);
		$this->ccantidadletras->setMoneda($data['factura']->divisa);//

		$data['cantidadLetra']	= $this->ccantidadletras->PrimeraMayuscula();

		/*$html					= $this->load->view('facturacion/facturaCapymet',$data,true);
		$pie 					= $this->load->view('facturacion/pieCapymet',$data,true);*/
		
		$html					= $this->load->view('facturacion/factura',$data,true);
		$pie 					= $this->load->view('facturacion/pie',$data,true);
		
		$this->mpdf->mPDF('en-x','Letter','','',10,10,5,78,2,0);
		$this->mpdf->SetHTMLFooter($pie);
		$this->mpdf->SetHTMLFooter($pie,'E');
		$this->mpdf->mirrorMargins = 1;
		$this->mpdf->WriteHTML($html);
		
		if($imprimir=='0')
		{
			$this->mpdf->Output();
		}
		
		if($imprimir=='1')
		{
			$this->mpdf->Output(carpetaCfdi.$data['configuracion']->rfc.'/folio'.$data['factura']->serie.$data['factura']->folio.'/'.$data['configuracion']->rfc.'_'.$data['factura']->serie.$data['factura']->folio.'.pdf','F');
		}
		
		if($imprimir=='2')
		{
			$this->mpdf->Output($data['configuracion']->rfc.'_'.$data['factura']->serie.$data['factura']->folio.'.pdf','D');
		}
	}
	
	public function reciboNomina($idFactura,$imprimir='0')
	{
		$this->load->helper('qrlib');
		$this->load->library('ccantidadletras');
		$this->load->library('mpdf/mpdf');
		
		$data['factura'] 		= $this->obtenerFactura($idFactura);
		$data['percepciones'] 	= $this->reportes->obtenerPercepciones($idFactura);
		$data['deducciones'] 	= $this->reportes->obtenerDeducciones($idFactura);
		$data['empleado'] 		= $this->reportes->obtenerEmpleado($idFactura);
		$data['emisor']			= $this->configuracion->obtenerEmisor($data['factura']->idEmisor);
		$data['reporte']		= 'nomina/reciboNomina';
		
		generarCodigoBidimensional($data['factura'],$data['emisor']);
		
		$this->ccantidadletras->setIdioma("ES");
        $this->ccantidadletras->setNumero($data['factura']->total);
		$this->ccantidadletras->setMoneda("pesos");//
		$data['cantidadLetra']	=$this->ccantidadletras->PrimeraMayuscula();

		$html					= $this->load->view('nomina/principal',$data,true);
		$pie 					= $this->load->view('nomina/pieNomina',$data,true);
		
		$this->mpdf->mPDF('en-x','Letter','','',10,10,10,10,2,1);
		$this->mpdf->SetHTMLFooter($pie);
		$this->mpdf->SetHTMLFooter($pie,'E');
		$this->mpdf->mirrorMargins = 1;
		$this->mpdf->WriteHTML($html);
		
		if($imprimir=='0')
		{
			$this->mpdf->Output();
		}
		
		if($imprimir=='1')
		{
			$this->mpdf->Output(carpetaCfdi.$data['emisor']->rfc.'/folio'.$data['factura']->serie.$data['factura']->folio.'/'.$data['emisor']->rfc.'_'.$data['factura']->serie.$data['factura']->folio.'.pdf','F');
		}
	}
	
	//FACTURA SAT
	public function crearFacturaSat($idFactura,$criterio=0)
	{
		$this->load->helper('xml');
		
		$this->load->library('ccantidadletras');
		$this->load->library('mpdf/mpdf');
		
		/*$this->load->library('ccantidadletras');
		$this->load->library('mpdf/mpdf');*/
		
		$data['factura'] 		= $this->reportes->obtenerFacturaSat($idFactura);
		
		$carpeta				= "media/sat/";
		
		if(strlen($data['factura']->xml)>10)
		{
			$fichero				= $data['factura']->rfcEmisor.'_'.obtenerFechaMesCorto($data['factura']->fecha).'_'.$data['factura']->serie.$data['factura']->folio.'.xml';
			guardarFichero($carpeta.$fichero,$data['factura']->xml);
			$data['xml'] 			= procesarXmlCfdi($carpeta.$fichero);
		}
		
		$this->generarCodigoBidimensionalSat($data['xml'],$data['factura']);
		
		
		$this->ccantidadletras->setIdioma("ES");
        $this->ccantidadletras->setNumero($data['xml'][4]);
		$this->ccantidadletras->setMoneda($data['xml'][14]);//
		
		$CantidadLetras			=$this->ccantidadletras->PrimeraMayuscula();
		$data['cantidadLetra']	=$CantidadLetras;

		$html	=$this->load->view('reportes/facturacionSat/pdf/factura',$data,true);
		$pie 	= $this->load->view('reportes/facturacionSat/pdf/pie',$data,true);
		
		$this->mpdf->mPDF('en-x','Letter','','',10,10,5,47,2,0);
		$this->mpdf->SetHTMLFooter($pie);
		$this->mpdf->SetHTMLFooter($pie,'E');
		$this->mpdf->mirrorMargins = 1;
		$this->mpdf->WriteHTML($html);
		
		if($criterio==0)
		{
			$this->mpdf->Output();
		}
		else
		{
			$this->mpdf->Output('media/sat/'.$data['factura']->rfcEmisor.'_'.obtenerFechaMesCorto($data['factura']->fecha).'_'.$data['factura']->serie.$data['factura']->folio.'.pdf','F');
		}
	}
	
	public function generarCodigoBidimensionalSat($xml,$factura)
	{
		$this->load->helper('qrlib');

		$partes			= explode(".",$xml[4]);
		
		$entero			= $partes[0];
		$decimal		= $partes[1];
		
		$valor			= strlen($entero);
		$ceros			= 10-$valor;
		$ceroEntero		= "";
		
		for($i=1;$i<=$ceros;$i++)
		{
			$ceroEntero.="0";
		}
		
		$ceroEntero.=$entero;
		
		$valor			= strlen($decimal);
		$ceros			= 6-$valor;
		$ceroDecimal	= "";
		
		for($i=1;$i<=$ceros;$i++)
		{
			$ceroDecimal.="0";
		}
		
		$ceroDecimal=$decimal.$ceroDecimal;
		
		$codigoBidimensional = "?re=".$xml[15]."&rr=".$xml[24]."&tt=".$ceroEntero.".".$ceroDecimal."&id=".$xml[40]."";

		$codigo='media/sat/'.$xml[15].'_'.obtenerFechaMesCorto($factura->fecha).'_'.$xml[11].$xml[12].'.png';
		
		#if(!file_exists($codigo))
		#{
			QRcode::png($codigoBidimensional, $codigo, 'L', 3, 2);
		#}
	}
	
	public function timbrarReciboNomina()
	{
		$this->load->library('factor');
		
		#$ficheroXML		= 'media/cfdi.xml';
		$ficheroXML		= leerFichero('media/molita.txt',"READ","");
		
		#echo 'XML: '.$ficheroXML.'<br /><br />';
		
		$config			= $this->obtenerConfiguracion();
		$timbrado 		= new Factor();
		$respuesta 		= $timbrado->obtenerTimbre($config->usuarioFactor, $config->passwordFactor, $ficheroXML);

		if(!$respuesta['estatus'])
		{
			if(strlen($respuesta['codigoError'])>0)
			{
				#$this->registrarError($respuesta['codigoError'],$respuesta['comentarios'],$configuracion->idEmisor);	
			}
			
			echo $ficheroXML;
			echo '<br /><br />';
		
			var_dump($respuesta);
			return 0;
		}
		
		echo $ficheroXML;
		echo '<br /><br />';
		var_dump($respuesta);
		
		/*if($respuesta['estatus'])
		{
			$timbre		=$carpetaFolio.'cfdi'.$folio.'Timbre.xml'; #Es el archivo XML Timbrado
			$fichero	=fopen($timbre,"w");	
			fwrite($fichero,$respuesta['xml']);
			fclose($fichero);
			
			$data['xml']			=$respuesta['xml'];
			$data['folio']			=$folio;
			$data['cadenaTimbre']	=$respuesta['cadenaTimbre'];
			$data['cadenaOriginal']	=$cadena;
			$data['selloDigital']	=$sello;
			$data['UUID']			=$respuesta['uuid'];
			$data['fechaTimbrado']	=$respuesta['fechaTimbrado'];
			$data['selloSat']		=$respuesta['selloSat'];
			$data['certificado']	=$respuesta['certificado'];
			
			$this->session->set_userdata('notificacion',"El cfdi se ha creado correctamente");
			$this->agregarFactura($data,$cotizacion,$cliente,$configuracion,$productos,$divisa);
		}*/
	}
	
	public function obtenerRelaciones($idFactura)
	{
		$sql=" select a.tipoRelacion, c.UUID
		from facturas as a
		inner join facturas_relacionados as b
		on a.idFactura=b.idFactura
		inner join facturas as c
		on c.idFactura=b.idFacturaRelacion
		
		where a.idFactura='$idFactura'";
		
		return $this->db->query($sql)->result();
	}
}
?>
