<?php
class Globales_modelo extends CI_Model
{
	protected $_fecha_actual;
	protected $_table;
	protected $idLicencia;
	protected $resultado;
	protected $_user_id;
	protected $fecha;
	protected $idFactura;

	function __construct()
	{
		parent::__construct();

        $this->_user_id 		= $this->session->userdata('id');
		$this->idLicencia 		= $this->session->userdata('idLicencia');

		$datestring   			= "%Y-%m-%d %H:%i:%s";
		$this->_fecha_actual 	= mdate($datestring,now());
		$this->fecha 			= date('Y-m-d');
		$this->resultado		= "1";
		$this->idFactura		= 0;
	}

	public function editarNumeroVentas()
	{
		$data=array
		(
			'numeroVentas'	 => $this->input->post('numeroVentas'),
			'importeDinero'	 => $this->input->post('importeDinero')
		);

		$this->db->where('idLicencia',$this->input->post('idLicencia'));
		$this->db->update('configuracion',$data);
	}
	
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	//REPORTE DE CHECADOR
	public function contarVentasGlobal($inicio,$fin,$idLicencia=0,$criterio='')
	{
		$sql=" select a.idCotizacion
		from cotizaciones as a
		where  a.idLicencia='$idLicencia'
		and date(a.fechaCompra) between '$inicio' and '$fin'
		and a.estatus='1'
		and a.cancelada='0'
		and a.activo='1' ";
		
		$sql.=strlen($criterio)>0?" and a.folio like '%$criterio%'   ":'';

		return $this->db->query($sql)->num_rows();
	}
	
	public function obtenerVentasGlobal($numero,$limite,$inicio,$fin,$idLicencia=0,$criterio)
	{
		$sql=" select a.idCotizacion, a.folio, a.folioConta, a.total, a.fechaCompra,
		(select b.nombre from configuracion_estaciones as b where b.idEstacion=a.idEstacion) as estacion,
		(select b.nombre from catalogos_formas as b inner join catalogos_ingresos as c on c.idForma=b.idForma where c.idVenta=a.idCotizacion limit 1) as formaPago,
		(select count(b.idFactura) from facturas as b where b.idFactura=a.idFactura and b.cancelada='0') as facturas
		from cotizaciones as a
		where  a.idLicencia='$idLicencia'
		and date(a.fechaCompra) between '$inicio' and '$fin'
		and a.estatus='1'
		and a.cancelada='0'
		and a.activo='1' ";
		
		$sql.=strlen($criterio)>0?" and a.folio like '%$criterio%'   ":'';
		
		$sql .=$numero>0?" limit $limite,$numero ":''; 


		return $this->db->query($sql)->result();
	}
	
	public function sumarVentasGlobal($inicio,$fin,$idLicencia=0,$criterio='',$facturar=0)
	{
		$sql=" select coalesce(sum(a.total),0) as total,
		coalesce(sum(a.subTotal),0) as subTotal,
		coalesce(sum(a.descuento),0) as descuento,
		coalesce(sum(a.iva),0) as iva
		
		from cotizaciones as a
		where  a.idLicencia='$idLicencia'
		and date(a.fechaCompra) between '$inicio' and '$fin'
		and a.estatus='1'
		and a.cancelada='0'
		and a.activo='1' ";
		
		$sql.=$facturar>0?" and a.folioConta > 0":'';
		
		$sql.=strlen($criterio)>0?" and a.folio like '%$criterio%'   ":'';

		return $this->db->query($sql)->row();
	}
	
	public function obtenerVentasGlobalFactura($inicio,$fin,$idLicencia=0,$criterio)
	{
		$sql=" select a.idCotizacion, a.folio, a.folioConta, a.total, a.fechaCompra,
		a.subTotal, a.iva, a.ivaPorcentaje, a.descuento
		from cotizaciones as a
		where  a.idLicencia='$idLicencia'
		and date(a.fechaCompra) between '$inicio' and '$fin'
		and a.estatus='1'
		and a.cancelada='0'
		and a.activo='1'
		and a.idFactura=0 ";
		
		$sql.=strlen($criterio)>0?" and a.folio like '%$criterio%'   ":'';

		return $this->db->query($sql)->result();
	}
	
