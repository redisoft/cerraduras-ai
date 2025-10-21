<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pagos_modelo extends CI_Model
{
	protected $fecha;
	protected $fechaCorta;
	protected $idUsuario;
	protected $resultado;
	protected $idFactura;
	protected $rfc;
	protected $idLicencia;
	
    function __construct() 
	{
		parent::__construct();
		
		$this->fecha			= date("Y-m-d H:i:s");
		$this->fechaCorta		= date("Y-m-d");
		$this->idUsuario		= $this->session->userdata('id');
		$this->idLicencia		= $this->session->userdata('idLicencia');
		$this->resultado		= '1';
		$this->idFactura		= 0;
		
		$this->cambiarFechaActual();
    }
	
	public function cambiarFechaActual()
	{
		$sql="select date_sub('".date('Y-m-d H:i:s')."', interval 10 minute) as fechaActual";
		
		$this->fecha	= $this->db->query($sql)->row()->fechaActual;
	}
	
	public function obtenerFacturaRelacion($idFactura)
	{
		$sql=" select a.*
		from facturas as a
		inner join facturas_relacionados as b
		on a.idFactura=b.idFacturaRelacion
		where b.idFactura='$idFactura' ";
		
		return $this->db->query($sql)->row();
	}
	
	public function obtenerPago($idFactura)
	{
		$sql=" select * from facturas_pagos
		where idFactura='$idFactura' ";
		
		return $this->db->query($sql)->row();
	}
	
	public function obtenerFacturasPagos($idFactura)
	{
		$sql=" select a.idFactura, a.fecha,  a.total, a.rfc, a.serie, a.folio,
		a.documento, a.tipoComprobante, a.empresa, a.cancelada, a.metodoPago, c.importe,
		c.fechaPago
		from facturas  as a
		
		inner join facturas_relacionados as b
		on a.idFactura = b.idFactura	
		
		inner join  facturas_pagos as c 
		on c.idFactura = b.idFactura
		
		where b.idFacturaRelacion='$idFactura'
		and b.tipoRelacion='1' 
		order by a.folio desc ";

		return $this->db->query($sql)->result();
	}
	
	public function obtenerFactura($idFactura)
	{
		$sql=" select a.*,
		(select coalesce(sum(b.importe),0) from facturas_pagos as b
		inner join facturas_relacionados as c 
		on c.idFactura = b.idFactura
		
		inner join facturas as d
		on c.idFactura = d.idFactura
		
		where c.idFacturaRelacion=a.idFactura
		and d.cancelada='0') as saldo,
		
		(select count(b.idPago) from facturas_pagos as b
		inner join facturas_relacionados as c 
		on c.idFactura = b.idFactura
		where c.idFacturaRelacion=a.idFactura) + 1 as parcialidad 
		from facturas as a
		where a.idFactura='$idFactura' ";
		
		return $this->db->query($sql)->row();
	}

	public function registrarPago()
	{
		if(!$this->facturacion->comprobarFoliosDisponibles())
		{
			return array('0','Los folios se han terminado, por favor consulte con el administrador','','','');
		}
		
		$idFactura		= $this->input->post('txtIdFactura');
		$idEmisor		= $this->input->post('selectEmisoresGlobal');

		$configuracion	= $this->facturacion->obtenerEmisor($idEmisor);
		$factura		= $this->obtenerFactura($idFactura);
		$divisa			= $this->facturacion->obtenerDivisa(1);
		$folio			= $this->facturacion->obtenerFolio($idEmisor);
		$cliente		= $this->clientes->obtenerDireccionesEditar($factura->idDireccion);
		
		if($folio<1)
		{
			return array(0=>'0',1=>'No hay folios disponibles para el contribuyente');
		}
		
		$this->db->trans_start(); 
		
		$this->load->helper('sat');
		$this->load->helper('pagos');

		$carpetaUsuario		= carpetaCfdi.$configuracion->rfc.'/';
		$carpetaFolio		= $carpetaUsuario.'folio'.$configuracion->serie.$folio.'/';
		$cfd				= $carpetaFolio.'cfd'.$folio.'.xml';
		
		crearDirectorio($carpetaFolio);
		
		$sello				= "";
		$certificado		= "";

		$ficheroXML			= crearXmlPago($configuracion,$factura,$sello,$certificado,$this->fecha,$folio,$divisa,$cliente);

		guardarFichero($cfd,$ficheroXML);

		//PARA SERVIDORES CON PROCESADOR XSTL
		exec("xsltproc ".carpetaCfdi.'cadenaoriginal4.xslt'." ".$cfd." > ".$carpetaFolio.'cadena.txt');
		exec("openssl pkcs8 -inform DER -in ".$carpetaUsuario.$configuracion->llave." -passin pass:".$configuracion->passwordLlave." -out ".$carpetaFolio.'certificado.txt');
		exec("openssl dgst -sha256 -sign ".$carpetaFolio."certificado.txt ".$carpetaFolio."cadena.txt | openssl enc -base64 -A > ".$carpetaFolio.'sello.txt');
		exec("openssl enc -base64 -in ".$carpetaUsuario.$configuracion->certificado." -out ".$carpetaFolio.'certificadoImprimir.txt');
		
		$certificado	= leerFichero($carpetaFolio.'certificadoImprimir.txt',"READ","");
		$certificado 	= QuitarEspaciosXML($certificado,"B");
		$sello			= leerFichero($carpetaFolio.'sello.txt',"READ","");
		$sello 			= QuitarEspaciosXML($sello,"B");
		$cadena			= leerFichero($carpetaFolio.'cadena.txt',"READ","");

		$ficheroXML		= crearXmlPago($configuracion,$factura,$sello,$certificado,$this->fecha,$folio,$divisa,$cliente);

		if(guardarFichero($cfd,$ficheroXML))
		{
			if($configuracion->pac=='4gFactor')
			{
				$this->timbrarFactor($ficheroXML,$folio,$carpetaFolio,$sello,$cadena,$factura,$configuracion,$divisa,$cliente);
			}
			
			if($configuracion->pac=='finkok')
			{
				$this->timbrarFinkok($ficheroXML,$folio,$carpetaFolio,$sello,$cadena,$factura,$configuracion,$divisa,$cliente);
			}
		}
		
		if ($this->db->trans_status() === FALSE or $this->resultado!="1")
		{
			$this->db->trans_rollback(); #Revertir si hubo algun fallo en las actualizaciones
			$this->db->trans_complete();
			
			return array(0=>'0',1=>$this->resultado);
		}
		else
		{
			$this->db->trans_commit(); #Guargar los cambios si todo ha sido correcto
			$this->db->trans_complete();
			
			$this->facturacion->borrarArchivosTemporales($carpetaFolio);
			
			return array('1','El cfdi con folio '.$configuracion->serie.$folio.' se ha creado correctamente');
		}
	}
	
	public function timbrarFinkok($ficheroXML,$folio,$carpetaFolio,$sello,$cadena,$factura,$configuracion,$divisa,$cliente)
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
				$this->facturacion->registrarError($respuesta['codigoError'],$respuesta['mensaje'],$configuracion->idEmisor);	
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
			
			$this->registrarFactura($data,$factura,$configuracion,$divisa,$cliente);
			
		}
	}
	
	public function timbrarFactor($ficheroXML,$folio,$carpetaFolio,$sello,$cadena,$factura,$configuracion,$divisa,$cliente)
	{
		$this->load->library('factor');
		
		$timbrado 		= new Factor();
		$respuesta 		= $timbrado->obtenerTimbre($configuracion->usuarioPac, $configuracion->passwordPac, $ficheroXML,$configuracion->url);

		if(!$respuesta['estatus'])
		{
			$this->resultado	= $respuesta['mensaje'];
			
			$this->facturacion->registrarError($respuesta['codigoError'],$respuesta['comentarios'],$configuracion->idEmisor);	
			
			return 0;
		}
		
		if($respuesta['estatus'])
		{
			/*#$timbre		=$carpetaFolio.'cfdi'.$folio.'Timbre.xml'; #Es el archivo XML Timbrado
			$timbre					= $carpetaFolio.$configuracion->rfc.'_'.$configuracion->serie.$folio.'.xml'; #Es el archivo XML Timbrado
			$fichero	=fopen($timbre,"w");	
			fwrite($fichero,$respuesta['xml']);
			fclose($fichero);*/
			
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
			$this->registrarFactura($data,$factura,$configuracion,$divisa,$cliente);
		}
	}
	
	public function registrarFactura($timbre,$factura,$configuracion,$divisa,$cliente)
	{
		$saldo				= $this->input->post('txtSaldoFactura')-$this->input->post('txtImportePagar');
		$metodoPago			= $saldo>0?'PPD, Pago en parcialidades o diferido':'PUE, Pago en una sola exhibición';
		
		$data=array
		(
			'rfc'					=> $factura->rfc,
			'empresa'				=> $factura->empresa,
			'calle'					=> $factura->calle,
			'numeroExterior'		=> $factura->numeroExterior,
			'codigoPostal'			=> $factura->codigoPostal,
			'localidad'				=> $factura->localidad,
			'municipio'				=> $factura->municipio,
			'estado'				=> $factura->estado,
			'pais'					=> $factura->pais,
			'codigoPostal'			=> $factura->codigoPostal,
			'telefono'				=> $factura->telefono,
			'email'					=> $factura->email,
			'colonia'				=> $factura->colonia,
			
			'subTotal'				=> 0,
			'iva'					=> 0,
			#'ivaTotal'				=> 0,			
			'descuento'				=> 0,
			'descuentoPorcentaje'	=> 0,
			'total'					=> 0,
			
			'folio'					=> $timbre['folio'],
			'fecha'					=> $this->fecha,
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
			'idCliente'				=> $factura->idCliente,
			'documento'				=> 'PAGO',
			'tipoComprobante'		=> 'Pago',
			'serie'					=> $configuracion->serie,
			'condicionesPago'		=> '',

			'metodoPago'			=> $metodoPago,
			'formaPago'				=> $this->input->post('formaPago'),
			
			'divisa'				=> $divisa->nombre,
			'claveDivisa'			=> $divisa->clave,
			'tipoCambio'			=> $divisa->tipoCambio,

			'condiciones'			=> '',
			'observaciones'			=> '',
			
			'idUsuario'				=> $this->idUsuario,
			'idEmisor'				=> $configuracion->idEmisor,
			'usoCfdi'				=> 'CP01, Pagos',
			'pago'					=> '1',
			
			'idPac'					=> $configuracion->idPac,
			'versionCfdi'			=> '4.0',
			'regimenFiscalCliente'	=> $cliente->claveRegimen.' '.$cliente->regimenFiscal,
			'certificadoEmisor'		=> $configuracion->numeroCertificado,
		);
		
		$this->db->insert('facturas',$data);
		$idFactura = $this->db->insert_id();
		$this->idFactura	= $idFactura;
		#-------------------------------------------------------------------------------------#
		
		//DETALLES DEL PAGO
		$data=array
		(
			'idFactura'				=> $idFactura,
			'nombre'				=> 'Pago',
			'unidad'				=> 'Actividad',
			'claveUnidad'			=> 'ACT',
			'claveProducto'			=> '84111506',
			'claveDescripcion'		=> 'Servicios de facturación',
			'precio'				=> 0,
			'importe'				=> 0,
			'cantidad'				=> 1,
			'descuento'				=> 0,
			'descuentoPorcentaje'	=> 0,
			'claveObjetoImpuesto'	=> '01',
			'objetoImpuesto'		=> 'No objeto de impuesto',
		);
		
		$this->db->insert('facturas_detalles',$data);
		$idDetalle	= $this->db->insert_id();
		
		//DETALLES DEL PAGO
		$data=array
		(
			'idFactura'				=> $idFactura,
			'numeroOperacion'		=> $this->input->post('txtNumeroOperacion'),
			'rfcOrdenante'			=> $this->input->post('txtRfcOrdenante'),
			'cuentaOrdenante'		=> $this->input->post('txtCuentaOrdenante'),
			'rfcBeneficiario'		=> $this->input->post('txtRfcBeneficiario'),
			'cuentaBeneficiario'	=> $this->input->post('txtCuentaBeneficiario'),
			'numeroParcialidad'		=> $this->input->post('txtNumeroParcialidad'),
			'importeAnterior'		=> $this->input->post('txtSaldoFactura'),
			'saldoInsoluto'			=> $this->input->post('txtSaldoFactura')-$this->input->post('txtImportePagar'),
			'importe'				=> $this->input->post('txtImportePagar'),
			'fechaPago'				=> $this->input->post('txtFechaPago'),
			'iva'					=> $this->input->post('txtImporteIva16'),
		);
		
		$this->db->insert('facturas_pagos',$data);
		
		//RELACIONAR EL COMPROBANTE
		$data=array
		(
			'idFactura'			=> $idFactura,
			'idFacturaRelacion'	=> $this->input->post('txtIdFactura'),
			'tipoRelacion'		=> '1',
		);
		
		$this->db->insert('facturas_relacionados',$data);
		
	}
}