	public function registrarFacturaGlobal()
	{
		if(!$this->facturacion->comprobarFoliosDisponibles())
		{
			return array('0','Los folios se han terminado, por favor consulte con el administrador','','','');
		}
		
		$this->db->trans_start();
		
		$this->load->helper('sat');
		$this->load->helper('global');
		
		$inicio					= $this->input->post('inicio');
		$fin					= $this->input->post('fin');
		$idLicencia				= $this->input->post('idLicencia');
		$criterio				= $this->input->post('criterio');

		$idCliente				= $this->input->post('txtIdClienteGlobal');
		$idEmisor				= $this->input->post('selectEmisoresGlobal');
		$configuracion			= $this->facturacion->obtenerEmisor($idEmisor);
		$cliente				= $this->facturacion->obtenerCliente($idCliente);
		$divisa					= $this->facturacion->obtenerDivisa(1);
		$ventas					= $this->obtenerVentasGlobalFactura($inicio,$fin,$idLicencia,$criterio);

		if(strlen($cliente->rfc)<12 or strlen($cliente->razonSocial) <3 or strlen($cliente->codigoPostal) !=5 )
		{
			$this->db->trans_rollback(); #Revertir si hubo algun fallo en las actualizaciones
			$this->db->trans_complete();

			return array('0',"El cliente no tiene los datos fiscales necesarios para crear la factura");
		}
		
		$folio					= $this->facturacion->obtenerFolio($idEmisor);
		
		if($folio<1)
		{
			$this->db->trans_rollback(); #Revertir si hubo algun fallo en las actualizaciones
			$this->db->trans_complete();
			
			return array('0',"Error de servidor SAT");
		}
		

		$carpetaUsuario			= carpetaCfdi.$configuracion->rfc.'/';
		$carpetaFolio			= $carpetaUsuario.'folio'.$configuracion->serie.$folio.'/';
		$cfd					= $carpetaFolio.'cfd'.$folio.'.xml';

		crearDirectorio($carpetaFolio);
		
		$sello					= "";
		$certificado			= "";
		$ficheroXML				= facturaGlobalVentas($configuracion,$cliente,$sello,$certificado,$this->_fecha_actual,$folio,$ventas);
		
		guardarArchivoXML($cfd,$ficheroXML);
		
		exec("xsltproc ".carpetaCfdi.'cadenaoriginal4.xslt'." ".$cfd." > ".$carpetaFolio.'cadena.txt');
		
		exec("openssl pkcs8 -inform DER -in ".$carpetaUsuario.$configuracion->llave." -passin pass:".$configuracion->passwordLlave." -out ".$carpetaFolio.'certificado.txt');
		exec("openssl dgst -sha256 -sign ".$carpetaFolio."certificado.txt ".$carpetaFolio."cadena.txt | openssl enc -base64 -A > ".$carpetaFolio.'sello.txt');
		exec("openssl enc -base64 -in ".$carpetaUsuario.$configuracion->certificado." -out ".$carpetaFolio.'certificadoImprimir.txt');
		
		$certificado			= leerFichero($carpetaFolio.'certificadoImprimir.txt',"READ","");
		$certificado 			= QuitarEspaciosXML($certificado,"B");
		$sello					= leerFichero($carpetaFolio.'sello.txt',"READ","");
		$sello 					= QuitarEspaciosXML($sello,"B");
		$cadena					= leerFichero($carpetaFolio.'cadena.txt',"READ","");

		$ficheroXML				= facturaGlobalVentas($configuracion,$cliente,$sello,$certificado,$this->_fecha_actual,$folio,$ventas);
		
		if(guardarArchivoXML($cfd,$ficheroXML))
		{
			if($configuracion->pac=='4gFactor')
			{
				$this->timbrarFactor($ficheroXML,$folio,$carpetaFolio,$sello,$cadena,$cliente,$configuracion,$ventas);
			}
			
			if($configuracion->pac=='finkok')
			{
				$this->timbrarFinkok($ficheroXML,$folio,$carpetaFolio,$sello,$cadena,$cliente,$configuracion,$ventas);
			}
		}
		
		#$this->configuracion->registrarBitacora('Registrar factura manual','Reporte ventas público',$configuracion->serie.$folio.', '.$cliente->empresa); //Registrar bitácora
		
		if ($this->db->trans_status() === FALSE or $this->resultado!="1")
		{
			$this->db->trans_rollback(); #Revertir si hubo algun fallo en las actualizaciones
			$this->db->trans_complete();
			
			return array('0',$this->resultado);
		}
		else
		{
			$this->db->trans_commit(); #Guargar los cambios si todo ha sido correcto
			$this->db->trans_complete();
			
			$this->facturacion->borrarArchivosTemporales($carpetaFolio);
			
			return array('1','');
		}
	}
	
	public function timbrarFinkok($ficheroXML,$folio,$carpetaFolio,$sello,$cadena,$cliente,$configuracion,$ventas)
	{
		$this->load->library('finkok');
		$this->load->helper('xmlcfdi');

		$timbrado 		= new Finkok();
		$respuesta 		= $timbrado->obtenerTimbre($configuracion->usuarioPac,$configuracion->passwordPac,$configuracion->url,$ficheroXML.'aa');

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
			
			$this->agregarFactura($data,$configuracion,$cliente,$ventas);
			
			$this->facturacion->borrarArchivosTemporales($carpetaFolio);
		}
	}
	
	public function timbrarFactor($ficheroXML,$folio,$carpetaFolio,$sello,$cadena,$cliente,$configuracion,$ventas)
	{
		$this->load->library('factor');
		
		$timbrado 		= new Factor();
		$respuesta 		= $timbrado->obtenerTimbre($configuracion->usuarioPac, $configuracion->passwordPac, $ficheroXML,$configuracion->url);

		if(!$respuesta['estatus'])
		{
			if(strlen($respuesta['codigoError'])>0)
			{
				$this->facturacion->registrarError($respuesta['codigoError'],$respuesta['comentarios'],$configuracion->idEmisor);	
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

			$this->agregarFactura($data,$configuracion,$cliente,$ventas);
		}
	}

	public function agregarFactura($timbre,$configuracion,$cliente,$ventas)
	{
		$subTotal	= 0;
		$total		= 0;
		$iva		= 0;
		$ivas		= 0;
		$ieps		= 0;
		$descuentos	= 0;
		
		foreach($ventas as $row)
		{
			$precio		= round($row->subTotal,decimales);
			$descuento	= round($row->descuento,decimales);
			$importe	= $precio;
	
			$diferencia	= $importe-$descuento;
			$diferencia	= round($diferencia,decimales);
			
			$impuesto	= $diferencia*($row->ivaPorcentaje/100);
			$impuesto	= round($impuesto,decimales);
			
			$subTotal	+=$importe;
			$ivas		+=$impuesto;
			$descuentos	+=$descuento;
			
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
			'idCliente'				=> $cliente->idCliente,
			
			'subTotal'				=> $subTotal,
			#'ivaPorcentaje'			=> $totales->ivaPorcentaje,
			'iva'					=> $ivas,
			
			
			'descuento'				=> $descuentos,
			'descuentoPorcentaje'	=> 0,
			
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
			'idCotizacion'			=> 0,
			
			'documento'				=> 'FACTURA',
			'tipoComprobante'		=> "ingreso",
			'serie'					=> $configuracion->serie,
			'condicionesPago'		=> $this->input->post('txtCondiciones'),
			
			'parcial'				=> 0,
			'divisa'				=> 'Pesos',
			'claveDivisa'			=> 'MXN',
			'tipoCambio'			=> 1,
			'global'				=> '1',
			'idUsuario'				=> $this->_user_id,
			'idEmisor'				=> $configuracion->idEmisor,
			'metodoPago'			=> $this->input->post('metodoPagoTexto'),
			'formaPago'				=> $this->input->post('formaPagoTexto').' '.$this->input->post('txtCuentaPago'),
			'usoCfdi'				=> $this->input->post('usoCfdiTexto'),
			'idPac'					=> $configuracion->idPac,
			
			'versionCfdi'			=> '4.0',
			'regimenFiscalCliente'	=> $cliente->claveRegimen.', '.$cliente->regimenFiscal,
			'certificadoEmisor'		=> $configuracion->numeroCertificado,
		);
		
		$this->db->insert('facturas',$data);
		$idFactura = $this->db->insert_id();
		
		#-------------------------------------------------------------------------------------#
		$data=array();
		$data['encriptacion']	=sha1("'".$idFactura.$timbre['fechaTimbrado']."'"); 
		
		$this->db->where('idFactura',$idFactura); 
		$this->db->update('facturas',$data);
		
		#GUARDAR EL DETALLE DE PRODUCTOS
		#-------------------------------------------------------------------------------------#
		foreach($ventas as $row)
		{
			$precio		= round($row->subTotal,decimales);
			$descuento	= round($row->descuento,decimales);
			$importe	= $precio;
	
			$diferencia	= $importe-$descuento;
			$diferencia	= round($diferencia,decimales);
			
			$impuesto	= $diferencia*($row->ivaPorcentaje/100);
			$impuesto	= round($impuesto,decimales);
			
			#$Impuesto	= obtenerImpuestoPinata($row->impuesto);
			
			$data=array
			(
				'idFactura'				=> $idFactura,
				'idProducto'			=> 0,
				'nombre'				=> $row->folio,
				'precio'				=> $precio,
				'importe'				=> $importe,
				'cantidad'				=> 1,
				'descuento'				=> $descuento,
				'descuentoPorcentaje'	=> 0,
				'unidad'				=> $this->input->post('txtUnidad'),
				'claveUnidad'			=> $this->input->post('txtClaveUnidad'),
				'claveProducto'			=> $this->input->post('txtClaveProducto'),
				'claveDescripcion'		=> $this->input->post('txtClaveDescripcion'),
				
				'claveObjetoImpuesto'	=> '02',
				'objetoImpuesto'		=> 'Si objeto de impuesto',
			);
			
			$this->db->insert('facturas_detalles',$data);
			$idDetalle	= $this->db->insert_id();
			
			$data=array
			(
				'idDetalle'				=> $idDetalle,
				'tasa'					=> $row->ivaPorcentaje,
				'importe'				=> $impuesto,
				'impuesto'				=> '002',
				'nombreImpuesto'		=> 'IVA',
				'base'					=>  $diferencia,
			);
			
			$this->db->insert('facturas_detalles_impuestos',$data);
		}
		
		#-------------------------------------------------------------------------------------#
		
		foreach($ventas as $row)
		{
			$this->db->where('idCotizacion',$row->idCotizacion);
			$this->db->update('cotizaciones',array('idFactura'=>$idFactura));
			
			//AGREGAR LA FACTURA CON LA COTIZACIÓN
			$this->db->insert('rel_factura_cotizacion',array('idFactura'=>$idFactura,'idCotizacion'=>$row->idCotizacion));
		}
		
		//REGISTRAR LA FACTURA GLOBAL
		$this->registrarGlobal($idFactura);
	}
	
	public function registrarGlobal($idFactura)
	{
		$data=array
		(
			'idFactura'		=> $idFactura,
			'anio'			=> $this->input->post('selectAnio'),
			'mes'			=> $this->input->post('mes'),
			'periodicidad'	=> $this->input->post('periodicidad'),
		);

		$this->db->insert('facturas_global',$data);
	}
}
